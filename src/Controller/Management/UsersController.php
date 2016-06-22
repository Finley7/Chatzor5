<?php
/**
 * Created by PhpStorm.
 * User: finley
 * Date: 22-06-16
 * Time: 16:46
 */

namespace App\Controller\Management;


use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;

class UsersController extends AppController
{
    public function index()
    {
        $this->paginate = [
            'limit' => 25,
            'contain' => [
                'PrimaryRole'
            ]
        ];

        $this->set('users', $this->paginate($this->Users->find('all')->order(['created' => 'DESC'])));
    }

    public function block($id = null){
        $currentUser = $this->Users->get($id);

        if(is_null($currentUser)) {
            throw new NotFoundException("User not found!");
        }

        if($this->request->is(['put', 'patch', 'post'])) {
            if ($currentUser->primary_role == Configure::read('Roles.banRole')) {
                $currentUser->primary_role = Configure::read('Roles.userRole');
            } else {
                $currentUser->primary_role = Configure::read('Roles.banRole');
            }

            if ($this->Users->save($currentUser)) {
                $this->Flash->success(__('The user has been updated'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Something went wrong, the user is not updated!'));
                return $this->redirect(['action' => 'index']);
            }
        }
    }
    
    public function activate($id = null) {
        $currentUser = $this->Users->get($id);

        if(is_null($currentUser)) {
            throw new NotFoundException("User not found!");
        }

        if($this->request->is(['patch', 'post', 'put'])) {
            if($currentUser->primary_role != Configure::read('Roles.activationRole')) {
                $this->Flash->error(__('{0} has already been activated', $currentUser->username));
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $currentUser->primary_role = Configure::read('Roles.userRole');

                if($this->Users->save($currentUser))
                {
                    $this->Flash->success(__('{0}\'s account has been activated!', $currentUser->username));
                    return $this->redirect(['action' => 'index']);
                }
                else
                {
                    $this->Flash->error(__('Something went wrong, the user is not updated!'));
                    return $this->redirect(['action' => 'index']);
                }
            }
        }
    }
}