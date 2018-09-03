<?php

namespace AppBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Game;
use Doctrine\ORM\Mapping as ORM;

class GamesListController extends Controller {
    
    /**
     * @Route("/liste_jeux", name="gamesList")
     */
    
    public function indexAction(Request $oRequest) {
        
        $oGame = new Game();
        //récupération des parties en bdd
        $oRepoGame = $this->getDoctrine()->getRepository(Game::class);
        $oCheckGame = $oRepoGame->findBy([
                'status' => 'waiting',
            ]);
        //création du formulaire de création de salon  
        $oFormCreateGame = $this->createFormBuilder($oGame)
            ->setMethod('POST')
            ->setAction($this->generateUrl('gamesList'))
            ->add('game_name', TextType::class)
            ->add('players_nb', ChoiceType::class, array(
                'choices' => array(
                    '2 joueurs' => 2,
                    '3 joueurs' => 3,
                    '4 joueurs' => 4,
                    '5 joueurs' => 5,
                    '6 joueurs' => 6,
                ),
                //'preferred_choices' => array(4), //incomplet pour que le choix 4 joueurs soit sélectionné par défaut
            ))
            ->getForm()
        ;
        // stockage du nouveau salon dans la base de données
        $oFormCreateGame->handleRequest($oRequest);
        if ( $oFormCreateGame->isSubmitted() AND $oFormCreateGame->isValid() ) {
            
            // Création de salon
            $entityManager = $this->getDoctrine()->getManager();
                       
            // Ajouer le utilisateur en cour dans la liste de joueurs       
            $oGame->addPlayer($this->getUser());
            
            $oGame->setStatus('waiting');
            
            //Création data tableau
            $tabColor =['blue', 'green', 'yellow', 'red', 'black', 'orange'];
            $aData = []; 
           $aData[] = ['player'=> $this->getUser()->getUsername(),
                'position' => 0,
               'bank' => 100,
               'turn' => true,
               'color' => $tabColor[array_rand($tabColor)],
               'finished' => false,
               ];
            //$oGame->setData(null);            
            $oGame->setData(serialize($aData));
            $entityManager->persist($oGame); // Persist c'est que pour creer nouveau element BDD
            $entityManager->flush();
            $this->addFlash('success', 'Salon CREE');
            return $this->redirectToRoute('gameboard', array('gameId'=>$oGame->getId()));
           // return $this->redirectToRoute('gameboard', ['gameId' => $oGame->getId()]);
        } else if ( $oFormCreateGame->isSubmitted() AND !$oFormCreateGame->isValid() ) {
            $this->addFlash('error', 'Erreur, veuillez recommencer');
            return $this->redirectToRoute('gamesList');
        }
        
        return $this->render('@App/GamesList/gamesList.html.twig', [
            'formCreateGame' => $oFormCreateGame->createView(),
            'games' => $oCheckGame,
        ]);
    }
    
    /**
     * @Route("/rejoindre/{gameId}", name="joinGame")
     */
    
    public function joinRoomAction(Request $oRequest, $gameId) {
             
       
        
        //récupération de la bdd
        $oDoctrine = $this->getDoctrine();
        
        //Recuperation d'objet game avec son ID
        $oGame = $oDoctrine->getRepository(Game::class)->find($gameId);
        
       //dump($this->getUser());
        
        // Ajouer le utilisateur en cour dans la liste de joueurs       
        $oGame->addPlayer($this->getUser());
        $aData = unserialize($oGame->getData());
        $stop = false;
        $aColorPlayers=[];
        
         $tabColor =['blue', 'green', 'yellow', 'red', 'black', 'orange'];
        
        foreach ($aData as $ligne_joueur) {
            
           $aColorPlayers[] =  $ligne_joueur['color'];  
        }
            
        do {
            
           $couleur =  $tabColor[array_rand($tabColor)];
           
                   
        } while( in_array($couleur, $aColorPlayers) );
                
                                
         $aData[] = ['player'=> $this->getUser()->getUsername(),
                'position' => 0,
               'bank' => 100,
               'turn' => false,
               'color' => $couleur,
               'finished' => false,
               ];
        //Mise à jour de la BDD
          $oGame->setData(serialize($aData));
        $oDoctrine->getManager()->flush();
        
        //////////////////////////
        //dumper less players..        
        //$oDoctrine = $this->getDoctrine();        
        //Recuperation d'objet game avec son ID
        //$oGame = $oDoctrine->getRepository(Game::class)->find($gameId);
        //dump(count($oGame->getPlayers()));
        //////////////////////////////
         
        //passer peut-être l'id dans le return*/
        return $this->redirectToRoute('gameboard', array('gameId'=>$gameId));
    
        /*return $this->render('@App/GameBoard/game_brod.html.twig', [
            'game'=>$oGame,
            'pos_jeton' => 0]);*/
    }    
}
