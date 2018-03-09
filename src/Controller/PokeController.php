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
     * @Route("/", name="poke_racine")
     */
    public function indexAction(): Response
    {
        return $this->render('poke/index.html.twig',
            ['server' => $this->container->getParameter('node_poke')]
        );
    }
}
