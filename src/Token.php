<?php
namespace baohan\token;

use baohan\token\Token\Expire;

class Token implements \Serializable, \JsonSerializable
{
    /**
     * @var Expire
     */
    protected $expire;

    /**
     * @var Role
     */
    protected $role;

    /**
     * @var Crypto
     */
    protected $crypto;

    public function __construct(Crypto $crypto)
    {
        $this->crypto = $crypto;
        $this->expire = new Expire();
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    public function setExpire(int $timestamp)
    {
        $this->expire->unserialize($timestamp);
    }

    /**
     * @return mixed|string
     * @throws \Exception
     */
    public function jsonSerialize()
    {
        return $this->serialize();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function serialize()
    {
        return $this->crypto->encrypt($this->toJson());
    }

    /**
     * @param string $authorization
     * @throws \Exception
     */
    public function unserialize($authorization)
    {
        $item = json_decode($this->crypto->decrypt($authorization));
        $this->expire->unserialize($item->expire);
        $this->role->unserialize($item->role);
    }

    /**
     * @return Expire
     */
    public function getExpire(): Expire
    {
        return $this->expire;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'expire'  => $this->expire->serialize(),
            'role'    => $this->role,
        ];
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
