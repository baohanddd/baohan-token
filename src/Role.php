<?php
namespace baohan\token;


class Role
{
    /**
     * @var string
     */
    protected $title = "";

    /**
     * @var string
     */
    protected $identity = "";

    /**
     * @var Role[]
     */
    protected $supervisors = [];

    public function __construct($title, $supervisors = [])
    {
        $this->title = $title;
        foreach($supervisors as $role) {
            $this->addSupervisor($role);
        }
    }

    public function setIdentity(string $identity)
    {
        $this->identity = $identity;
    }

    public function addSupervisor(Role $role)
    {
        $this->supervisors[] = $role;
    }

    public function match(Role $role): bool
    {
        if ($this->title === $role->title) return true;
        foreach($role->supervisors as $supervisor) {
            if ($supervisor->match($role)) return true;
        }
        return false;
    }

    /**
     * @param string $identity
     * @return bool
     */
    public function self(string $identity): bool
    {
        return $this->identity === $identity;
    }
}