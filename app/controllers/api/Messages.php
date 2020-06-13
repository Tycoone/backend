<?php
class Messages  extends Controller
{


    use authuser;


    public function __construct()
    {
        $this->userModel = $this->model('User');
    }
    public function index()
    {
        // require_once '../vendor/firebase/php-jwt/src/JWT.php';
        print_r($this->user());
    }
}
