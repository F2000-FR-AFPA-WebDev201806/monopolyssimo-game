<?php

namespace AppBundle\Controller;

/**
 * Description of HomeController
 *
 * @author Zensaikeunde
 */

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
    
    public function indexAction() {
        
        //créer des liens href ?
        
        //récupération des parties en bdd
        $oRepoGame = $this->getDoctrine()->getRepository(Game::class);
        $oCheckGame = $oRepoGame->findBy([
                'status' => 'waiting',
            ]);
        dump($oCheckGame);
        //création du formulaire de création de salon
        $oFormCreateGame = $this->createFormBuilder($oCheckGame)
            //->setAction($this->generateUrl('validation'))
            ->setMethod('POST')
            ->add('game_name', TextType::class)
            ->add('players_nb', ChoiceType::class, array(
                'choices' => array(
                    '2 joueurs' => 2,
                    '3 joueurs' => 3,
                    '4 joueurs' => 4,
                    '5 joueurs' => 5,
                    '6 joueurs' => 6,
                ),
                //'preferred_choices' => array(4), //pour que le choix 4 joueurs soit sélectionné par défaut
            ))
            ->getForm()
        ;
        //création du formulaire pour rejoindre un salon
        $oFormJoinGame = $this->createFormBuilder()
            //->setAction($this->generateUrl('validation'))
            ->setMethod('POST')
            ->getForm()
        ;
        //return new Response('suite à faire');
        return $this->render('@App/GamesList/gamesList.html.twig', [
            'formCreateGame' => $oFormCreateGame->createView(),
            'formJoinGame' => $oFormJoinGame->createView(),
            'games' => $oCheckGame,
        ]);
    }
    
    /**
     * @Route("/creer", name="createGame")
     */
    
    public function createRoomAction(Request $oRequest) {
        
        return $this->redirectToRoute('gameboard');
    }
    
    /**
     * @Route("/rejoindre", name="joinGame")
     */
    
    public function joinRoomAction(Request $oRequest) {
        return new Response('A toi la suite du boulot Ryiadh !');
        //return $this->redirectToRoute('gameboard');
    }
}
