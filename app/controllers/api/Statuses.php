<?php
class Statuses  extends Controller
{

    public $user;

    use authuser;


    public function __construct()
    {
        $this->user = $this->user();
        // $user_id = $user['data']->id;
        $this->userModel = $this->model('User');
        $this->messageModel = $this->model('Message');
    }
    public function add($var = null)
    {
        # code...
    }
    public function delete($var = null)
    {
        # code...
    }
    public function index($var = null)
    {
        # code...
    }
    public function show($var = null)
    {
        # code...
    }
}
