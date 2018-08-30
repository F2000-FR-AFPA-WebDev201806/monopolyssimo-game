<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GameBoardController extends Controller {

    /**
     * @Route("/plateau_jeu", name="gameboard")
     */
    public function gameboardShowAction(Request $request) {

//        $plateauDeJeu = new \AppBundle\Controller\MesClasses\gameBoard();
//
//        for ($i = 1; $i <= 40; $i++) {
//            $case = new \AppBundle\Controller\MesClasses\caseGame();
//            $case->valeur = rand(0, 100);
//            $plateauDeJeu[$i] = $case;
//        }
//        $plateauSerilialize = serialize($plateauDeJeu);
//
//        $pdo = new \PDO('mysql:host=localhost;dbname=symfony', 'root', '', array(
//            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "utf8"',
//            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING
//        ));
        $liste_joueurs = array(0 => array('id' => 55, 'cagnotte' => 1200),
            1 => array('id' => 3, 'cagnotte' => 200),
            2 => array('id' => 107, 'cagnotte' => 0),
        );

        return $this->render('@App/GameBoard/game_brod.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                    'liste_joueurs' => $liste_joueurs,
                    'pos_jeton' => 0
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
            $this->addFlash('Success', 'Partie terminé !');
            return $this->redirectToRoute("homepage");
        } else {
            return $this->render('@App/GameBoard/game_brod.html.twig', [
                        'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
                        'liste_joueurs' => $liste_joueurs,
                        'dice' => $dice,
                        'pos_jeton' => $pos_actuelle + $dice,
            ]);
        }
    }

}
