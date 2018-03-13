<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TronController
 *
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"}, host="%host_tron%")
 */
class TronController extends Controller
{
    /**
     * @return Response
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     *
     * @Route("/", name="tron_index")
     */
    public function index(): Response
    {
        return $this->render('tron/index.html.twig',
            ['server' => $this->container->getParameter('node_tron')]
        );
    }
}
