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
use Cake\ORM\TableRegistry;

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

    public function search() {
        $searchString = h($this->request->query['search_string']);

        $this->paginate = [
            'limit' => 25,
            'contain' => 'PrimaryRole'
        ];

        if(strlen($searchString) < 1) {
            $this->Flash->error(__('Search string is empty'));
            return $this->redirect(['action' => 'index']);
        }

        $users = $this->Users->findByUsername($searchString);

        $this->set('title', __('Searchresult for {0}', $searchString));
        $this->set('users', $this->paginate($users));
    }

    public function add() {
        $newUser = $newUser = $this->Users->newEntity([
            'associated' => [
                'Roles',
                'Activity',
            ]
        ]);

        $roles = TableRegistry::get('Roles')->find('all');

        if ($this->request->is('post')) {
            $this->request->data['roles']['_ids'] = $this->request->data['role'];;
            $this->request->data['username'] = h($this->request->data['username']);
            $this->request->data['primary_role'] = 1;
            $this->request->data['activity']['action'] = 'new_user';

            $newUser = $this->Users->patchEntity($newUser, $this->request->data, [
                'associated' => [
                    'Roles',
                    'Activities',
                ]
            ]);

            if ($this->Users->save($newUser, ['associated' => ['Roles', 'Activities']])) {

                $this->Flash->success(__('User has been added!'));

                return $this->redirect([
                    'controller' => 'Users',
                    'action' => 'index',
                    'prefix' => 'management'
                ]);

            } else {
                $this->Flash->error(__('Account could not be created!'));
            }
        }

        $this->set('newUser', $newUser);
        $this->set('roles', $roles);
        $this->set('title', __('Add an user'));
    }

    public function edit($id = null) {
        $editUser = $this->Users->findByUsername($id)->contain(['Roles'])->first();

        if(is_null($editUser)) {
            throw new NotFoundException(__('User not found!'));
        }

        $roles = TableRegistry::get('roles')->find('list');

        $hasRoles = [];

        foreach ($editUser->roles as $uRole) {
            $hasRoles[$uRole->id] = $uRole->name;
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $editUser = $this->Users->patchEntity($editUser, $this->request->data);
            if ($this->Users->save($editUser)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('editUser'));
        $this->set(compact('hasRoles'));
        $this->set(compact('roles'));
        $this->set('title', 'Edit user ' . $editUser->username);
        $this->set('_serialize', ['user']);
    }

    public function password($username) {
        $editUser = $this->Users->findByUsername($username)->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $editUser = $this->Users->patchEntity($editUser, $this->request->data);
            if ($this->Users->save($editUser)) {
                $this->Flash->success(__('The password has been changed'));
                return $this->redirect(['action' => 'edit', $editUser->id]);
            } else {
                $this->Flash->error(__('The password could not be saved. Please, try again.'));
            }
        }
        $this->set('title', __('Change password'));
        $this->set(compact('editUser'));
    }

    public function avatarReset($id) {
        $currentUser = $this->Users->get($id);

        if(is_null($currentUser)) {
            throw new NotFoundException("User not found!");
        }

        if($this->request->is(['put', 'patch', 'post'])) {
            $currentUser->avatar = 'default-avatar.png';

            if ($this->Users->save($currentUser)) {
                $this->Flash->success(__('The user has been updated'));
                return $this->redirect(['action' => 'edit', $currentUser->username]);
            } else {
                $this->Flash->error(__('Something went wrong, the user is not updated!'));
                return $this->redirect(['action' => 'edit', $currentUser->username]);
            }
        }
    }
}