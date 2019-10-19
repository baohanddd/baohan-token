<?php
namespace baohan\token;


class Role implements \Serializable
{
    protected $title;

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

    public function addSupervisor(Role $role)
    {
        $this->supervisors[] = $role;
    }

    public function match(Role $role): bool
    {
        if ($this->title === $role->title) return true;
        foreach($this->supervisors as $supervisor) {
            if ($supervisor->match($role)) return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $assoc = unserialize($serialized);
        $this->title = $assoc['title'];
        $this->supervisors = $assoc['supervisors'];
    }

    public function toArray()
    {
        return [
            'title'       => $this->title,
            'supervisors' => $this->supervisors
        ];
    }
}