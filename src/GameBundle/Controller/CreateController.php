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


class CreateController extends Controller
{
    /**
     * @Route("/game/create/{name}/{description}/{image}/{tags}", name="game_add")
     */
    public function indexAction(string $name,string $description,string $image,string $tags)
    {

        $newGame = new Game();
        $newGame->setName($name);
        $newGame->setDescription($description);
        $newGame->setImage($image);
        $newGame->setTags($tags);
        $newGame->setSumOfVote(0);
        $newGame->setNumberOfVote(0);
        $newGame->setRating(0);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newGame);
        $em->flush();

        return $this->render('GameBundle:Game:list.html.twig', []);
    }
}