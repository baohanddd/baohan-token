<?php
namespace baohan\token\Payload;

use App\Model\Entity\User;
use App\Model\Forbiddable;

use baohan\token\Payload;

/**
 * User token payload
 */
class UserPayload extends Payload implements Forbiddable
{
    /**
     * @var string
     */
    protected $user_id = "";

    public function __construct(int $expire, string $userId)
    {
        parent::__construct($expire);
        $this->role = Payload::ROLE_USER;
        $this->user_id = $userId;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $item = parent::toArray();
        $item['user_id'] = $this->user_id;
        return $item;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isForbidden(): bool
    {
        $usr = User::findByIdWithException($this->user_id);
        if ($usr instanceof Forbiddable) {
            return $usr->isForbidden();
        }
        return false;
    }
}
