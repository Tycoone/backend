<?php
class Profile extends Controller
{
    public $user;

    use authuser;
    public function __construct()
    {
        $this->user = $this->user();
        // $user_id = $user['data']->id;
        $this->userModel = $this->model('User');
        $this->messageModel = $this->model('Message');
        header("access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Content-Type: application/json; charset=UTF-8");
    }
    public function index()
    {
        $user = $this->user();
        $data = $this->userModel->getprofile($this->user['data']->id);

        $result = $this->success($data, 200);
        print_r($result);
        die();
    }
    public function show($api, $id)
    {
        $data = $this->userModel->getprofile($id);

        $result = $this->success($data, 200);
        print_r($result);
        die();
    }
}
