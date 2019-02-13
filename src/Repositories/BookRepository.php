<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\Exceptions\ModelWasNotDeletedException;
use App\Models\Exceptions\ModelWasNotFoundException;
use App\Models\Exceptions\ModelWasNotSavedException;
use App\Models\Exceptions\ModelWasNotUpdatedException;
use App\Models\Factories\BookFactory;

class BookRepository extends AbstractRepository
{
    const DEFAULT_LIMIT = 10;
    const DEFAULT_OFFSET = 0;

    /**
     * @var BookFactory
     */
    private $factory;

    public function __construct()
    {
        parent::__construct();
        $this->factory = new BookFactory();
    }

    /**
     * @return Book[]
     */
    public function findAll(): array
    {
        $statement = $this->getConnection()->prepare($this->getDefaultQuery());
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $this->factory->createFromArray($result);
    }

    private function getDefaultQuery(): string
    {
        return '
          SELECT book.id AS bookId, book.name AS bookName,
            book.description AS bookDescription, book.picture_path AS bookPicturePath,
            author.id AS authorId, author.full_name AS authorFullName
          FROM books book
            INNER JOIN authors author
              ON author.id = book.author_id
          ';
    }

    /**
     * @throws ModelWasNotFoundException
     */
    public function findById(int $id): Book
    {
        $statement = $this->getConnection()->prepare(
            $this->getDefaultQuery() . 'WHERE book.id = :id'
        );
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        if (!$result = $statement->fetch(\PDO::FETCH_ASSOC)) {
            throw new ModelWasNotFoundException('Unable to find a book with id = ' . $id);
        }

        return $this->factory->createFromArray($result);
    }

    /**
     * @return Book[]
     */
    public function findAllWithLimitAndOffset(
        int $limit = self::DEFAULT_LIMIT,
        int $offset = self::DEFAULT_OFFSET
    ): array {
        $statement = $this->getConnection()->prepare(
            $this->getDefaultQuery() . 'LIMIT :limit OFFSET :offset'
        );
        $statement->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $this->factory->createFromArray($result);
    }

    public function save(Book $book): Book
    {
        $statement = $this->getConnection()->prepare(
            'INSERT INTO books (name, author_id, description, picture_path)
            VALUES (:name, :authorId, :description, :picturePath)'
        );

        $statement->bindParam(':name', $book->getName());
        $statement->bindParam(':authorId', $book->getAuthor()->getId());
        $statement->bindParam(':description', $book->getDescription());
        $statement->bindParam(':picturePath', $book->getPicturePath());

        if (!$statement->execute()) {
            throw new ModelWasNotSavedException('Unable to save the book');
        }

        $book->setId($this->getConnection()->lastInsertId());

        return $book;
    }

    public function update(Book $book): Book
    {
        $statement = $this->getConnection()->prepare(
            'UPDATE books
                      SET name = :name,
                        author_id = :authorId,
                        description = :description,
                        picture_path = :picturePath
                      WHERE id = :id'
        );

        $statement->bindParam(':id', $book->getId());
        $statement->bindParam(':name', $book->getName());
        $statement->bindParam(':authorId', $book->getAuthor()->getId());
        $statement->bindParam(':description', $book->getDescription());
        $statement->bindParam(':picturePath', $book->getPicturePath());

        if (!$statement->execute()) {
            throw new ModelWasNotUpdatedException('Unable to update the book with id = ' . $book->getId());
        }

        return $book;
    }

    public function delete(int $id): void
    {
        $statement = $this->getConnection()->prepare(
            'DELETE FROM books
                       WHERE id = :id'
        );

        $statement->bindParam(':id', $id);
        if (!$statement->execute()) {
            throw new ModelWasNotDeletedException('Unable to delete the book with id = ' . $id);
        }
    }

    public function getTotalBooksCount()
    {
        $statement = $this->getConnection()->prepare(
            'SELECT COUNT(id) FROM books'
        );
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_COLUMN);
    }
}
