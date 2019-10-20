# A password hash token

---

Example
```php
require('vendor/autoload.php');

// to set up roles and relationships...
$roles = [
    'admin' => new \baohan\token\Role('admin'),
    'user'  => new \baohan\token\Role('user'),
    'guest' => new \baohan\token\Role('guest')
];

$roles['guest']->addSupervisor($roles['user']);
$roles['user']->addSupervisor($roles['admin']);
```
--------------------------------------------------------------
```php
# to generate a token
// NOTE: private key is consist with 32 chars only...
$private_key = "i7p0TdjdOxAfl3mtrk9k99HCauZWyW4Y";
$token = new \baohan\token\Token(
    new \baohan\token\Crypto($private_key)
);
// to set role for the token
$token->setRole($roles['guest']);
// expire for token
$token->setExpire(time() + 3600);
// can output token as string...
echo $token;
// works too...
$authorization = (string) $token; 
// works again...
$authorization = $token->serialize();
```
---------------------------------------------------------------
```php
// to validate a token...
$t2 = new \baohan\token\Token(
    new \baohan\token\Crypto($private_key)
);
$t2->unserialize((string) $token);
$perm = new \baohan\token\Permission($t2);
var_dump($perm->is($roles['guest']));    // true
var_dump($perm->is($roles['user']));     // true
var_dump($perm->is($roles['admin']));    // true
var_dump($perm->alive());                // true
```

That's all.
Enjoy it.


