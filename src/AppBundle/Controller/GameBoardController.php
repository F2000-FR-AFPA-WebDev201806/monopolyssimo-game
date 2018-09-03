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
                    'data'=> unserialize($game->getData())
        ]);
    }

    public function cm_decroissant ($a, $b) {
        if ($a['bank']<$b['bank']) {
            return -1;
        }
        if ($a['bank']>$b['bank']) {
            return 1;
        }
        if ($a['bank']==$b['bank']) {
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
        
        $dice = rand(20, 30);
         // recupere la ligne du joeur concerné 
        
        foreach ($aData as $key => $ligne_joueur) {
            //$index = $key;
            //dump($ligne_joueur['player']);
            
            if ($ligne_joueur['player'] == $this->getUser()->getUsername() ){
                     
                $index = $key;
             
            }
        }
            
        $gameOver=false;
        $aData[$index]['position'] = $aData[$index]['position']+$dice;

        if ($aData[$index]['position'] > 39) {
            $aData[$index]['finished'] = true;
            $gameOver=true;

            //verifier si tt l emonde a fini
            $stop=true;
            for ($i=0; $i<count($aData);$i++) {
                if (!$aData[$i]['finished']) {
                    $stop=false;
                    //status=>finished
                }
            }
            if ($stop) {
                $game->setData(serialize($aData));
                $oDoctrine->getManager()->flush();
                
                //dump(array_multisort($aData,  SORT_DESC, 'bank'));
                uasort($aData, [$this, 'cm_decroissant']);
                
                dump($aData);
                
                return $this->render('@App/GameBoard/gameOver.html.twig', [
                   'game'=>$game,
                   'data'=> $aData,
                ]);

            }

        } else {

            //recuprer la cagnotte
            //$key = array_search($aData[$index]['position'], \AppBundle\Model\Board::BOARD); 
           $key=0; 
           while ( \AppBundle\Model\Board::BOARD[$key]['index'] != $aData[$index]['position']) {

                $key++;

           }

           $aData[$index]['bank']=$aData[$index]['bank']+ \AppBundle\Model\Board::BOARD[$key]['valeur'];
           $aData[$index]['turn'] = FALSE;

       }

       $nextPlayer = ($index+1) % count($aData);


       while ($aData[$nextPlayer]['finished']){
           $nextPlayer = ($nextPlayer+1)% count($aData);
       }

       $aData[$nextPlayer]['turn'] = TRUE;

        $game->setData(serialize($aData));
        $oDoctrine->getManager()->flush();
            //return new Response('debug');
        if ($gameOver)
        {

            return $this->render('@App/GameBoard/gameOver.html.twig', [
                       'game'=>$game,
                       'data'=> $aData,
               ]);

        } else {
            return $this->render('@App/GameBoard/game_brod.html.twig', [
                    'game' => $game,        
                    'dice' => $dice,
                    'pos_jeton' => $aData[$index]['position'],
                    'checkPermission' => true,
                    'data'=> $aData,
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
        
        return $this->render('@App/GameBoard/gameOver.html.twig', [
            'game'=>$game,
            'data'=> $aData,
        ]);
    }

}
