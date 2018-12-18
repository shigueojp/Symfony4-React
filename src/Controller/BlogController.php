<?php

namespace App\Controller;


use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Flex\Response;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 5}, requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit',10);
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

        return new JsonResponse(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function (BlogPost $item) {
                    return $this->generateUrl('blog_by_id', ['id' => $item->getId()]);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */
    // ParamConverter("nomeParametro", class="Caminho da Classe", options={"mapping": {"NOMECAMPO": "FIELDNAME"}})
    public function post(BlogPost $id)
    {
        // Same as doing find(['id' => $id]);
        // It works because of ParamConverter Symfony Library
        return $this->json($id);
    }

    /**
     * @Route("/post/{sluguinho}", name="blog_by_slug", methods={"GET"})
     * The below annotation is not required when $slug is defined with BlogPost
     * and route parameter name MATCHES any field on the BlogPost entity
     * @ParamConverter("qualquercoisa", options={"mapping": {"sluguinho": "slug"}}, class="App:BlogPost")
     */
    public function postBySlug($qualquercoisa)
    {
        // Same as doing findOneBy(['slug' => $slug]);
        return $this->json($qualquercoisa);
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(),BlogPost::class,'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
     */
    public function delete(BlogPost $id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return new JsonResponse(null, \Symfony\Component\HttpFoundation\Response::HTTP_NO_CONTENT);
    }




}