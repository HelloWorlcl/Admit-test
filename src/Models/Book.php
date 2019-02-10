<?php

namespace App\Models;

class Book extends AbstractModel implements \JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Author
     */
    protected $author;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $picturePath;

    public function __construct(string $name, Author $author)
    {
        $this->name = $name;
        $this->author = $author;
    }

    public function setId(?int $id): Book
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

    public function setName(string $name): Book
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setAuthor(Author $author): Book
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setDescription(?string $description): Book
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setPicturePath(?string $picturePath): Book
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'author' => $this->getAuthor(),
            'description' => $this->getDescription(),
            'picturePath' => $this->getPicturePath()
        ];
    }
}
