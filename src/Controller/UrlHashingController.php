<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;
use App\Exception\CustomException;
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
            $id = $this->UrlHashing->find()->select(['id'])->last();
            if (!isset($id)) {
                $id = 0;
            }
            log::debug($this->request);
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
            $this->Flash->success(__('Shortened URL: ' . 'http://localhost/news-bytes/urlHashing/view/' . $encodedURL));
            return $this->redirect(['action' => 'index']);
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
    public function view($id = null)
    {
        $urlHashing = $this->UrlHashing->get($id, [
            'contain' => [],
        ]);

        $this->set('urlHashing', $urlHashing);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $urlHashing = $this->UrlHashing->newEntity();
        if ($this->request->is('post')) {
            $urlHashing = $this->UrlHashing->patchEntity($urlHashing, $this->request->getData());
            if ($this->UrlHashing->save($urlHashing)) {
                $this->Flash->success(__('The url hashing has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The url hashing could not be saved. Please, try again.'));
        }
        $this->set(compact('urlHashing'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Url Hashing id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $urlHashing = $this->UrlHashing->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $urlHashing = $this->UrlHashing->patchEntity($urlHashing, $this->request->getData());
            if ($this->UrlHashing->save($urlHashing)) {
                $this->Flash->success(__('The url hashing has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The url hashing could not be saved. Please, try again.'));
        }
        $this->set(compact('urlHashing'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Url Hashing id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $urlHashing = $this->UrlHashing->get($id);
        if ($this->UrlHashing->delete($urlHashing)) {
            $this->Flash->success(__('The url hashing has been deleted.'));
        } else {
            $this->Flash->error(__('The url hashing could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Shortened URL method
     *
     * @param string $hash encoded hash.
     * @return \Cake\Http\Response|null Redirects to the original url.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function shortenedUrl($hash) {
        $urlHasingDetails = $this->UrlHashing->find()
            ->where(['hash' => $hash])
            ->first();
        
        return $this->redirect($urlHasingDetails['original_url']);
    }
}
