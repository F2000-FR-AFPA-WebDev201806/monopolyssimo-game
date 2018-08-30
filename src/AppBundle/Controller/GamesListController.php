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
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($oGame);
            $entityManager->flush();
            $this->addFlash('success', 'Salon CREE');
            return $this->redirectToRoute('gameboard', ['gameId' => $oGame->getId()]);
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
     * @Route("/rejoindre", name="joinGame")
     */
    
    public function joinRoomAction() {
        //passer peut-être l'id dans le return*/
        return $this->redirectToRoute('gameboard');
    }
}
