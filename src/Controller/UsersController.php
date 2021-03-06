<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Mailer\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event) {
        $this->Auth->allow(['add', 'logout', 'forgot']);

        if ($this->request->action == 'login' || $this->request->action == 'add' || $this->request->action == 'forgot') {
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
        $this->loadModel('Activities');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                $activity = $this->Activities->findByUserId($this->Auth->user('id'))->first();
                $activity->action = 'logged_in';

                if($this->Activities->touch($activity, 'Activities.updated') && $this->Activities->save($activity)) {
                    $this->Flash->success(__("Welcome back, {0}", $this->Auth->user('username')));
                    return $this->redirect('/');
                }
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
            $this->request->data['primary_role'] = Configure::read('App.mailActivation') ? 3 : 1;
            $this->request->data['activity']['action'] = 'new_user';

            $this->request->data['mailkeys'][1] = [
                'token' => bin2hex(openssl_random_pseudo_bytes(64)),
                'type' => 'activation',
                'activated' => Configure::read('App.mailActivation') ? 0 : 1
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


                if(Configure::read('App.mailActivation')) {
                    $mail = new Email();
                    $mail
                        ->from(['no-reply@finleyhd.nl' => 'Chatzor'])
                        ->to(h($this->request->data['email']))
                        ->subject(__('Activate your account!'))
                        ->emailFormat('html')
                        ->template('activate')
                        ->viewVars([
                            'username' => h($this->request->data['username']),
                            'token' => $this->request->data['mailkeys'][1]['token']
                        ])
                        ->send();

                    $this->Flash->success(__('Your account has been created! Check your e-mail!'));
                }
                else
                {
                    $this->Flash->success(__('Your account has been created! You can login now!'));
                }

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

    public function forgot(){
        $this->loadModel('Mailkeys');

        $mailkey = $this->Mailkeys->newEntity();
        
        if($this->request->is('post')) {
            if(strlen($this->request->data['email']) < 1) {
                $this->Flash->error(__("E-mail invalid"));
            }
            else
            {
                $new_user = $this->Users->findByEmail(h($this->request->data['email']))->first();

                if(is_null($new_user)) {
                    $this->Flash->success(__("If there is an account we will send a new verifaction e-mail!"));
                    return $this->redirect(['action' => 'login']);
                }
                else
                {
                    $mailkey->user_id = $new_user->id;
                    $mailkey->token = bin2hex(openssl_random_pseudo_bytes(64));
                    $mailkey->type = 'password_reset';
                    
                    if($this->Mailkeys->save($mailkey)) {
                        $mail = new Email();
                        $mail
                            ->from(['no-reply@finleyhd.nl' => 'Chatzor'])
                            ->to(h($this->request->data['email']))
                            ->subject(__('Request for password change!'))
                            ->emailFormat('html')
                            ->template('password')
                            ->viewVars([
                                'username' => h($new_user->username),
                                'token' => $mailkey->token
                            ])
                            ->send();

                        $this->Flash->success(__("If there is an account we will send a new verifaction e-mail!"));
                        return $this->redirect(['action' => 'login']);
                    }
                }
            }
        }
    }

    public function settings() {

        $editUser = $this->Users->get($this->Auth->user('id'));
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if($this->request->is(['patch', 'post', 'put'])){
            if(!@getimagesize($this->request->data['avatar']['tmp_name'])) {
                $this->Flash->error(__('Je hebt geen geldige avatar geselecteerd'));
                return $this->redirect(['view' => 'avatar']);
            }
            if(!in_array($this->request->data['avatar']['type'], $allowed_types)) {
                $this->Flash->error(__('Je hebt geen geldig formaat geupload'));
                return $this->redirect(['view' => 'avatar']);
            }
            if($this->request->data['avatar']['size'] > 2000000) {
                $this->Flash->error(__('Je hebt te grote avatar geupload!'));
                return $this->redirect(['view' => 'avatar']);
            }
            $ext = explode('/', $this->request->data['avatar']['type']);
            $avatar_url = 'avatar_' . Text::uuid() . '_' . $editUser->username . '.' . $ext[1];
            $editUser->avatar = $avatar_url;
            if($this->Users->save($editUser))
            {
                $image_info = file_get_contents($this->request->data['avatar']['tmp_name']);
                $avatar = new File('img/uploads/avatars/' . $avatar_url, true);
                $avatar->append($image_info);
                $avatar->create();
                $this->Auth->setUser($editUser->toArray());
                $this->Flash->success(__('Avatar geupload!'));
                return $this->redirect(['action' => 'settings']);
            }
            else
            {
                $this->Flash->error(__('Er iets fout gegaan tijdens het opslaan!'));
            }
        }
        $this->set('editUser', $editUser);
        $this->set('page_parent', 'settings');
    }

}
