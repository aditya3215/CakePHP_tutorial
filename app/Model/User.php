<?php

App::uses('AppModel','Model');
// code for password hashing
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel{

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {

            // making a object of BlowFishPasswordHasher to make convert password to hash
            $passwordHasher = new BlowfishPasswordHasher();

            // storing the has value in the password (Passing current password into hash)
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }

    // validation for Login and 
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'User name is Required!'
            )
        ),
        'password'=> array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Password is Required!'
            )
        ),
        'role' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'Role is Required!'
            )
        )
    );
}

?>