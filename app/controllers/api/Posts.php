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
        $posts = $this->postModel->getPosts();
        $data = [
            'posts' => $posts
        ];
        $this->view('posts/index', $data);
        // print(json_encode($data));
    }
    public function show($api, $id)
    {
        $post = $this->postModel->show($id);
        // var_dump($post->user_id);
        // die();
        $user = $this->userModel->getUserbyId($post->user_id);
        $data = [
            'post' => $post,
            'user' => $user
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
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'title_err' => '',
                'body_err' => '',
            ];

            //validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }
            if (empty($data['body'])) {
                $data['body_err'] = 'Please enter body text';
            }

            if (empty($data['title_err']) && empty($data['body_err'])) {
                // die('success');
                if ($this->postModel->updatePost($data)) {
                    flash('post_message', 'Post Edited');
                    redirect("posts");
                } else {
                    die('somethin went wrong');
                }
            } else {
                $this->view('posts/edit', $data);
            }
        } else {
            $post = $this->postModel->show($id);
            if ($post->user_id != $_SESSION['user_id']) {
                redirect('posts');
            }
            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body,
                'title_err' => '',
                'body_err' => '',
            ];
            $this->view('posts/edit', $data);
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
}
