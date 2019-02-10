<?php

namespace App\Repositories;

use App\Models\AbstractModel;
use App\Models\Author;
use App\Models\Exceptions\ModelWasNotFoundException;
use App\Models\Factories\AuthorFactory;

class AuthorRepository extends AbstractRepository
{
    /**
     * @var AbstractRepository
     */
    private $factory;

    public function __construct()
    {
        parent::__construct();
        $this->factory = new AuthorFactory();
    }

    /**
     * @return Author[]
     */
    public function findAll(): array
    {
        $statement = $this->getConnection()->prepare($this->getDefaultQuery());
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $this->factory->createFromArray($result);
    }

    /**
     * @return AbstractModel
     */
    public function findById(int $id): Author
    {
        $statement = $this->getConnection()->prepare($this->getDefaultQuery() . 'WHERE id = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();

        if (!$result = $statement->fetch(\PDO::FETCH_ASSOC)) {
            throw new ModelWasNotFoundException('Unable to find an author with id = ' . $id);
        }

        return $this->factory->createFromArray($result);
    }

    private function getDefaultQuery()
    {
        return 'SELECT id AS authorId, full_name AS authorFullName FROM authors ';
    }
}
