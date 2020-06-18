<?php
class Connections extends Controller
{
    public $user;

    use authuser;
    public function __construct()
    {
        $this->user = $this->user();
        $this->userModel = $this->model('User');
        $this->connectionModel = $this->model('Connection');
        header("access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Content-Type: application/json; charset=UTF-8");
    }
    public function send($api, $user)
    {
        if (is_numeric($user)) {
            $user_id = $this->user['data']->id;
            $data = [
                'rid' => $user,
                'user_id' => $user_id
            ];
            $check = $this->connectionModel->check($data);
            if ($check) {
                $this->renderFullError('Already a Connection', 500);
                die;
            } else {
                $response = $this->connectionModel->sendrequest($data);
            }
            if ($response) {
                $this->success('Connection Request has been sent');
                die;
            } else {
                $this->renderFullError('Something went wrong', 500);
                die;
            }
        } else {
            # code...
        }
    }
    public function accept($api, $user)
    {
        if (is_numeric($user)) {
            $user_id = $this->user['data']->id;
            $data = [
                'rid' => $user,
                'user_id' => $user_id
            ];
            $check = $this->connectionModel->check($data);
            if ($check) {
                die('Already a Connection');
            } else {
                $response = $this->connectionModel->acceptrequest($data);
            }
            if ($response) {
                die('Connection has been established');
            } else {
                die('Something went wrong');
            }
        } else {
            # code...
        }
    }
    public function deleterequest($api, $user)
    {
        if (is_numeric($user)) {
            $user_id = $this->user['data']->id;
            $data = [
                'rid' => $user,
                'user_id' => $user_id
            ];
            $response = $this->connectionModel->deleterequest($data);
            if ($response) {
                die('delete successful');
            } else {
                die('Something went wrong');
            }
        } else {
            # code...
        }
    }
    public function block($api, $user)
    {
        if (is_numeric($user)) {
            $user_id = $this->user['data']->id;
            $data = [
                'rid' => $user,
                'user_id' => $user_id
            ];
            $response = $this->connectionModel->blockconnection($data);
            if ($response) {
                die('block  successful');
            } else {
                die('Something went wrong');
            }
        } else {
            # code...
        }
    }
}
