<?php

namespace App\Models\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Repositories\AuthorRepository;

class BookFactory implements ModelFactory
{
    /**
     * @return Book[]|Book
     */
    public function createFromArray(array $booksAsArray)
    {
        if (is_array($booksAsArray[0])) {
            return $this->createMultipleFromArrays($booksAsArray);
        }

        return $this->createSingleFromArray($booksAsArray);
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
        $author = $this->createAuthorFromArray($bookArray['authorId'], $bookArray['authorFullName']);

        $book = new Book($bookArray['bookName'], $author);
        $book->setId($bookArray['bookId'])
            ->setDescription($bookArray['bookDescription'])
            ->setPicturePath($bookArray['bookPicturePath']);

        return $book;
    }

    private function createAuthorFromArray(int $authorId, string $authorFullName = null): Author
    {
        $authorFactory = new AuthorFactory();

        return $authorFactory->createFromArray([
            'authorId' => $authorId,
            'authorFullName' => $authorFullName
        ]);
    }

    private function createSingleFromArray(array $bookArray): Book
    {
        return $this->setBookObject($bookArray);
    }
}
