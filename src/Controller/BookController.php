<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer
    ){}

    #[Route('/books', name: 'book_get', methods: "GET")]
    public function readAll(): Response {
        $books = $this->em->getRepository(Book::class)->findAll();

        return new Response($this->serializer->serialize($books, 'json'));
    }

    #[Route('/books', name: 'book_post', methods: "POST")]
    public function create(Request $request): Response {
        $book = $this->serializer->deserialize($request->getContent(), Book::class, 'json');

        $this->em->persist($book);
        $this->em->flush();

        return new Response($this->serializer->serialize($book, 'json'), 201);
    }

    #[Route('/books/{id}', name: 'book_put', methods: "PUT")]
    public function update(Request $request, int $id): Response {
        $book = $this->em->getRepository(Book::class)->find($id);
        $book = $this->serializer->deserialize($request->getContent(), Book::class, 'json', ['object_to_populate' => $book]);

        $this->em->flush();

        return new Response($this->serializer->serialize($book, 'json'), 200);
    }

    #[Route('/books/{id}', name: 'book_delete', methods: "DELETE")]
    public function delete(Request $request, int $id): Response {
        $book = $this->em->getRepository(Book::class)->find($id);

        $this->em->remove($book);
        $this->em->flush();

        return new Response(null, 204);
    }
}
