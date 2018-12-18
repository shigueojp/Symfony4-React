<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/")
 */
class FirstController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return new JsonResponse([
            'action' => 'index',
            'time' => time(),
        ]);
    }
}