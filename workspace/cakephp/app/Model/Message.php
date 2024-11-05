<?php
App::uses('Model', 'Model');

class Message extends AppModel
{
    public $useTable = 'messages';

    public $validate = [
        'message' => [
            'rule' => 'notBlank',
            'message' => 'Message cannot be empty.'
        ],
    ];

    public function beforeSave($options = array())
    {
        if (empty($this->data[$this->alias]['id'])) {
            if (empty($this->data[$this->alias]['created_at'])) {
                $this->data[$this->alias]['created_at'] = date('Y-m-d H:i:s');
            }
        }

     
        $this->data[$this->alias]['updated_at'] = date('Y-m-d H:i:s');

        return true;
    }
}
