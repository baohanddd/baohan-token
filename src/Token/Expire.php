<?php
namespace baohan\token\Token;

class Expire implements \Serializable
{
    /**
     * @var int
     */
    protected $timestamp = 0;

    public function __construct()
    {
    }

    public function serialize()
    {
        return $this->timestamp;
    }

    public function unserialize($expire)
    {
        $this->timestamp = intval($expire);
    }

    /**
     * @return bool
     */
    public function isExpire(): bool
    {
        return $this->timestamp < time();
    }
}