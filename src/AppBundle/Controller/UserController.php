<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


use AppBundle\Form\UserType;
use AppBundle\Form\UserLoginType;
use AppBundle\Entity\User;

class UserController extends Controller
{
    /**
     * @Route("/loginOLD", name="userLoginToutControl")
     */
    public function loginOLDAction(Request $oRequest, UserPasswordEncoderInterface $encoder)
    {
        $oUser = new User;
        
        $oForm = $this->createForm(UserLoginType::class, $oUser);

        $oForm->handleRequest($oRequest);        
        if ($oForm->isSubmitted() && $oForm->isValid()) {

            //V�rifier si l'utilisateur existe
            //
            //Chercher dans la base de donn�es            
            $oRepoUser = $this->getDoctrine()->getRepository(User::class);

            //On ne veut pas chercher tous les utilisateurs mais un seule
            $oCheckUser = $oRepoUser->findOneBy([
                    'username'   => $oUser->getUsername()
                    ]);
            
            if ($oCheckUser) {
                $isValid = $encoder->isPasswordValid($oCheckUser, $oUser->getPassword());
                
                if ($isValid) {
                    $this -> addFlash('success', 'YOUHOUU !!');

                    $oRequest->getSession()->set('utilisateur', $oCheckUser);

                    return $this->redirectToRoute('gamesList', []);
                }
            }
            
            $this -> addFlash('error', 'TU N EXISTE PAS !!');                    

            return $this->redirectToRoute('inscription');
        }
        return $this->render('@App/User/login.html.twig', [                        
                        'form'   => $oForm->createView(),
                        'user'   => $oUser
        ]);
    }
     /**
     * @Route("/", name="user_login")
     */    
    public function loginAction(AuthenticationUtils $authenticationUtils)
{
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        $oUser = new User;
        $oUser->setUsername($authenticationUtils->getLastUsername());
        $oForm = $this->createForm(UserLoginType::class, $oUser);
               
        return $this->render('@App/User/login.html.twig', [              
                            'error'  => $error,                       
                            'form'   => $oForm->createView(),
                            'user'   => $oUser,
            ]);            
    }
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscriptionAction(Request $oRequest, UserPasswordEncoderInterface $encoder)
    {
        // UTILISATION FORMULAIRE DE SYMFONY
        
        $oUser = new User;
        
        $oForm = $this -> createForm(UserType::class, $oUser);

        $oForm->handleRequest($oRequest);

        if ($oForm->isSubmitted() && $oForm->isValid()) {
            
            // pour stocker dans la base de donn�es
            $entityManager = $this->getDoctrine()->getManager();
            
            $hash = $encoder->encodePassword($oUser, $oUser->getPassword());
            
            $oUser->setPassword($hash);
            
            $entityManager->persist($oUser);
            $entityManager->flush();
            
            // Pour rediriger vers la page d'accueil
            
            $this->addFlash('success', 'Inscription REUSSIE');
          
            return $this->redirectToRoute('user_login');
        }
                
        return $this->render('@App/User/inscription.html.twig', [
                        'user'   => $oUser,
                        'form'   => $oForm->createView()
        ]);           
    }
    
    /**
    * @Route("/Oldlogout", name="oldDeconnexion")     
    */ 
    public function logoutOldAction (Request $oRequest){
        
        $oRequest->getSession()->invalidate();
        
        return $this->redirectToRoute('user_login');
                
    }    
    /**
    * @Route("/logout", name="user_logout")     
    */ 
    public function logoutAction (Request $oRequest){
        
        $oRequest->getSession()->invalidate();
        
        return $this->redirectToRoute('user_login');
                
    }
        
}
