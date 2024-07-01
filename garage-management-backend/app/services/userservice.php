<?php
namespace Services;

use Repositories\UserRepository;

class UserService {

    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function authenticate($email, $password)
    {
        return $this->repository->checkEmailPassword($email, $password);
    }

    public function emailExists($email)
    {
        return $this->repository->emailExists($email);
    }

    public function createUser($user)
    {
        return $this->repository->insert($user);
    }
}

?>