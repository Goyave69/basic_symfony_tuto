<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(length: 255)]
    private string $ibn;

    #[ORM\Column(length: 255)]
    private string $author;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Book
     */
    public function setId(int $id): Book
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Book
     */
    public function setName(string $name): Book
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Book
     */
    public function setDescription(string $description): Book
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getIbn(): string
    {
        return $this->ibn;
    }

    /**
     * @param string $ibn
     * @return Book
     */
    public function setIbn(string $ibn): Book
    {
        $this->ibn = $ibn;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return Book
     */
    public function setAuthor(string $author): Book
    {
        $this->author = $author;
        return $this;
    }

}
