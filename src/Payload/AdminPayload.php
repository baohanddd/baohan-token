<?php
namespace baohan\token\Payload;

use baohan\token\Payload;

/**
 * admin token payload
 */
class AdminPayload extends Payload
{
    /**
     * @var string
     */
    protected $role = Payload::ROLE_ADMIN;
}
