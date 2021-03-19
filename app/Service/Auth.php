<?php declare(strict_types=1);


namespace App\Service;


use App\Entity\User;

class Auth
{
    protected $currentUser;

    /**
     * Auth constructor.
     * @param User|null $currentUser
     */
    public function __construct(?User $currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * @return User|null
     */
    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }
}