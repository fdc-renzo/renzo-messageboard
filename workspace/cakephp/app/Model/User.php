<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class User extends AppModel
{
    // Validation rules
    public $validate = [
        'name' => [
            'required' => [
                'rule' => 'notBlank',
                'message' => 'A name is required'
            ],
            'minLength' => [
                'rule' => ['minLength', 5],
                'message' => 'Name must be at least 5 characters long',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 20],
                'message' => 'Name must be at most 20 characters long',
            ]
        ],
        // 'birthdate' => [
        //     'required' => [
        //         'rule' => 'notBlank',
        //         'message' => 'A birthdate is required'
        //     ]
        // ],
        // 'hubby' => [
        //     'required' => [
        //         'rule' => 'notBlank',
        //         'message' => 'A hubby is required'
        //     ]
        // ],
        'email' => [
            'required' => [
                'rule' => 'notBlank',
                'message' => 'An email is required'
            ],
            'unique' => [
                'rule' => 'isUnique',
                'message' => 'This email is already registered'
            ],
            'email' => [
                'rule' => 'email',
                'message' => 'Please enter a valid email address'
            ]
        ],
        'password' => [
            'required' => [
                'rule' => 'notBlank',
                'message' => 'A password is required'
            ],
            'minLength' => [
                'rule' => ['minLength', 6],
                'message' => 'Password must be at least 6 characters long'
            ]
        ],
        'password_confirm' => [
            'match' => [
                'rule' => 'matchPasswords',
                'message' => 'Passwords do not match'
            ]
        ]
    ];

    public function matchPasswords($check)
    {
        // Get the password and password_confirm values
        $password = $this->data[$this->alias]['password'];
        $passwordConfirm = $check['password_confirm']; // This comes from the field being validated

        return $password === $passwordConfirm;
    }

    public function validateImageFormat($check)
    {
        $file = $check['profile'];
        $allowedTypes = ['image/jpeg', 'image/gif', 'image/png'];

        if (in_array($file['type'], $allowedTypes)) {
            return true;
        }

        return false; // Invalid format
    }

    private function getUserIp()
    {
        // Retrieve the user's IP address from the request
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // Check if the IP is from shared internet
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Check if the IP is passed from a proxy
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        // Return the remote address
        return $_SERVER['REMOTE_ADDR'];
    }

    // Hash password before saving this will invoke upon saving in controller
    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password']) && !empty($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }

        $ipAddress = $this->getUserIp();

        if (isset($this->data[$this->alias]['id'])) {
            $this->data[$this->alias]['modified'] = date('Y-m-d H:i:s');
            $this->data[$this->alias]['modified_ip'] = $ipAddress;
        } else {
            $this->data[$this->alias]['created'] = date('Y-m-d H:i:s');
            $this->data[$this->alias]['created_ip'] = $ipAddress;

            $this->data[$this->alias]['modified_ip'] = $ipAddress;
            $this->data[$this->alias]['modified'] = date('Y-m-d H:i:s');
        }

        return true;
    }

    public function updateLastLoginTime($userId)
    {
        $this->id = $userId;

        return $this->save(
            ['last_login_time' => date('Y-m-d H:i:s')],
            ['fieldList' => ['last_login_time']]
        );
    }
}
