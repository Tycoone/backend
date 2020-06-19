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
        $this->statusModel = $this->model('Status');
    }
    public function add($var = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $this->user['data']->id;
            $img = null;
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if (isset($_FILES["file"]["name"])) {
                $img = $this->uploadfile();
            }
            $data = [
                'sender_id' => trim($user_id),
                'type' => trim($_POST['type']),
                'file' => $img,
                'message' => trim($_POST['caption']),
                'status' => 'sent',
                'views' => 0
            ];

            $err = [];

            //Validate Email
            if (empty($data['sender_id']) || is_null($data['sender_id']) || $data['sender_id'] = '') {
                $err['user'] = 'You are not logged in';
            }


            //Make sure errors are empty
            if (empty($err)) {
                if (is_null($data['chat_id'])) {
                    $getmessage = $this->messageModel->check($data);
                    if ($getmessage) {
                        $data['chat_id'] = $getmessage->chat_id;
                    } else {
                        $data['chat_id'] = $this->messageModel->createchat($data);
                    }
                }


                $message = $this->statusModel->add($data);

                if ($message) {
                    //create session
                    die(json_encode($message));
                } else {
                    var_dump($err);
                    die();
                    // $this->view('users/login', $data);
                }
                #..
            } else {
                //load view with errors
                $result = $this->renderFullError($err, 401);
                print_r($result);
                die();
            }
            #..
        } else {
            //Load Form
            $data = [
                'Request Error' => 'Method not Allowed '
            ];
            $result = $this->renderFullError($data, 405);
            print_r($result);
            die();
        }
    }
    public function delete($api, $id)
    {
        $user_id = $this->user['data']->id;
        $data = [
            'status_id' => $id,
            'user_id' => $user_id
        ];
        $deletestatus = $this->statusModel->delete($data);
    }
    public function index($api, $id)
    {
        # code...
    }
    public function show($api, $id)
    {
        $this->statusModel->show($id);
    }
    public function showuserstatus($api, $user_id)
    {
        # code...
    }
    public function reply($var = null)
    {
        # code...
    }
    public function uploadfile($var = null)
    {
        if (isset($_FILES["file"]["name"])) {
            $countfiles = count($_FILES['file']['name']);
            if ($countfiles > 8) {
                return false;
            }


            // Upload directory
            $upload_location = "statuses/img/";

            // To store uploaded files path
            $files_arr = array();

            // Loop all files
            for ($index = 0; $index < $countfiles; $index++) {

                // File name
                $filename = $_FILES['file']['name'][$index];

                // Get extension
                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                // Valid image extension
                $valid_ext_img = array("png", "jpeg", "jpg");
                $valid_ext_vid = array("mp4", "3gp", "mkv");
                $valid_ext_aud = array("mp3");

                // Check extension
                if (in_array($ext, $valid_ext_img)) {
                    $upload_location = "statuses/img/";
                    // File path
                    $path = $upload_location . $filename;

                    // Upload file
                    if (move_uploaded_file($_FILES['file']['tmp_name'][$index], $path)) {
                        $files_arr[] = $path;
                        // var_dump($files_arr);
                        // die;
                    }
                    // var_dump($files_arr);
                    // die;
                } elseif (in_array($ext, $valid_ext_vid)) {
                    $upload_location = "statuses/videos/";
                    $path = $upload_location . $filename;

                    // Upload file
                    if (move_uploaded_file($_FILES['file']['tmp_name'][$index], $path)) {
                        $files_arr[] = $path;
                        // var_dump($files_arr);
                        // die;
                    }
                    // var_dump($files_arr);
                    // die;
                } elseif (in_array($ext, $valid_ext_aud)) {
                    $upload_location = "statuses/audio/";
                    $path = $upload_location . $filename;

                    // Upload file
                    if (move_uploaded_file($_FILES['file']['tmp_name'][$index], $path)) {
                        $files_arr[] = $path;
                        // var_dump($files_arr);
                        // die;
                    }
                    // var_dump($files_arr);
                    // die;
                } else {
                    $upload_location = "uploads/files/";
                    $path = $upload_location . $filename;

                    // Upload file
                    if (move_uploaded_file($_FILES['file']['tmp_name'][$index], $path)) {
                        $files_arr[] = $path;
                        // var_dump($files_arr);
                        // die;
                    }
                }
            }
        }
    }
}
