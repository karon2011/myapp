<?php

namespace App\Controller;

use App\Entity\Author;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorController extends AbstractController
{
    public $encoders;
    public $normalizers;
    public $serializer;

    public function __construct(EncoderInterface $encoders, NormalizerInterface $normalizers, SerializerInterface $serializer)
    {
        $this->encoders = $encoders;
        $this->normalizers = $normalizers;
        $this->serializer = $serializer;
    }
    // /**
    //  * @Route("/author", name="author")
    //  */
    // public function index()
    // {
    //     return $this->render('author/index.html.twig', [
    //         'controller_name' => 'AuthorController',
    //     ]);
    // }

    /**
     * @Route("api/authors", name="author")
     * @Method("GET")
     * @return JsonResponse
     */
    public function showAllAuthors()
    {
        $authors = $this->getDoctrine()
            ->getRepository(Author::class)
            ->findAll();

        $jsonContent = $this->serializer->serialize($authors, 'json');

        return new Response($jsonContent);
    }

    /**
     * @Route("api/authors/{id}/show", name="author_show")
     * @Method("POST")
     * @return JsonResponse
     */
    public function show($id)
    {
        $author = $this->getDoctrine()
            ->getRepository(Author::class)
            ->find($id);

        if (!$author) {
            throw $this->createNotFoundException(
                'No author found for id ' . $id
            );
        }

        return new Response($author->getName());
    }

    /**
     * @Route("api/authors/new/edit", name="create_author")
     * @Method("POST")
     */
    public function createAuthor(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $author = new Author();
        $parametersAsArray = [];

        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);

            $author->setName($parametersAsArray['name']);
            $author->setOrigin($parametersAsArray['origin']);

            $em->persist($author);
            $em->flush();
        }

        return new Response('Saved new Author : ' . $author->getName());
    }

    /**
     * @Route("api/authors/{id}/edit", name="update_author")
     * @Method("PUT")
     */
    public function updateAuthor(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $author = $entityManager->getRepository(Author::class)->find($id);
        $parametersAsArray = [];

        if (!$author) {
            throw $this->createNotFoundException(
                'No Author found for id ' . $id
            );
        }

        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);

            $author->setName($parametersAsArray['name']);
            $author->setOrigin($parametersAsArray['origin']);

            $entityManager->persist($author);
            $entityManager->flush();
        }

        return new Response('Updated Author : ' . $author->getName());
        // $entityManager = $this->getDoctrine()->getManager();
        // $author = $entityManager->getRepository(Author::class)->find($id);

        // if (!$author) {
        //     throw $this->createNotFoundException(
        //         'No author found for id '.$id
        //     );
        // }

        // $author->setName('New name!');
        // $entityManager->flush();
    }

    /**
     * @Route("api/authors/{id}/delete", name="delete_author")
     * @Method("DELETE")
     */
    public function deleteAuthor($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $author = $entityManager->getRepository(Author::class)->find($id);

        if (!$author) {
            throw $this->createNotFoundException(
                'No author found for id ' . $id
            );
        }

        $entityManager->remove($author);
        $entityManager->flush();

        return new Response('Author successfully deleted ');
    }
}
