<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UrlHashing Entity
 *
 * @property int $id
 * @property string $hash
 * @property string $original_url
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $expiration_date
 */
class UrlHashing extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'hash' => true,
        'original_url' => true,
        'created' => true,
        'expiration_date' => true,
    ];
}
