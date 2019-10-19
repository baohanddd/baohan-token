<?php
namespace baohan\token;

/**
 * token payload
 */
class Payload
{
    /**
     * @var string
     */
    protected $role = "";

    /**
     * @var int
     */
    protected $expire = 0;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER  = 'user';
    const ROLE_ANONYMOUS = 'anonymous';

    /**
     * @param int $expire
     */
    public function __construct(int $expire)
    {
        $this->expire = $expire;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this);
    }

    /**
     * @param $value
     * @return static
     */
    public static function unserialize($value)
    {
        return unserialize($value);
    }

    /**
     * @return int
     */
    public function getTTL()
    {
        return $this->expire;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    public function toArray(): array
    {
        return [
            'role'   => $this->role,
            'expire' => $this->expire
        ];
    }
}
