<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Game;
//pour debug
use Symfony\Component\HttpFoundation\Response;

class GameBoardController extends Controller {

    /**
     * @Route("/plateau_jeu/{gameId}", name="gameboard")
     */
    public function gameboardShowAction(Request $request, $gameId) {

        $oDoctrine = $this->getDoctrine();

        //Recuperation d'objet game avec son ID
        $game = $oDoctrine->getRepository(Game::class)->find($gameId);

        $checkPermission = false;
        if ($game->getPlayersNb() == count($game->getPlayers())) {

            $checkPermission = true;
            $game->setStatus('running');
            $oDoctrine->getManager()->flush();
        }
        //jet dé automatique
        //$this->launchDiceAction($request, $gameId);

        return $this->render('@App/GameBoard/game_brod.html.twig', [
                    'game' => $game,
                    'pos_jeton' => 0,
                    'checkPermission' => $checkPermission,
                    'data' => unserialize($game->getData())
        ]);
    }

    public function cm_decroissant($a, $b) {
        if ($a['bank'] > $b['bank']) {
            return -1;
        }
        if ($a['bank'] < $b['bank']) {
            return 1;
        }
        if ($a['bank'] == $b['bank']) {
            return 0;
        }
    }

    /**
     * @Route("/jet_des/{gameId}", name="launchDice")
     */
    public function launchDiceAction(Request $request, $gameId) {

        $oDoctrine = $this->getDoctrine();

        //Recuperation d'objet game avec son ID
        $game = $oDoctrine->getRepository(Game::class)->find($gameId);

        $aData = unserialize($game->getData());

        // recupere la ligne du joeur concerné 

        foreach ($aData as $key => $ligne_joueur) {
            //$index = $key;
            //dump($ligne_joueur['player']);

            if ($ligne_joueur['player'] == $this->getUser()->getUsername()) {

                $index = $key;
            }
        }

        /*         * * VERIFICATION DU TOUR DE JOUEUR ***** */
        

        if ($aData[$index]['turn']) {

            /*             * ***lancer le dés****** */
            $dice = rand(1, 6);
            /*             * **gerer la position du jeton*** */
            $aData[$index]['position'] = $aData[$index]['position'] + $dice;

            /*             * * Vérification de fin de partie de joueur qui a lancé le dés ** */
            $gameOver = false; /*             * * Joueur a fini ** */
            
            // le joueur actif à fini son tour 
            $aData[$index]['turn'] = FALSE;
            // on vérifie si le joueur à fini la partie
            if ($aData[$index]['position'] > 39) {
                // Si la position est supérieur à 39 le joueur à fini la partie
                $aData[$index]['finished'] = true;
                              
                $gameOver = true;
                // On va vérifier si les autres joueurs ont aussi fini
                $stop = true; //verifier si tt l emonde a fini
                for ($i = 0; $i < count($aData); $i++) {
                    if (!$aData[$i]['finished']) {
                        $stop = false;
                        $game->setStatus('finished');
                    }
                }
                /*** si tt le monde a fini ***/
                if ($stop) {
                    $game->setData(serialize($aData));
                    $oDoctrine->getManager()->flush();
                    return $this->redirectToRoute('classement', array('gameId' => $game->getId()));
                }
                /*** Si le joueur n'a pas fini la partie ***/
            } else {
                //recuprer la cagnotte
                //$key = array_search($aData[$index]['position'], \AppBundle\Model\Board::BOARD); 
                $key = 0;
                // Recuperation de la casse correspondant à la position dans le tableau BOARD
                while (\AppBundle\Model\Board::BOARD[$key]['index'] != $aData[$index]['position']) {

                    $key++;
                }
                /*** mettre a jour la cagnotte ***/
                $aData[$index]['bank'] = $aData[$index]['bank'] + \AppBundle\Model\Board::BOARD[$key]['valeur'];
                /*** desactivation du tour du joueur ***/
                
            }

            /***donner le tour au prochaine joueur qui peut jouer***/

            $nextPlayer = ($index + 1) % count($aData);

            while ($aData[$nextPlayer]['finished']) {
                $nextPlayer = ($nextPlayer + 1) % count($aData);
            }

            $aData[$nextPlayer]['turn'] = TRUE;
            

            $game->setData(serialize($aData));
            $oDoctrine->getManager()->flush();
            //return new Response('debug');
            if ($gameOver) {                
                return $this->redirectToRoute('gameOver', array('gameId' => $game->getId()));
                
            } else {
                return $this->render('@App/GameBoard/game_brod.html.twig', [
                            'game' => $game,
                            'dice' => $dice,
                            'pos_jeton' => $aData[$index]['position'],
                            'checkPermission' => true,
                            'data' => $aData,
                ]);
            }
            /*** Si c'est pas le tour du joueur => ON RETOURNE AU PLATEAU ***/
            // Pour gérer problème de rafraîchissement de la page en JavaScript
        } else {

        
            return $this->render('@App/GameBoard/game_brod.html.twig', [
                        'game' => $game,
                        'dice' => null,
                        'pos_jeton' => $aData[$index]['position'],
                        'checkPermission' => true,
                        'data' => $aData,
            ]);
        }
    }

    /**
     * @Route("game_over/{gameId}", name="gameOver")
     */
    public function gameOverAction(Request $request, $gameId) {

        $oDoctrine = $this->getDoctrine();

        //Recuperation d'objet game avec son ID
        $game = $oDoctrine->getRepository(Game::class)->find($gameId);

        $aData = unserialize($game->getData());

        $stop = true; //verifier si tt l emonde a fini
        for ($i = 0; $i < count($aData); $i++) {
            if (!$aData[$i]['finished']) {
                $stop = false;
                $game->setStatus('finished');
            }
        }
        /*         * * si tt le monde a fini** */
        if ($stop) {

            return $this->redirectToRoute('classement', array('gameId' => $game->getId()));
        } else {

            return $this->render('@App/GameBoard/gameOver.html.twig', [
                        'game' => $game,
                        'data' => $aData,
            ]);
        }
    }

    /**
     * @Route("classement/{gameId}", name="classement")
     */
    public function classementAction(Request $request, $gameId) {

        $oDoctrine = $this->getDoctrine();

        //Recuperation d'objet game avec son ID
        $game = $oDoctrine->getRepository(Game::class)->find($gameId);

        $aData = unserialize($game->getData());


      
        // Classement final
        uasort($aData, [$this, 'cm_decroissant']);

       

        return $this->render('@App/GameBoard/classement.html.twig', [
                    'game' => $game,
                    'data' => $aData,
        ]);
    }

}
