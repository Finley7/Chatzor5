<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * User Entity.
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property \Cake\I18n\Time $created
 * @property int $primary_role
 * @property \App\Model\Entity\Role[] $roles
 */
class User extends Entity
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
        '*' => true,
        'id' => false,
    ];

    /**
     * Fields that are excluded from JSON an array versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    /**
     * @param $permissionName
     * @return bool
     */
    public function hasPermission($permissionName)
    {
        return in_array($permissionName, $this->_getPermissions());
    }
    /**
     * @return array
     */
    public function _getPermissions()
    {
        $_permissions = [];
        foreach ($this->_getRoles() as $role) {
            foreach($role->permissions as $permission) {
                $_permissions[] = $permission->name;
            }
        }
        return array_unique($_permissions);
    }
    /**
     * @return array
     */
    private function _getRoles()
    {
        $permissionsRegistry = TableRegistry::get('RolesUsers');
        $userRoles = $permissionsRegistry->findByUserId($this->id)->contain(['Roles' => ['Permissions']]);
        $roles = [];
        foreach($userRoles as $userRole)
        {
            $roles[] = $userRole->role;
        }
        return $roles;
    }
    /**
     * @param $password
     * @return mixed
     */
    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }
}
