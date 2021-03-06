<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        if($this->request->action == 'landing' && !is_null($this->Auth->user())) {
            return $this->redirect(['controller' => 'pages', 'action' => 'index']);
        }
        $this->Auth->allow(['landing']);
    }

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function landing()
    {
        $this->set('title', __("Welcome to Chatzor!"));
    }

    public function index() 
    {
        $this->loadModel('Users');
        $this->loadModel('Chats');

        $users = $this->Users->find('all')->select('id')->count();
        $chats = $this->Chats->find('all')->select('id')->count();

        $this->set(compact('users', 'chats'));

        $this->set('title', __('Chatbox'));
    }


    public function archive()
    {
        $this->paginate = [
            'limit' => 15,
            'contain' => [
                'Users' => ['PrimaryRole']
            ]
        ];

        $this->loadModel('Chats');
        $this->loadComponent('Ubb');

        $chats = $this->Chats->find('all')
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
            ->order(['Chats.created' => 'DESC']);

        $this->set('chats', $this->paginate($chats));
    }
}
