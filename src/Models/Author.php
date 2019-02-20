<?php

namespace App\Models;

class Author extends AbstractModel implements \JsonSerializable
{
    protected $id;

    protected $fullName;

    public function __construct(string $fullName)
    {
        $this->fullName = $fullName;
    }

    public function setId(int $id): Author
    {
        /*
         * I understand, that we cannot set an id manually,
         * but it's necessary only for BookFactory
         * and doesn't impact on the final entity id
         */
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setFullName(string $fullName): Author
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'fullName' => $this->getFullName()
        ];
    }
}
