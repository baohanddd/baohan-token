<?php
namespace baohan\token\Payload;

use baohan\token\Payload;

/**
 * Anonymous token payload
 */
class AnonymousPayload extends Payload
{
    public function __construct()
    {
        parent::__construct(0);
        $this->role = Payload::ROLE_ANONYMOUS;
    }
}
