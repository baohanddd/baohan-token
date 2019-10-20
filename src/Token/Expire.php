<?php
namespace baohan\token\Token;

class Expire
{
    /**
     * @var int
     */
    protected $timestamp = 0;

    /**
     * @return bool
     */
    public function isAlive(): bool
    {
        return $this->timestamp > time();
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}