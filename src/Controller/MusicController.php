<?php

namespace App\Controller;

use App\Entity\Music;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MusicController
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"}, host="%host_music%")
 */
class MusicController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/", name="music_index")
     */
    public function index(): Response
    {
        return $this->render('music/Page/index.html.twig');
    }

    /**
     * @return Response
     *
     * @throws \LogicException
     *
     * @Route("/music/games", name="music_music_games")
     */
    public function games(): Response
    {
        return $this->process('G');
    }

    /**
     * @return Response
     *
     * @throws \LogicException
     *
     * @Route("/music/trap", name="music_music_trap")
     */
    public function trap(): Response
    {
        return $this->process('T');
    }

    /**
     * @return Response
     *
     * @throws \LogicException
     *
     * @Route("/music/electro-house", name="music_music_electro")
     */
    public function electro(): Response
    {
        return $this->process('E');
    }

    /**
     * @return Response
     *
     * @throws \LogicException
     *
     * @Route("/music/dubstep-drum-and-bass", name="music_music_dubstep")
     */
    public function dubstep(): Response
    {
        return $this->process('D');
    }

    /**
     * @param $style
     *
     * @return Response
     *
     * @throws \LogicException
     */
    private function process($style): Response
    {
        $em = $this->getDoctrine()->getManager();
        $musics = $em->getRepository(Music::class)->findByStyleOrder($style, 0);
        return $this->render('music/Page/music.html.twig', ['musics' => $musics, 'style' => $style]);
    }

    /**
     * @param $style
     * @param $page
     *
     * @return Response
     *
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \InvalidArgumentException
     *
     * @Route("/music/page/{style}/{page}", name="music_page", requirements={"style"="g|t|e|d|s", "page"="\d+"})
     */
    public function page($style, $page): Response
    {
        $em = $this->getDoctrine()->getManager();
        $begin = ($page - 1) * 20;
        if ($begin < 0) {
            throw new NotFoundHttpException();
        }

        $musics = $em->getRepository(Music::class)->findByStyleOrder($style, $begin);
        return $this->render('music/Json/music.json.twig',
            ['musics' => $musics, 'style' => $style],
            new Response('application/json', 200, ['Content-Type' => 'application/json'])
        );
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \LogicException
     *
     * @Route("/music/search", name="music_search")
     */
    public function search(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$request->request->get('search')) {
            if (!$request->headers->get('referer')) {
                return $this->redirect($this->generateUrl('music_index'));
            }
            if (substr($request->headers->get('referer'), -\strlen('/music/search')) === '/music/search') {
                return $this->redirect($this->generateUrl('music_index'));
            }
            return $this->redirect($request->headers->get('referer'));
        }
        $query = $request->request->get('search');
        $musics = $em->getRepository(Music::class)->findByTitleSearch($query);

        return $this->render('music/Page/music.html.twig', ['musics' => $musics, 'style' => 'S']);
    }
}
