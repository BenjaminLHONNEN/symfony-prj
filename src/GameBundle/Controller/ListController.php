<?php
/**
 * Created by PhpStorm.
 * User: probe
 * Date: 27/09/2017
 * Time: 16:34
 */

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GameBundle\Repository\GameRepository;
use GameBundle\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use GameBundle\Entity\Rating;


class ListController extends Controller
{
    /**
     * @Route("/game/list", name="game_list")
     */
    public function indexAction(Request $request)
    {
        $gameRepository = $this->getDoctrine()->getRepository("GameBundle\Entity\Game");

        $sortTarget = $request->get('sort', 'none');
        $sortMode = $request->get('order', 'none');
        if ($sortTarget === "name") {
            if ($sortMode === "ascend") {
                $games = $gameRepository->findBy([], ['name' => 'ASC']);
            } else {
                $games = $gameRepository->findBy([], ['name' => 'DESC']);
            }
        } else {
            $games = $gameRepository->findAll();
        }

        if ($sortMode === "ascend") {
            $invertSortMode = "descend";
        } else {
            $invertSortMode = "ascend";
        }

        return $this->render('GameBundle:Game:list.html.twig', [
            "games" => $games,
            "sortTarget" => $sortTarget,
            "sortMode" => $sortMode,
            "invertSortMode" => $invertSortMode,
        ]);
    }


    /**
     * @Route("/", name="index")
     */
    public function defaultAction()
    {
        return $this->indexAction(new Request());
    }
}