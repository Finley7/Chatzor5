<?php
/**
 * Created by PhpStorm.
 * User: finley
 * Date: 15-06-16
 * Time: 01:26
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;

class MailkeysController extends AppController
{
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['activate']);
    }

    public function activate($token) {
        $mailkey = $this->Mailkeys->findByToken($token)->first();

        if(is_null($mailkey)) {
            throw new NotFoundException(__("Key not found!"));
        }
        if(!$mailkey->activated) {
            if ($mailkey->created->toUnixString() + 1440 < Time::now()->toUnixString()) {
                throw new MethodNotAllowedException(__("This key has expired"));
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
}