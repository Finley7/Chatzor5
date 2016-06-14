<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

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
                'Activity',
                'Mailkeys'
            ]
        ]);
        
        if ($this->request->is('post')) {
            $this->request->data['roles']['_ids'] = [1,3];
            $this->request->data['username'] = h($this->request->data['username']);
            $this->request->data['primary_role'] = 3;
            $this->request->data['activity']['action'] = 'new_user';

            $this->request->data['mailkeys'][1] = [
                'token' => bin2hex(openssl_random_pseudo_bytes(64)),
                'type' => 'activation',
                'activated' => 0
            ];

            $newUser = $this->Users->patchEntity($newUser, $this->request->data, [
                'associated' => [
                    'Roles',
                    'Activities',
                    'Mailkeys'
                ]
            ]);


            if ($this->Users->save($newUser, [
                'associated' => [
                    'Roles',
                    'Activities',
                    'Mailkeys'
                ]
            ])
            ) {
                $this->Flash->success(__('Your account has been created! Check your e-mail!'));

                $mail = new Email();
                $mail
                    ->from(['no-reply@finleyhd.nl' => 'Chatzor'])
                    ->to(h($this->request->data['email']))
                    ->subject(__('Activiate your account!'))
                    ->emailFormat('html')
                    ->template('activate')
                    ->viewVars(['token' => $this->request->data['mailkeys'][1]['token']])
                    ->send();

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
