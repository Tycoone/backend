<?php
// require_once 'libraries/Core.php';
// require_once 'libraries/Controller.php';
// require_once 'libraries/Database.php';
// require_once 'libraries/Mail.php';
spl_autoload_register(function ($className) {
    require_once '../app/libraries/' . $className . '.php';
});
// require_once '../app/libraries/Core.php';
// require_once '../app/libraries/Controller.php';
// require_once '../app/libraries/Database.php';
// require_once '../app/libraries/Mail.php';
// require_once '../app/libraries/Mailer.php';
