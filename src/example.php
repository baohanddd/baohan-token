<?php

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
$permission->is($roles['admin']) === true; // false
$permission->expire() === true;            // false

