<?php
/**
 * Created by PhpStorm.
 * User: finley
 * Date: 18-06-16
 * Time: 01:11
 */

namespace App\Controller\Management;


use App\Controller\AppController;
use Cake\Log\Log;
use Cake\Network\Exception\NotFoundException;

class ChatsController extends AppController
{
    public function index()
    {
        $this->paginate = [
            'limit' => 15,
            'contain' => [
                'Users' => ['PrimaryRole']
            ]
        ];

        $this->loadModel('Chats');

        $chats = $this->Chats->find('all')
            ->contain([
                'Users' => [
                    'PrimaryRole'
                ],
                'Whispers' => [
                    'PrimaryRole'
                ]
            ])
            ->order(['Chats.created' => 'DESC']);

        $this->set('chats', $this->paginate($chats));
    }

    public function delete($id)
    {
        $this->request->allowMethod(['post']);

        $chat = $this->Chats->get($id);

        if(is_null($chat)) {
            throw new NotFoundException(__("Chat not found"));
        }

        if($this->request->is(['post', 'patch', 'put'])) {
            $chat->deleted = 1;

            if($this->Chats->save($chat)) {
                $this->Flash->success(__("Chat #{0} has been deleted!", $chat->id));
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error(__("Chat could not be deleted"));
            }
        }
    }
}