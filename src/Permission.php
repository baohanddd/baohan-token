<?php
namespace baohan\token;


class Permission
{
    /**
     * @var Token
     */
    protected $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function is(Role $role): bool
    {
        return $this->token->getRole()->match($role);
    }

    /**
     * @return bool
     */
    public function alive()
    {
        return $this->token->getExpire()->isAlive();
    }
}