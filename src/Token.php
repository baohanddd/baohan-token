<?php
namespace baohan\token;

use baohan\token\Exception\HeaderWriteException;
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
    private $crypto;

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
        $this->expire->setTimestamp($timestamp);
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
    public function __toString()
    {
        return $this->serialize();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function serialize(): string
    {
        try {
            return $this->crypto->encrypt($this->toSerialize());
        } catch (\Exception $e) {
            var_dump($e);
            return "";
        }
    }

    /**
     * @param string $authorization
     * @throws \Exception
     */
    public function unserialize($authorization)
    {
        $item = unserialize($this->crypto->decrypt($authorization));
        $this->expire = $item['expire'];
        $this->role = $item['role'];
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
            'expire'  => $this->expire,
            'role'    => $this->role,
        ];
    }

    /**
     * @return string
     */
    public function toSerialize()
    {
        return serialize($this->toArray());
    }
}
