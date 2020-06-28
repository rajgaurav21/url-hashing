<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;
use App\Exception\CustomException;
use Cake\Utility\Security;
use Cake\I18n\Time;

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
            $id = $this->UrlHashing->find()->select(['id'])->last();
            if (!isset($id)) {
                $id = 0;
            }
            if (empty($originalUrlDetails['original_url'])) {
                $this->Flash->error(__('Invalid Data. Please, try again.'));
                return $this->redirect(['action' => 'index']);
            }
            $hashedURL = md5($originalUrlDetails['original_url'] . $id);
            $encodedURL = substr(base64_encode($hashedURL), 0, 8);  

            $urlDetail = $this->UrlHashing->newEntity();    

            $urlData = [
                'hash' => $encodedURL,
                'original_url' => $originalUrlDetails['original_url'],
            ];  

            if (isset($originalUrlDetails['expiration_date'])) {
                $urlData['expiration_date'] = $originalUrlDetails['expiration_date'];
            }   

            $urlDetail = $this->UrlHashing->patchEntity($urlDetail, $urlData);

            if (!$this->UrlHashing->save($urlDetail)) {
                $this->Flash->error(__('The url detail could not be saved. Please, try again.'));
            }
            
            $this->Flash->success(__('Shortened URL: ' . SERVER_DOMAIN . 'shortenedUrl/' . $encodedURL));
            return $this->redirect(['action' => 'view', $encodedURL]);
            //$urlDetail = $this->UrlHashing->newEntity();
        }
        $this->set(compact('urlDetail'));
    }

    /**
     * View method
     *
     * @param string|null $id Url Hashing id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($hash)
    {
        $urlHashing = $this->UrlHashing->find()
            ->where(['hash' => $hash])
            ->first();

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
        if (isset($urlHashingDetails['expiration_date']) && !empty($urlHashingDetails['expiration_date'])) {
            $currentDate = date_format(Time::now(),DATE_W3C);
            $expirationDate = date_format($urlHashingDetails['expiration_date'],DATE_W3C);
            if ($currentDate > $expirationDate) {
                if ($this->UrlHashing->deleteExpiredLink($urlHashingDetails)) {
                    $this->Flash->success(__('The url has been deleted.'));
                } else {
                    $this->Flash->error(__('The url has not been deleted. Please try again.'));
                }
                return null;
            }
        }
        
        return $this->redirect($urlHashingDetails['original_url']);
    }
}
