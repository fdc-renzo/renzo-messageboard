<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    // this will tell cakek to support php files for the view for rendering
    public $ext = '.php';

    // include the Post Model
    public $uses = array(
        'Post'
    );

    // - include components
    public $components = array(
        'Flash',
        'Auth' => array(
            // if the user is logged in
            'loginRedirect' => array(
                'controller' => 'users',
                'action' => 'index'
            ),

            // if teh user is not logged in AND accesses a forbidden action,
            'logoutRedirect' => array(
                'controller' => 'pages',
                'action' => 'display',
                'home'
            ),
            'authenticate' => array(
                'Form' => array(
                    // 'passwordHasher' => 'Blowfish',
                    // if you want to customize the fields for logging in
                    // 'fields'=>array('username'=>'email','password'=>'password')
                )
            )
        )
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        // Disable caching
        $this->response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->response->header('Pragma', 'no-cache');
        $this->response->header('Expires', '0');

        // Check auth
        $this->checkAuth();

        // Allow public access to the Welcome controller's index action
        $this->Auth->allow('index');

        //user available to views
        $this->set('currentUser', $this->Auth->user());

        //Set if the user is authenticated.
        if ($this->Auth->user()) {
            $this->_setUserView();
        }
    }

    public function checkAuth()
    {
        // Define the actions that require authentication
        $protectedActions = ['main', 'thankyou', 'profile', 'profileView'];

        // Check if the current action is in the protected actions
        if (in_array($this->action, $protectedActions) && !$this->Auth->user()) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    private function _setUserView()
    {
        $firstName = explode(' ', $this->Auth->user('name'))[0] ?? 'Unknown';

        $userData = [
            'title' => 'Message Board',
            'firstName' => $firstName,
        ];

        $this->set($userData);
    }

   
}
