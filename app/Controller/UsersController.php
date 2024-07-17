<?php

App::uses('AppController', 'Controller');

class UsersController extends AppController
{

    // this is part of controller lifecycle.
    // It is called before all other action methods.
    public function beforeFilter()
    {
        parent::beforeFilter();
        // this line states that add method does not require any authentication
        $this->Auth->allow('add');
    }

    // this index method is used to fetch data from DB
    public function index()
    {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    // login function
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }
    
    // logout function
    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

}
