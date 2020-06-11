<?php
class Users extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //process form 

            //sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'gender' => trim($_POST['gender']),
                // 'name_err' => '',
                // 'email_err' => '',
                // 'password_err' => '',
                // 'confirm_password_err' => ''
            ];
            $err = [
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            // validate name

            if (empty($data['firstname'])) {
                $err['name_err'] = 'Please enter firstname';
            }
            if (empty($data['lastname'])) {
                $err['name_err'] = 'Please enter lastname';
            }
            //Validate Email
            if (empty($data['email'])) {
                $err['email_err'] = 'Please enter email';
            } else {
                // check email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $err['email_err'] = 'email already taken';
                }
            }

            //Validate Password
            if (empty($data['password'])) {
                $err['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $err['password_err'] = 'Password must have 6 characters';
            }

            // Validate Confirm Password

            if (empty($data['confirm_password'])) {
                $err['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $err['confirm_password_err'] = 'Passwords do not match';
                }
            }

            //Make sure errors are empty
            if (empty($err['email_err']) && empty($err['name_err']) && empty($err['password_err']) && empty($err['confirm_password_err'])) {
                //validated
                // die('success');
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                //Register User
                if ($this->userModel->register($data)) {
                    flash('register_success', 'You are Registered Successfully');
                    // die($data);
                    print_r($this->success($data, 200));
                    die;
                    // redirect('users/login');
                } else {
                    die('Something went wrong');
                }
                #..
            } else {
                //load view with errors
                $result = $this->renderFullError($err, 401);
                print_r($result);
                die();
                // $this->view('users/register', $data);
            }
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

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

            //Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            //Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }
            //Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                //user found
            } else {
                $data['email_err'] = 'no user found';
            }

            //Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                //validated
                //check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    //create session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password Incorrect';

                    die($data);
                    // $this->view('users/login', $data);
                }
                #..
            } else {
                //load view with errors
                $this->view('users/login', $data);
            }
            #..
        } else {
            //Load Form
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];
            $this->view('users/login', $data);
        }
    }
    public function  createUserSession($user)
    {
        // $_SESSION['user_id'] = $user->id;
        // $_SESSION['email'] = $user->email;
        // $_SESSION['user_name'] = $user->name;
        // redirect('posts');


        // var_dump($user);
        // die;

        $secret_key = "YOUR_SECRET_KEY";
        $issuer_claim = URLROOT; // this can be the servername
        $audience_claim = "THE_AUDIENCE";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 10; //not before in seconds
        $expire_claim = $issuedat_claim + 6000; // expire time in seconds
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $user->id,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "email" => $user->email,
            )
        );

        http_response_code(200);

        require_once '../vendor/firebase/php-jwt/src/JWT.php';
        $jwt = \Firebase\JWT\JWT::encode($token, $secret_key);
        // $jwt = $this->jwt::encode($token, $secret_key);

        print_r(
            $this->success(
                // json_encode(
                array(
                    "message" => "Successful login.",
                    "token" => $jwt,
                    "email" => $user->email,
                    "user" => $token["data"],
                    "expireAt" => $expire_claim
                )
            )
        );
    }
    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
    }
}
