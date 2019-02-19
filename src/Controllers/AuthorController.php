<?php

namespace App\Controllers;

use App\Models\Author;
use App\Repositories\AbstractRepository;
use App\Repositories\AuthorRepository;

class AuthorController
{
    /**
     * @var AuthorRepository
     */
    private $repository;

    public function __construct(AbstractRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Author[]
     */
    public function index(): array
    {
        return $this->repository->findAll();
    }
}
