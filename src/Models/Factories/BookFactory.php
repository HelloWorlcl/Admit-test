<?php

namespace App\Models\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Repositories\AbstractRepository;

class BookFactory implements ModelFactory
{
    /**
     * @var AuthorFactory
     */
    private $authorFactory;

    /**
     * @var AbstractRepository
     */
    private $authorRepository;

    public function __construct(ModelFactory $factory, AbstractRepository $authorRepository)
    {
        $this->authorFactory = $factory;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @return Book[]|Book
     */
    public function createFromArray(array $booksAsArray)
    {
        if (is_array($booksAsArray[0])) {
            return $this->createMultipleFromArrays($booksAsArray);
        }

        return count($booksAsArray) > 0
            ? $this->createSingleFromArray($booksAsArray)
            : $booksAsArray;
    }

    /**
     * @return Book[]
     */
    private function createMultipleFromArrays(array $booksAsArray): array
    {
        $books = [];

        foreach ($booksAsArray as $bookArray) {
            $books[] = $this->setBookObject($bookArray);
        }

        return $books;
    }

    private function setBookObject(array $bookArray): Book
    {
        $author = $this->createAuthor($bookArray['authorId'], $bookArray['authorFullName']);

        $book = new Book($bookArray['bookName'], $author);
        $book->setId($bookArray['bookId'])
            ->setDescription($bookArray['bookDescription'])
            ->setPicturePath($bookArray['bookPicturePath']);

        return $book;
    }

    private function createAuthor(int $id, ?string $fullName): Author
    {
        if (!$fullName) {
            return $this->authorRepository->findById($id);
        }

        return $this->authorFactory->createFromArray([
            'authorId' => $id,
            'authorFullName' => $fullName
        ]);
    }

    private function createSingleFromArray(array $bookArray): Book
    {
        return $this->setBookObject($bookArray);
    }
}
