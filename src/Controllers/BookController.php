<?php

namespace App\Controllers;

use App\Models\Book;
use App\Repositories\AbstractRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\BookRepository;
use App\Service\Files\Exceptions\FileHandlerException;
use App\Service\Files\Exceptions\FileWasNotUploadedException;
use App\Service\Files\Exceptions\NotAllowedFileExtensionException;
use App\Service\Files\FileHandler;
use App\Service\Files\ImageHandler;
use http\Exception\BadQueryStringException;

class BookController
{
    /**
     * @var BookRepository
     */
    private $bookRepository;

    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    public function __construct(AbstractRepository $bookRepository, AbstractRepository $authorRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @return Book[]
     */
    public function index(): array
    {
        return $this->bookRepository->findAll();
    }

    /**
     * @return Book[]|Book
     *
     * @throws BadQueryStringException
     */
    public function show(array $params)
    {
        switch (true) {
            case isset($params['limit']) && isset($params['offset']):
                return $this->bookRepository->findAllWithLimitAndOffset($params['limit'], $params['offset']);
            case !empty($params['id']):
                return $this->bookRepository->findById($params['id']);
            case key_exists('totalCount', $params):
                return ['totalCount' => $this->bookRepository->getTotalBooksCount()];
            default:
                throw new BadQueryStringException('Supplied query parameter is not supported');
        }
    }

    /**
     * @throws FileHandlerException
     */
    public function new(array $params): Book
    {
        $author = $this->authorRepository->findById($params['authorId']);
        $book = new Book($params['bookName'], $author);
        $book->setDescription($params['bookDescription']);

        if (!empty($_FILES)) {
            $book->setPicturePath(
                $this->uploadFileAndReturnPath(new ImageHandler($_FILES['bookFile']))
            );
        }

        return $this->bookRepository->save($book);
    }

    /**
     * @throws FileHandlerException, BadQueryStringException
     */
    public function updatePUT(array $params): Book
    {
        if (!empty($params['id'])) {
            $author = $this->authorRepository->findById($params['authorId']);
            $book = $this->bookRepository->findById($params['id']);
            $book->setName($params['bookName'])
                ->setAuthor($author)
                ->setDescription($params['bookDescription']);

            if (!empty($_FILES)) {
                $book->setPicturePath(
                    $this->uploadFileAndReturnPath(new ImageHandler($_FILES['bookFile']))
                );
            }

            return $this->bookRepository->update($book);
        }

        throw new BadQueryStringException('Book id was not supplied');
    }

    /**
     * @throws BadQueryStringException
     */
    public function delete(array $params): void
    {
        if (!empty($params['id'])) {
            $this->bookRepository->delete($params['id']);

            http_response_code(204);
        }

        throw new BadQueryStringException('Book id was not supplied');
    }

    /**
     * @throws FileHandlerException
     */
    private function uploadFileAndReturnPath(FileHandler $fileHandler): string
    {
        try {
            $fileHandler->upload();

            return $fileHandler->getUploadedFilePath();
        } catch (NotAllowedFileExtensionException $e) {
            http_response_code(400);

            throw new FileHandlerException($e->getMessage());
        } catch (FileWasNotUploadedException $e) {
            http_response_code(500);

            throw new FileHandlerException($e->getMessage());
        }
    }
}
