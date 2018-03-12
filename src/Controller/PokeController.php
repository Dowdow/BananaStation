<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PokeController
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"}, host="%host_poke%")
 */
class PokeController extends Controller
{
    /**
     * @return Response
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     *
     * @Route("/", name="poke_index")
     */
    public function index(): Response
    {
        return $this->render('poke/index.html.twig',
            ['server' => $this->container->getParameter('node_poke')]
        );
    }
}
