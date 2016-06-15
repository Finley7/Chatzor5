<?php
/**
 * Created by PhpStorm.
 * User: finley
 * Date: 15-06-16
 * Time: 01:26
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\Database\Type\DateTimeType;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Mailer\Email;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;

class MailkeysController extends AppController
{
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['activate', 'password']);
    }

    public function activate($token) {
        if(strlen($token) < 1) {
            throw new MethodNotAllowedException("Not allowed");
        }

        $mailkey = $this->Mailkeys->findByToken($token)->first();

        if(is_null($mailkey)) {
            throw new NotFoundException(__("This key is not valid!"));
        }
        if(!$mailkey->activated) {
            if ($mailkey->created->toUnixString() + 1440 < Time::now()->toUnixString()) {
                $mailkey->token = bin2hex(openssl_random_pseudo_bytes(64));
                $mailkey->created = Time::now()->toDateTimeString();

                if($this->Mailkeys->save($mailkey)) {
                    $mail = new Email();
                    $mail
                        ->from(['no-reply@finleyhd.nl' => 'Chatzor'])
                        ->to(h($this->request->data['email']))
                        ->subject(__('Activate your account!'))
                        ->emailFormat('html')
                        ->template('activate')
                        ->viewVars([
                            'username' => 'user',
                            'token' => $mailkey->token
                        ])
                        ->send();
                }

                throw new MethodNotAllowedException(__("This key has expired. A new key has been sent!"));
            } else {
                $this->loadModel('Users');
                $user = $this->Users->get($mailkey->user_id);

                $user->primary_role = 1;
                $mailkey->activated = true;

                if ($this->Users->save($user) && $this->Mailkeys->save($mailkey)) {
                    $this->Flash->success(__("Activation successfull!"));
                    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
                } else {
                    $this->Flash->error(__("An unknown error has occured"));
                }
            }
        }
        else
        {
            throw new MethodNotAllowedException("This key is not valid!");
        }

        $this->set(compact('mailkey'));
    }

    public function password($token) {
        if(strlen($token) < 1) {
            throw new MethodNotAllowedException("Not allowed");
        }

        $mailkey = $this->Mailkeys->findByToken($token)->first();

        $this->loadModel('Users');

        if(is_null($mailkey)) {
            throw new NotFoundException(__("This key is not valid!"));
        }
        if(!$mailkey->activated) {
            if ($mailkey->created->toUnixString() + 1440 < Time::now()->toUnixString()) {
                throw new MethodNotAllowedException(__("This key has expired"));
            } else {
                $new_user = $this->Users->get($mailkey->user_id);

                if ($this->request->is(['patch', 'post', 'put'])) {

                    $new_user = $this->Users->patchEntity($new_user, $this->request->data);

                    if ($this->Users->save($new_user))
                    {
                        $mailkey->activated = 1;

                        if($this->Mailkeys->save($mailkey)) {
                            $this->Flash->success(__('The password has been changed'));
                            return $this->redirect(['action' => 'login']);
                        }

                    } else {
                        $this->Flash->error(__('The password could not be saved. Please, try again.'));
                    }
                }
            }
        }
        else
        {
            throw new MethodNotAllowedException(__("This key is not valid!"));
        }

        $this->set(compact('mailkey', 'new_user'));
    }
}