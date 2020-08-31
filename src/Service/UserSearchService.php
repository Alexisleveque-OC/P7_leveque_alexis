<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;

class UserSearchService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function searchUser(User $user)
    {
        return $this->userRepository->findOneBy(["id"=> $user->getId()]);
    }
}