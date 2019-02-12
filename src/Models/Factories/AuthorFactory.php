<?php

namespace App\Models\Factories;

use App\Models\Author;
use App\Repositories\AuthorRepository;

class AuthorFactory implements ModelFactory
{
    /**
     * @return Author[]|Author
     */
    public function createFromArray(array $authorsAsArray)
    {
        if (is_array($authorsAsArray[0])) {
            return $this->createMultipleFromArrays($authorsAsArray);
        }

        return $this->createSingleFromArray($authorsAsArray);
    }

    /**
     * @return Author[]
     */
    private function createMultipleFromArrays(array $authorsAsArray): array
    {
        $authors = [];

        foreach ($authorsAsArray as $authorArray) {
            $authors[] = $this->setAuthorObject($authorArray);
        }

        return $authors;
    }

    private function setAuthorObject(array $authorArray): Author
    {
        if (!$authorArray['authorFullName']) {
            $authorRepository = new AuthorRepository();

            return $authorRepository->findById($authorArray['authorId']);
        }

        $author = new Author($authorArray['authorFullName']);
        $author->setId($authorArray['authorId']);

        return $author;
    }

    private function createSingleFromArray(array $authorArray): Author
    {
        return $this->setAuthorObject($authorArray);
    }
}
