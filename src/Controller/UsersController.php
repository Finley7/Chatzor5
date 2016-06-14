<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event) {
        $this->Auth->allow(['add', 'logout', 'view']);

        if ($this->request->action == 'login' || $this->request->action == 'add') {
            if ($this->Auth->user()) {
                $this->Flash->error(__("Je bent al ingelogd!"));
                return $this->redirect([
                    'controller' => 'Pages',
                    'action' => 'landing'
                ]);
            }
        }
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect('/');
            }
            $this->Flash->error(__('Invalid username or password!'));
        }

        $this->set('title', __("Log in with your account!"));
    }

    public function logout()
    {
        if($this->request->is('post')) {
            if($this->Auth->logout())
            {
                $this->Flash->success(__('Je bent succesvol uitgelogd!'));
                return $this->redirect('/');
            }
        }
        else
        {
            throw new NotFoundException();
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $newUser = $this->Users->newEntity([
            'associated' => [
                'Roles',
            ]
        ]);
        
        if ($this->request->is('post')) {
            $this->request->data['roles']['_ids'] = [1];
            $this->request->data['username'] = h($this->request->data['username']);
            $this->request->data['primary_role'] = 1;

            $newUser = $this->Users->patchEntity($newUser, $this->request->data, [
                'associated' => [
                    'Roles',
                ]
            ]);
            if ($this->Users->save($newUser, [
                'associated' => [
                    'Roles',
                ]
            ])
            ) {
                $this->Flash->success(__('Your account has been created! You can login now!'));
                return $this->redirect([
                    'controller' => 'users',
                    'action' => 'login',
                    'prefix' => false
                ]);
            } else {
                $this->Flash->error(__('Something went wrong while registering your account, please try again!'));
            }
        }
        $this->set('title', __('Create your account!'));
        $this->set(compact('newUser'));
        $this->set('_serialize', ['newUser']);
    }

}
