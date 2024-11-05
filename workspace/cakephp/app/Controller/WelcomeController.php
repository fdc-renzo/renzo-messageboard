<?php
class WelcomeController extends AppController
{
    
    public function index()
    {
        $this->set('message', 'Welcome to our application! We are glad to have you here.');
        $this->layout = 'guest'; 
    }
}
