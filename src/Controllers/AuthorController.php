<?php

namespace App\Controllers;

use App\Models\Author;
use App\Repositories\AbstractRepository;
use App\Repositories\AuthorRepository;

class AuthorController
{
    /**
     * @var AbstractRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new AuthorRepository();
    }

    /**
     * @return Author[]
     */
    public function index(): array
    {
        return $this->repository->findAll();
    }
}
