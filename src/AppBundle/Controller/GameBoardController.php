<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Game;

class GameBoardController extends Controller {

    /**
     * @Route("/plateau_jeu/{gameId}", name="gameboard")
     */
    public function gameboardShowAction(Request $request, $gameId) {

        // tester si le nb_player est bon
        $game = $this->getDoctrine()
                ->getRepository(Game::class)
                ->find($gameId)
        ;

//        dump($game->getPlayersNb());
//
//        foreach ($game->getPlayers() as $player) {
//
//            dump($player);
//        }
        $checkPermission = false;
        if ($game->getPlayersNb() == count($game->getPlayers())) {

            $checkPermission = true;
            
        } 

        return $this->render('@App/GameBoard/game_brod.html.twig', [
                    'game' => $game,
                    'pos_jeton' => 0,
                    'checkPermission' => $checkPermission,
        ]);
    }

    /**
     * @Route("/jet_des/{pos_actuelle}", name="launchDice")
     */
    public function launchDiceAction(Request $request, $pos_actuelle) {

        $dice = rand(1, 6);

        $liste_joueurs = array(0 => array('id' => 55, 'cagnotte' => 1200),
            1 => array('id' => 3, 'cagnotte' => 200),
            2 => array('id' => 107, 'cagnotte' => 0),
        );

        if ($pos_actuelle + $dice > 39) {
            return $this->redirectToRoute("gameOver");
        } else {
            return $this->render('@App/GameBoard/game_brod.html.twig', [
                        'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                        'liste_joueurs' => $liste_joueurs,
                        'dice' => $dice,
                        'pos_jeton' => $pos_actuelle + $dice,
            ]);
        }
    }

    /**
     * @Route("game_over", name="gameOver")
     */
    public function gameOverAction(Request $request) {

        return $this->render('@App/GameBoard/gameOver.html.twig', [
        ]);
    }

}
