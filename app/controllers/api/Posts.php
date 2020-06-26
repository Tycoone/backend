<?php
class Posts extends Controller
{

    public $user;

    use authuser;
    public function __construct()
    {
        // if (!isLoggedIn()) {
        //     redirect('users/login');
        // }
        $this->user = $this->user();
        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }
    public function index()
    {
        $posts = $this->postModel->getPosts($this->user['data']->id);
        $data = [
            'posts' => $posts
        ];
        $result = $this->success($data, 200);
        print_r($result);
        die();
    }
    public function show($api, $id)
    {
        $post = $this->postModel->show($id);
        // var_dump($post->user_id);
        // die();
        $user = $this->userModel->getUserbyId($post->user_id);
        $comments = $this->getcomments($id);
        $data = [
            'post' => $post,
            'user' => $user,
            'comments' => $comments
        ];
        $result = $this->success($data, 200);
        print_r($result);
        die();
    }
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $img = null;
            if (isset($_FILES["file"]["name"])) {
                $img = $this->uploadfile();
            }
            $data = [
                'caption' => trim($_POST['caption']),
                'user_id' => $this->user['data']->id,
                'file' => $img
            ];
            // var_dump($data);
            // die;
            $err = [];
            //validate title
            if (empty($data['caption'])) {
                $err['caption_err'] = 'Please enter caption';
            }

            if (empty($err)) {
                // die('success');
                if ($this->postModel->addPost($data)) {
                    $data = [
                        'message' => 'Post Added Successfully'
                    ];
                    $result = $this->success($data, 200);
                    print_r($result);
                    die();
                } else {
                    $err = [
                        'message' => 'Something went wrong'
                    ];
                    $result = $this->renderFullError($data, 500);
                    print_r($result);
                    die();
                }
            } else {
                $result = $this->renderFullError($err, 400);
                print_r($result);
                die();
            }
        } else {
            $data = [
                'Request Error' => 'Method not Allowed '
            ];
            $result = $this->renderFullError($data, 405);
            print_r($result);
            die();
        }
    }
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'id' => $id,
                'caption' => trim($_POST['caption']),
            ];
            $err = [];
            //validate title
            if (empty($data['caption'])) {
                $err['caption'] = 'Please enter caption';
            }

            if (empty($err)) {
                // die('success');
                if ($this->postModel->updatePost($data)) {
                    $data = [
                        'message' => 'Post Updated Successfully'
                    ];
                    $result = $this->success($data, 200);
                    print_r($result);
                    die();
                } else {
                    $err = [
                        'message' => 'Something went wrong'
                    ];
                    $result = $this->renderFullError($data, 500);
                    print_r($result);
                    die();
                }
            } else {
                $result = $this->renderFullError($err, 400);
                print_r($result);
                die();
            }
        } else {
            $data = [
                'Request Error' => 'Method not Allowed '
            ];
            $result = $this->renderFullError($data, 405);
            print_r($result);
            die();
        }
    }
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post = $this->postModel->show($id);
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }
            if ($this->postModel->deletePost($id)) {
                flash('post_message', 'Post Removed');
                redirect('posts');
            }
        } else {
            redirect('posts');
        }
    }

    public function like($api, $post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'post_id' => $post_id,
                'user_id' => $this->user['data']->id,
            ];
            $err = [];
            if ($this->postModel->validate_if_liked($data)) {
                $data = [
                    'Error' => 'You have already liked'
                ];
                $result = $this->renderFullError($data, 401);
                print_r($result);
                die();
            } else {
                if ($this->postModel->likepost($data)) {
                    $result = $this->success('Like Successful', 200);
                    print_r($result);
                    die();
                };
            }
        } else {
            $data = [
                'Request Error' => 'Method not Allowed '
            ];
            $result = $this->renderFullError($data, 405);
            print_r($result);
            die();
        }
    }
    public function uploadfile($var = null)
    {
        if (isset($_FILES["file"]["name"])) {
            $countfiles = count($_FILES['file']['name']);
            if ($countfiles > 8) {
                return false;
            }


            // Upload directory
            $upload_location = "uploads/img/";

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
                    $upload_location = "uploads/img/";
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
                    $upload_location = "uploads/videos/";
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
                    $upload_location = "uploads/audio/";
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
            return $files_arr;
        }
    }
    public function comment($api, $post_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $img = null;
            if (isset($_FILES["file"]["name"])) {
                $img = $this->uploadfile();
            }
            $data = [
                'post_id' => $post_id,
                'user_id' => $this->user['data']->id,
                'comment' => trim($_POST['comment']),
                'type' => trim($_POST['type']),
                'file' => $img,
            ];
            $err = [];

            if ($this->postModel->commentonpost($data)) {
                $result = $this->success('Comment Successful', 200);
                print_r($result);
                die();
            }
        } else {
            $data = [
                'Request Error' => 'Method not Allowed '
            ];
            $result = $this->renderFullError($data, 405);
            print_r($result);
            die();
        }
    }
    protected function getcomments($id)
    {
        $post = $this->postModel->getcomments($id);
        // $result = $this->success($post, 200);
        // print_r($result);
        return $post;
        // die();
    }
    public function getcomment($api, $id)
    {
        $post = $this->postModel->getcomments($id);
        $result = $this->success($post, 200);
        print_r($result);
        // return $post;
        die();
    }
}
