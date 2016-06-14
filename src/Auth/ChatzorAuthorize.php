<?php
namespace App\Auth;
use Cake\Auth\BaseAuthorize;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;

class ChatzorAuthorize extends BaseAuthorize
{
    public function authorize($user, Request $request)
    {
        //Create permission name from the prefix (Optional), controller and action
        $permissionName = strtolower((isset($request->prefix) ? strtolower($request->prefix)."_" : null ).strtolower($request->controller)."_".$request->action);
        //Check if the user is autorized to see this controller/action
        if($this->_isAllowed($user, $permissionName) == true)
        {
            return true;
        }
        return false;
    }
    /*
        Check if the current Authenticated user is authorized to make this request based on the permission name and it's permissions
    */
    protected function _isAllowed($user, $permissionName)
    {
        //Fetch the permissions from the database
        $userPermissions = [];
        $userRegistry = TableRegistry::get('Users');
        $userQuery = $userRegistry->findById($user['id'])->contain([
            'Roles' => [
                'Permissions'
            ]
        ]);
        $userObject = $userQuery->first();
        //create array of deduped permissions
        foreach($userObject->roles as $role)
        {
            foreach($role->permissions as $permission)
            {
                $userPermissions[] = $permission->name;
            }
        }
        //Dedup permissions
        array_unique($userPermissions);
        //Check if user has permission
        if(in_array($permissionName,$userPermissions) == true)
        {
            return true;
        }
        return false;
    }
}