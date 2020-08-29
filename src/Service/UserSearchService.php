<?php


namespace App\Service;


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

    public function searchUser($id)
    {
        return $this->userRepository->findOneBy(["id"=> $id]);
    }
}