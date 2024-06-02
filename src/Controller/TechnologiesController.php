<?php

namespace App\Controller;

use App\Entity\Technologies;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TechnologiesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TechnologiesController extends AbstractController
{
    #[Route('/api/technologies', name: 'app_technologies', methods: ['GET'])]
    public function index(TechnologiesRepository $technoRepository, SerializerInterface $serializer ): JsonResponse
    {
        $technologies = $technoRepository->findAll();
        $technologies = $serializer->serialize($technologies, 'json');
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TechnologiesController.php',
            'technologies' => $technologies,
        ]);
    }


    
    #[Route('/api/technologies/{id}', name: 'detailTechnologie', methods: ['GET'])]
    public function getDetailTechnologie(int $id, SerializerInterface $serializer,TechnologiesRepository $technoRepository ): JsonResponse {

        $technologie = $technoRepository->find($id);
        if ($technologie) {
            $jsonBook = $serializer->serialize($technologie, 'json');
            return new JsonResponse($jsonBook, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
    
    #[Route('/api/technologies/{id}', name: 'deleteTechnologies', methods: ['DELETE'])]
    public function deleteTechnologies(Technologies $technologies, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($technologies);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/technologies', name:"createTechnologie", methods: ['POST'])]
    public function createTechnologie(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {

        $technologie = $serializer->deserialize($request->getContent(), Technologies::class, 'json');
        $em->persist($technologie);
        $em->flush();

        $jsonTechnologie = $serializer->serialize($technologie, 'json');
        
        $location = $urlGenerator->generate('detailTechnologie', ['id' => $technologie->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonTechnologie, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/api/technologies/{id}', name:"updateTechnologies", methods:['PUT'])]

    public function updateTechnologie(Request $request, SerializerInterface $serializer, Technologies $currentTechnologie, EntityManagerInterface $em): JsonResponse 
    {
        $updatedBook = $serializer->deserialize($request->getContent(), 
                Technologies::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentTechnologie]);
        $content = $request->toArray();
        
        $em->persist($updatedBook);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
