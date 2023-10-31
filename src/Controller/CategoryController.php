<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer
    ){}

    #[Route('/categories', name: 'read_categories', methods: "GET")]
    public function readAll(): Response {
        $categories = $this->em->getRepository(Category::class)->findAll();

        return new Response($this->serializer->serialize($categories, 'json'));
    }

    #[Route('/categories', name: 'create_category', methods: "POST")]
    public function create(Request $request): Response {
        $category = $this->serializer->deserialize($request->getContent(), Category::class, 'json');

        $this->em->persist($category);
        $this->em->flush();

        return new Response($this->serializer->serialize($category, 'json'), 201);
    }

    #[Route('/categories/{id}', name: 'update_category', methods: "PUT")]
    public function update(Request $request, int $id): Response {
        $category = $this->em->getRepository(Category::class)->find($id);
        $category = $this->serializer->deserialize($request->getContent(), Category::class, 'json', ['object_to_populate' => $category]);

        $this->em->flush();

        return new Response($this->serializer->serialize($category, 'json'), 200);
    }

    #[Route('/categories/{id}', name: 'delete_category', methods: "DELETE")]
    public function delete(int $id): Response {
        $category = $this->em->getRepository(Category::class)->find($id);

        $this->em->remove($category);
        $this->em->flush();

        return new Response(null, 204);
    }
}
