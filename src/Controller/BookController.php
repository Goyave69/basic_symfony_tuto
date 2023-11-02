<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
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
        return new Response($this->serializer->serialize($this->em->getRepository(Book::class)->findAll(), 'json', ['groups' => 'book:read']));
    }

    #[Route('/books', name: 'book_post', methods: "POST")]
    public function create(Request $request): Response {
        //circular reference error
        /** @var Book  $book */
        $book = $this->serializer->deserialize(
            $request->getContent(),
            Book::class,
            'json',
            [
                'groups' => 'book:write'
            ]
        );

        //relation one to many
        $category = $this->em->find(Category::class, $book->getCategory()->getId());
        $category->addBook($book);

        $this->em->persist($book);
        $this->em->flush();

        return new Response($this->serializer->serialize($book, 'json', ["groups" => 'book:read']), 201);
    }

    #[Route('/books/{id}', name: 'book_put', methods: "PUT")]
    public function update(Request $request, int $id): Response {
        $book = $this->em->getRepository(Book::class)->find($id);
        $book = $this->serializer->deserialize($request->getContent(), Book::class, 'json', ['object_to_populate' => $book]);
        $category = $this->em->find(Category::class, $book->getCategory()->getId());
        $category->addBook($book);

        $this->em->flush();

        return new Response($this->serializer->serialize($book, 'json', ['groups' => 'book:read']), 200);
    }

    #[Route('/books/{id}', name: 'book_delete', methods: "DELETE")]
    public function delete(int $id): Response {
        $book = $this->em->getRepository(Book::class)->find($id);

        $this->em->remove($book);
        $this->em->flush();

        return new Response(null, 204);
    }
}
