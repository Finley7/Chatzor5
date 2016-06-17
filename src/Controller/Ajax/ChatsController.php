<?php
/**
 * Created by PhpStorm.
 * User: finley
 * Date: 14-06-16
 * Time: 01:07
 */

namespace App\Controller\Ajax;


use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

class ChatsController extends AppController
{
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->loadComponent('RequestHandler');
    }

    public function index() {
        if($this->request->isAjax()) {

            $this->loadComponent('Ubb');

            if(!isset($this->request->query['after'])) {
                $this->request->query['after'] = 0;
            }
            
            $chatsFinder = $this->Chats->find('all')
                ->where([
                    'deleted' => 0,
                    'OR' => [
                        'OR' => [
                            'user_id' => $this->Auth->user('id'),
                            'whisper_to is' => null,
                            'whisper_to' => $this->Auth->user('id')
                        ]
                    ]
                ])
                ->contain([
                    'Users' => [
                        'PrimaryRole'
                    ],
                    'Whispers' => [
                        'PrimaryRole'
                    ]
                ])
                ->order(['Chats.created' => 'DESC'])
                ->limit(10);
            
            $chats = [];
            $lastId = 0;
            $i = 0;
            
            foreach($chatsFinder->all() as $chat) {
                $chats[$i]['user']['username'] = h($chat->user->username);
                $chats[$i]['user']['primary_role'] = h($chat->user->primary_role->name);

                
                $string = h($chat->message);
                
                foreach(Configure::read("Blocked.words") as $key => $value) {
                    $string = str_ireplace($key, "[{$value}]", $string);
                }
                
                if(!is_null($chat->whisper_to)) {
                    if($chat->whisper_to == $this->Auth->user('id')) {
                        $string = "<b>". __("Whisper") .": </b>" . $string;
                    }
                    else
                    {
                        $string = "<b>" . __("Whisper to {0}", h($chat->whisper->username)) .": </b>" . $string;
                    }
                }
                
                $chats[$i]['message']['id'] = h($chat->id);
                $chats[$i]['message']['content'] = $this->Ubb->parse($string);
                $chats[$i]['message']['created'] = h($chat->created->nice());
                $i++;
            }

            if($lastId == 0) {
                $chatsFinder = $this->Chats->find('all')
                    ->where([
                        'deleted' => 0
                    ])
                    ->order([
                        'created' => 'DESC'
                    ]);

                $lastMessage = $chatsFinder->first();
                $lastId = $lastMessage->id;
            }

            if(count($chats) > 0) {
                $response['chats'] = $chats;
            }

            $response['last_id'] = $lastId;

            $this->set(compact('response'));
            $this->set('_serialize', ['response']);


        }
        else
        {
            throw new MethodNotAllowedException("AJAX request only");
        }
    }

    public function shout() {
        $this->loadComponent('Ubb');
        $this->loadModel('Users');
        if($this->request->isAjax()) {
            if($this->request->is('post')) {
                $lastChat = $this->Chats->findByUserId($this->Auth->user('id'))->select('created')->last();

                if(strlen($this->request->data['message']) < 1) {
                    throw new MethodNotAllowedException();
                }
                else {

                    if (!is_null($lastChat) && $lastChat->created->toUnixString() + 5 > Time::now()->toUnixString()) {
                        $response = ['status' => 'error', 'message' => __('Wait 5 seconds')];
                    } else {
                        $chat = $this->Chats->newEntity();

                        $chat->user_id = $this->Auth->user('id');
                        $chat->message = $this->request->data['message'];

                        $message_explode = explode(' ', $this->request->data['message']);

                        $private = false;

                        if ($message_explode[0] == '/pvt') {

                            if (strlen($message_explode[1]) < 1) {
                                $response = ['status' => 'error', 'message' => __('Could not save')];
                            }

                            $private = true;
                            $private_user = $this->Users->findByUsername(h($message_explode[1]))->select(['id', 'username'])->first();
                            if (!is_null($private_user)) {
                                $chat->whisper_to = $private_user->id;

                                $chat->message = str_replace('/pvt ' . $message_explode[1], '', $chat->message);
                            }
                        }

                        if (strlen($this->request->data['message']) < 1 || strlen($this->request->data['message']) > 150) {
                            $response = ['status' => 'error', 'message' => __('Invalid shout')];
                        } else {
                            if ($this->Chats->save($chat)) {
                                $activityRegistry = TableRegistry::get('Activities');
                                $activity = $activityRegistry->findByUserId($this->Auth->user('id'))->first();

                                $activity->action = 'new_shout';

                                if ($activityRegistry->touch($activity, 'Activities.updated') && $activityRegistry->save($activity)) {
                                    $response = ['status' => 'ok', 'id' => $chat->id];
                                } else {
                                    $response = ['status' => 'error', 'error' => __('Message could not be updated')];
                                }
                            } else {
                                $response = ['status' => 'error', 'message' => __('Could not save')];
                            }
                        }
                    }
                }
            }
            else
            {
                $response = ['status' => 'error', 'message' => __('No post request')];
            }


            $this->set(compact('response'));
            $this->set('_serialize', ['response']);
        }
        else
        {
            throw new MethodNotAllowedException("AJAX request only!");
        }
    }

    public function view($id) {
        if($this->request->isAjax()) {
            $this->loadComponent('Ubb');

            $chatQuery = $this->Chats->findById($id)
            ->where([
                    'deleted' => 0,
                    'OR' => [
                        'OR' => [
                            'user_id' => $this->Auth->user('id'),
                            'whisper_to is' => null,
                            'whisper_to' => $this->Auth->user('id')
                        ]
                    ]
                ])
                ->contain([
                    'Users' => [
                        'PrimaryRole'
                    ],
                    'Whispers' => [
                        'PrimaryRole'
                    ]
                ])
                ->order(['Chats.created' => 'DESC'])
                ->first();

            $chat['user']['username'] = h($chatQuery->user->username);
            $chat['user']['primary_role'] = h($chatQuery->user->primary_role->name);


            $string = h($chatQuery->message);

            foreach(Configure::read("Blocked.words") as $key => $value) {
                $string = str_ireplace($key, $value, $string);
            }

            if(!is_null($chatQuery->whisper_to)) {
                if($chatQuery->whisper_to == $this->Auth->user('id')) {
                    $string = "<b>Whisper: </b>" . $string;
                }
                else
                {
                    $string = "<b>Whisper to " . h($chatQuery->whisper->username) .": </b>" . $string;
                }
            }

            $chat['message']['id'] = h($chatQuery->id);
            $chat['message']['content'] = $this->Ubb->parse($string);
            $chat['message']['created'] = h($chatQuery->created->nice());

            if(is_null($chatQuery)) {
                throw new NotFoundException("Shout not found!");
            }
            else
            {
                $response['chat'] = $chat;
            }

            $this->set(compact('response'));
            $this->set('_serialize', ['response']);
        }
        else
        {
            throw new MethodNotAllowedException("AJAX request only");
        }
    }
}