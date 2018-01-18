<?php
/**
 * Created by PhpStorm.
 * User: probe
 * Date: 27/09/2017
 * Time: 16:35
 */

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GameBundle\Repository\GameRepository;
use GameBundle\Entity\Game;
use GameBundle\Entity\Rating;

class DetailController extends Controller
{
    /**
     * @Route("/game/detail/{gameId}", name="game_detail",requirements={"gameId": "[0-9]+"})
     */
    public function indexAction(int $gameId, Request $request)
    {
        $comment = new Rating();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();


        $form = $this->createFormBuilder($comment)
            ->add('note', TextType::class, array("label" => false,"data" => "-1","attr" => array("style" => "display:none;")))
            ->add('comment', TextareaType::class, array("label" => "Comment : "))
            ->add('save', SubmitType::class, array('label' => 'Send Comment'))
            ->getForm();


        $form->handleRequest($request);
        $gameRepository = $this->getDoctrine()->getRepository("GameBundle\Entity\Game");
        $game = $gameRepository->find($gameId);
        $gameStatService = $this->container->get('game.statistique.averageNote');


        if ($form->isSubmitted() && $form->isValid() && $user) {
            $comment = $form->getData();

            $comment->setGame($game);
            $comment->setUserId($user->getId());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('game_detail',[
                "gameId" => $gameId,
            ]);
        }


        $commentRepository = $this->getDoctrine()->getRepository("GameBundle\Entity\Rating");
        $comments = $commentRepository->findBy(array("game" => $game));
        $userRepository = $this->getDoctrine()->getRepository("GameBundle\Entity\User");

        $commentsObject = [];
        foreach ($comments as $comment) {
            $object = [];
            $object['commentClass'] = $comment;
            $object['userClass'] = $userRepository->find($comment->getUserId());
            $commentsObject[] = $object;
        }

        if ($game !== null) {
            return $this->render('GameBundle:Game:detail.html.twig', [
                "game" => $game,
                "user" => $user,
                'form' => $form->createView(),
                'commentsArray' => $commentsObject,
                'averageNote' => $gameStatService->getAverageNote($game->getId()),
            ], new Response('', 200));
        } else {
            return $this->render('GameBundle:Game:404.html.twig', [], new Response('404 Game not Found', 200));
        }
    }
}