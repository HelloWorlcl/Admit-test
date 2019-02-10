<?php

namespace App\Controllers;

use App\Models\Book;
use App\Models\Factories\BookFactory;
use App\Models\Factories\ModelFactory;
use App\Repositories\BookRepository;
use http\Exception\BadQueryStringException;

class BookController extends AbstractController
{
    /**
     * @var BookRepository
     */
    private $repository;

    /**
     * @var ModelFactory
     */
    private $factory;

    public function __construct()
    {
        $this->repository = new BookRepository();
        $this->factory = new BookFactory();
    }

    /**
     * @return Book[]
     */
    public function index(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @return Book[]|Book
     */
    public function show(array $params)
    {
        if (isset($params['limit']) && isset($params['offset'])) {
            return $this->repository->findAllWithLimitAndOffset($params['limit'], $params['offset']);
        }

        if (!empty($params['id'])) {
            return $this->repository->findById($params['id']);
        }

        throw new BadQueryStringException('Supplied query parameter is not supported');
    }

    public function new(array $params): Book
    {
        $book = $this->factory->createFromArray($params);

        return $this->repository->save($book);
    }

    public function updatePut(array $params): Book
    {
        $book = $this->factory->createFromArray($params);

        return $this->repository->update($book);
    }

    public function delete(array $params): void
    {
        if (!empty($params['id'])) {
            $this->repository->delete($params['id']);

            http_response_code(204);
        }

        throw new BadQueryStringException('Supplied query parameter is not supported');
    }
}
