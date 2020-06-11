<?php

class adddriver extends Mail
{
    private $name;
    private $password;
    private $email;
    private $url;
    public function __construct()
    {
        $this->name = $_SESSION['user_name'];
        $this->email = $_SESSION['email'];
        // $this->password = $password;

        // if ($func == 'view') {
        //     $this->view();
        // } elseif ($func == 'send') {
        //     $this->send();
        // }
    }
    public function view()
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'courier_name' => $this->email,
            'url' => $this->url
        ];
        $this->viewhtml($data, 'adddriver.mail');
    }
    public function send()
    {
        $data = [
            'name' => $this->name,
            'password' => $this->password,
            'email' => $this->email
        ];
        $this->sendhtml($data, 'adddriver.mail');
    }
}
