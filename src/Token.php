<?php
namespace baohan\token;

use baohan\token\Payload\AnonymousPayload;

class Token
{
    /**
     * @var \Redis
     */
    protected $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param $token
     * @return Payload
     */
    public function get($token)
    {
        $value = $this->redis->get($token);
        if ($value) {
            $payload = Payload::unserialize($value);
            return $payload;
        }
        return new AnonymousPayload();
    }

    /**
     * @param $token
     * @return bool
     */
    public function is($token): bool
    {
        $payload = $this->get($token);
        if (!$payload) {
            return false;
        }
        return $payload->getRole() === 'admin';
    }

    /**
     * @param $token
     * @param Payload $payload
     * @return Payload
     */
    public function save($token, Payload $payload)
    {
        $this->redis->set($token, $payload->serialize());
        $this->redis->expire($token, $payload->getTTL());
        return $payload;
    }

    /**
     * @param $token
     */
    public function remove($token)
    {
        $this->redis->delete($token);
    }
}
