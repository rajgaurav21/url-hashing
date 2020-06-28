<?php
namespace App\Model\Table;

use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UrlHashing Model
 *
 * @method \App\Model\Entity\UrlHashing get($primaryKey, $options = [])
 * @method \App\Model\Entity\UrlHashing newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UrlHashing[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UrlHashing|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UrlHashing saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UrlHashing patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UrlHashing[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UrlHashing findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UrlHashingTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('url_hashing');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('hash')
            ->maxLength('hash', 255)
            ->requirePresence('hash', 'create')
            ->notEmptyString('hash');

        $validator
            ->scalar('original_url')
            ->requirePresence('original_url', 'create')
            ->notEmptyString('original_url');

        $validator
            ->dateTime('expiration_date')
            ->allowEmptyString('expiration_date');

        return $validator;
    }

    /**
     * delete the link after its expiration
     *
     * @param $urlHashingDetails urlHashing entity.
     * @return bool
     */
    public function deleteExpiredLink($urlHashingDetails)
    {
        if ($this->delete($urlHashingDetails)) {

            return true;
        }

        return false;
    }

    /**
     * generate 8 character key based on the url
     *
     * @param string $originalUrl original long url.
     * @return $encodedURL string encoded key
     */
    public function generateKey($originalUrl)
    {
        $id = $this->find()->select(['id'])->last();
        if (!isset($id)) {
            $id = 0;
        }
        $hashedURL = md5($originalUrl . $id);
        $encodedURL = substr(base64_encode($hashedURL), 0, 8);

        return $encodedURL;
    }

    /**
     * save the URL after hashing
     *
     * @param string $originalUrl original long url.
     * @param string $key 8 digit encoded key
     * @param datetime|null $expiration_date url expiration date
     * @return bool
     */
    public function saveHashedUrl($originalUrl, $key, $expiration_date=null)
    {

        $urlDetail = $this->newEntity();
        $urlData = [
            'hash' => $key,
            'original_url' => $originalUrl,
        ];  

        if (isset($expiration_date)) {
            $urlData['expiration_date'] = $expiration_date;
        }

        $urlDetail = $this->patchEntity($urlDetail, $urlData);
        if (!$this->save($urlDetail)) {
            Log::error("[UrlHashingTable/saveHashedUrl] Error in saving the data");

            return false;
        }

        return true;
    }

    /**
     * check if the url is expired or not, if expired delete the url.
     *
     * @param entity $urlHashingDetails hashed url details.
     * @return bool
     */
    public function isExpiredUrl($urlHashingDetails)
    {
        if (isset($urlHashingDetails['expiration_date']) && !empty($urlHashingDetails['expiration_date'])) {
            $currentDate = date_format(Time::now(),DATE_W3C);
            $expirationDate = date_format($urlHashingDetails['expiration_date'],DATE_W3C);
            if ($currentDate > $expirationDate) {
                $isDeleted = $this->deleteExpiredLink($urlHashingDetails);
                if (!$isDeleted) {
                    Log::error("[UrlHashingTable/isExpiredUrl] Error in deleting the expired url");
                }

                return true;
            }
        }

        return false;
    }

}
