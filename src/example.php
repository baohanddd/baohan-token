<?php
use Phalcon\Mvc\Router;

$roles = [
    'admin' => new \baohan\token\Role('admin'),
    'user'  => new \baohan\token\Role('user'),
    'guest' => new \baohan\token\Role('guest')
];

$roles['guest']->addSupervisor($roles['user']);
$roles['user']->addSupervisor($roles['admin']);

$pk = "mockup_pk";
$crypto = new \baohan\token\Crypto($pk);
$token  = new \baohan\token\Token($crypto);
$token->setRole($roles['guest']);
$token->setExpire(time() + 3600);

echo json_encode(['token' => $token]);

if ($authorization) {
    $token->unserialize($authorization);
}

$permission = new \baohan\token\Permission($token);
$permission->is($roles['admin']) === true; // boolean
$permission->alive() === true;             // boolean

// --------------------------------------------------------------



// --------------------------------------------------------------
# integrated with phalcon router
# initial token
$token = new \baohan\token\Token(
    new \baohan\token\Crypto()
);

$router = new Router();
$route = $router->add('/login');
$route->beforeMatch(function($uri, $route) use ($di) {
    $role = $di->get('roles')->get('admin');
    $token = $di->get('token');
    $request = $di->get('request');
    $authorization = $request->getHeader('Authorization');
    if(!$authorization) {
        $token->unserialize($authorization);
    }
    $permission = new \baohan\token\Permission($token);
    return $permission->alive() && $permission->is($role);
});