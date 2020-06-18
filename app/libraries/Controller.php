 <?php

    /*
  * This is the Base Controller
  * Loads the Models and views
  *
  */
    class Controller
    {
        //Load Model
        public function model($model)
        {
            //Requires Model FIle
            require_once '../app/models/' . $model . '.php';

            //Instantiate Model
            return new $model();
        }

        // load views
        public function view($view, $data = [])
        {
            # check for view file
            if (file_exists('../app/views/' . $view . '.php')) {
                require_once '../app/views/' . $view . '.php';
            } else {
                die('view does not exist');
            }
        }
        public  function renderFullError($message, $errorStatusCode = null)
        {
            header('access-Control-Allow-Origin: *');
            // header("Access-Control-Allow-Headers: X-API-KEY, Origin, Authorization, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
            // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
            header("access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Max-Age: 3600");
            header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
            //Send response code via Header.
            if (is_numeric($errorStatusCode)) {
                http_response_code($errorStatusCode);
                // return $message;
                return json_encode(['error' => ['code' => $errorStatusCode, 'message' => $message]]);
            }
            // return ['success' => ['code' => $errorStatusCode, 'message' => $message]];
        }
        public function success($message, $errorStatusCode = 200)
        {
            header('access-Control-Allow-Origin: *');
            // header("Access-Control-Allow-Headers: X-API-KEY, Origin, Authorization, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
            // header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
            header("access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Max-Age: 3600");
            header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
            //Send response code via Header.
            if (is_numeric($errorStatusCode)) {
                http_response_code($errorStatusCode);
                return json_encode(['code' => $errorStatusCode, 'message' => $message]);
            }
            // else

            return json_encode(['success' => ['code' => $errorStatusCode, 'message' => $message]]);
            // return ['success' => ['code' => $errorStatusCode, 'message' => $message]];
        }
    }
