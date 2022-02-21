<?php

require_once 'app/Config.php';
require_once 'app/Session.php';
require_once 'app/Helper.php';
require_once 'app/Router.php';
require_once 'app/Validator.php';
require_once 'app/DB.php';

use App\Config;
use App\Session;
use App\Helper;
use App\Router;
use App\Validator;
use App\DB;

$config = new Config();
$db = new DB($config);
$db->createSchema();
$session = Session::getInstance();
$router = new Router($config);

$router->validateRoute();

if ($router->hasParameters()) {
    $validator = new Validator($session);
    $validator->validateParameters($router->getParameters());
}

if ($router->route == '/register') {
    require_once 'routes/register.php';
} elseif ($router->route == '/verify') {
    require_once 'routes/verify.php';
} elseif ($router->route == '/resend') {
    require_once 'routes/resend.php';
} elseif ($router->route == '/') {
    require_once 'routes/index.php';
} else {
    Helper::abort(404);
}
