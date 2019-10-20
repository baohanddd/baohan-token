<?php
use Phalcon\Mvc\Router;

$roles = [
    'admin' => new \baohan\token\Role('admin'),
    'user'  => new \baohan\token\Role('user'),
    'guest' => new \baohan\token\Role('guest')
];

$roles['guest']->addSupervisor($roles['user']);
$roles['user']->addSupervisor($roles['admin']);
// --------------------------------------------------------------
# initial token
$token = new \baohan\token\Token(
    new \baohan\token\Crypto()
);
$token->setRole($roles['admin']);
$token->setExpire(time() + 3600);
echo $token;