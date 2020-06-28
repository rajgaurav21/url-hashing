<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Exception\CustomException;
use Cake\Log\Log;
use Cake\Utility\Security;

/**
 * UrlHashing Controller
 *
 *
 * @method \App\Model\Entity\UrlHashing[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UrlHashingController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        if ($this->request->is('post')) {
            $originalUrlDetails = $this->request->data;
            log::debug($this->request->data);
            //no imput is given
            if (empty($originalUrlDetails['original_url'])) {
                $this->Flash->error(__('Invalid Data. Please, try again.'));
                return $this->redirect(['action' => 'index']);
            }

            //if expiration date is set for the url
            if (!isset($originalUrlDetails['expiration_date'])) {
                $originalUrlDetails['expiration_date'] = null;
            }

            $hashedKey = $this->UrlHashing->generateKey($originalUrlDetails['original_url']);   
            $result = $this->UrlHashing->saveHashedUrl($originalUrlDetails['original_url'], $hashedKey, $originalUrlDetails['expiration_date']);

            if (!$result) {
                $this->Flash->error(__("Data couldn't be saved. Please try again"));

                return null;
            }
            $this->Flash->success(__('Shortened URL: ' . SERVER_DOMAIN . 'shortenedUrl/' . $hashedKey));

            return $this->redirect(['action' => 'view', $hashedKey]);
        }
    }

    /**
     * View method
     *
     * @param string|null $hash Url Hashed Key.
     * @return null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($hash)
    {
        //fetch record based on the hash key
        $urlHashing = $this->UrlHashing->find()
            ->where(['hash' => $hash])
            ->first();

        if (!isset($urlHashing)) {
            $this->Flash->error(__('Url not found'));
            return $this->redirect(['action' => 'index']);
        }

        $this->set('urlHashing', $urlHashing);
        $this->set(compact('urlHashing'));
    }


    /**
     * Shortened URL method
     *
     * @param string $hash encoded hash.
     * @return \Cake\Http\Response|null Redirects to the original url.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function shortenedUrl($hash) {
        $urlHashingDetails = $this->UrlHashing->find()
            ->where(['hash' => $hash])
            ->first();

        if (!isset($urlHashingDetails)) {

            return null;
        }

        $isExpired = $this->UrlHashing->isExpiredUrl($urlHashingDetails);
        if ($isExpired) {
            $this->Flash->success(__('The expired url has been deleted.'));
            return null;
        }
        
        return $this->redirect($urlHashingDetails['original_url']);
    }
}
