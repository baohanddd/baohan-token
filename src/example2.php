<?php
require('vendor/autoload.php');

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
$token->setRole($roles['guest']);
$token->setExpire(time() + 3600);
echo $token;

$t2 = new \baohan\token\Token(
    new \baohan\token\Crypto()
);
$t2->unserialize((string) $token);
var_dump($t2);
$perm = new \baohan\token\Permission($t2);
var_dump($perm->is($roles['guest']));  // true
var_dump($perm->is($roles['user']));   // true
var_dump($perm->is($roles['admin']));  // true
var_dump($perm->alive());  // true