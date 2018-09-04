[1mdiff --git a/src/AppBundle/Controller/GameBoardController.php b/src/AppBundle/Controller/GameBoardController.php[m
[1mindex 745c8e6..8b953c7 100644[m
[1m--- a/src/AppBundle/Controller/GameBoardController.php[m
[1m+++ b/src/AppBundle/Controller/GameBoardController.php[m
[36m@@ -16,166 +16,214 @@[m [mclass GameBoardController extends Controller {[m
      */[m
     public function gameboardShowAction(Request $request, $gameId) {[m
 [m
[31m-        // tester si le nb_player est bon[m
[31m-        $game = $this->getDoctrine()[m
[31m-                ->getRepository(Game::class)[m
[31m-                ->find($gameId)[m
[31m-        ;[m
[31m-[m
[31m-//        dump($game->getPlayersNb());[m
[31m-//[m
[31m-//        foreach ($game->getPlayers() as $player) {[m
[31m-//[m
[31m-//            dump($player);[m
[31m-//        }[m
[32m+[m[32m        $oDoctrine = $this->getDoctrine();[m
[32m+[m
[32m+[m[32m        //Recuperation d'objet game avec son ID[m
[32m+[m[32m        $game = $oDoctrine->getRepository(Game::class)->find($gameId);[m
[32m+[m
         $checkPermission = false;[m
         if ($game->getPlayersNb() == count($game->getPlayers())) {[m
 [m
             $checkPermission = true;[m
[31m-            [m
[31m-        } [m
[32m+[m[32m            $game->setStatus('running');[m
[32m+[m[32m            $oDoctrine->getManager()->flush();[m
[32m+[m[32m        }[m
[32m+[m[32m        //jet dé automatique[m
[32m+[m[32m        //$this->launchDiceAction($request, $gameId);[m
 [m
         return $this->render('@App/GameBoard/game_brod.html.twig', [[m
                     'game' => $game,[m
                     'pos_jeton' => 0,[m
                     'checkPermission' => $checkPermission,[m
[31m-                    'data'=> unserialize($game->getData())[m
[32m+[m[32m                    'data' => unserialize($game->getData())[m
         ]);[m
     }[m
 [m
[31m-    public function cm_decroissant ($a, $b) {[m
[31m-        if ($a['bank']<$b['bank']) {[m
[32m+[m[32m    public function cm_decroissant($a, $b) {[m
[32m+[m[32m        if ($a['bank'] > $b['bank']) {[m
             return -1;[m
         }[m
[31m-        if ($a['bank']>$b['bank']) {[m
[32m+[m[32m        if ($a['bank'] < $b['bank']) {[m
             return 1;[m
         }[m
[31m-        if ($a['bank']==$b['bank']) {[m
[32m+[m[32m        if ($a['bank'] == $b['bank']) {[m
             return 0;[m
         }[m
     }[m
[31m-        [m
[32m+[m
     /**[m
      * @Route("/jet_des/{gameId}", name="launchDice")[m
      */[m
     public function launchDiceAction(Request $request, $gameId) {[m
[31m-        [m
[31m-        [m
[32m+[m
         $oDoctrine = $this->getDoctrine();[m
[31m-        [m
[32m+[m
         //Recuperation d'objet game avec son ID[m
         $game = $oDoctrine->getRepository(Game::class)->find($gameId);[m
[31m-        [m
[32m+[m
         $aData = unserialize($game->getData());[m
[31m-        [m
[31m-        $dice = rand(20, 30);[m
[31m-         // recupere la ligne du joeur concerné [m
[31m-        [m
[32m+[m
[32m+[m[32m        // recupere la ligne du joeur concerné[m[41m [m
[32m+[m
         foreach ($aData as $key => $ligne_joueur) {[m
             //$index = $key;[m
             //dump($ligne_joueur['player']);[m
[31m-            [m
[31m-            if ($ligne_joueur['player'] == $this->getUser()->getUsername() ){[m
[31m-                     [m
[32m+[m
[32m+[m[32m            if ($ligne_joueur['player'] == $this->getUser()->getUsername()) {[m
[32m+[m
                 $index = $key;[m
[31m-             [m
             }[m
         }[m
[32m+[m
[32m+[m[32m        /*         * * VERIFICATION DU TOUR DE JOUEUR ***** */[m
[32m+[m
[32m+[m[32m        dump($aData[$index]['turn']);[m
[32m+[m[32m        dump($this->getUser()->getUsername());[m
[32m+[m
[32m+[m[32m        if ($aData[$index]['turn']) {[m
[32m+[m
[32m+[m[32m            /*             * ***lancer le dés****** */[m
[32m+[m[32m            $dice = rand(10, 20);[m
[32m+[m[32m            /*             * **gerer la position du jeton*** */[m
[32m+[m[32m            $aData[$index]['position'] = $aData[$index]['position'] + $dice;[m
[32m+[m
[32m+[m[32m            /*             * * Vérification de fin de partie de joueur qui a lancé le dés ** */[m
[32m+[m[32m            $gameOver = false; /*             * * Joueur a fini ** */[m
             [m
[31m-        $gameOver=false;[m
[31m-        $aData[$index]['position'] = $aData[$index]['position']+$dice;[m
[31m-[m
[31m-        if ($aData[$index]['position'] > 39) {[m
[31m-            $aData[$index]['finished'] = true;[m
[31m-            $gameOver=true;[m
[31m-[m
[31m-            //verifier si tt l emonde a fini[m
[31m-            $stop=true;[m
[31m-            for ($i=0; $i<count($aData);$i++) {[m
[31m-                if (!$aData[$i]['finished']) {[m
[31m-                    $stop=false;[m
[31m-                    //status=>finished[m
[32m+[m[32m            // le joueur actif à fini son tour[m[41m [m
[32m+[m[32m            $aData[$index]['turn'] = FALSE;[m
[32m+[m[32m            // on vérifie si le joueur à fini la partie[m
[32m+[m[32m            if ($aData[$index]['position'] > 39) {[m
[32m+[m[32m                // Si la position est supérieur à 39 le joueur à fini la partie[m
[32m+[m[32m                $aData[$index]['finished'] = true;[m
[32m+[m[41m                              [m
[32m+[m[32m                $gameOver = true;[m
[32m+[m[32m                // On va vérifier si les autres joueurs ont aussi fini[m
[32m+[m[32m                $stop = true; //verifier si tt l emonde a fini[m
[32m+[m[32m                for ($i = 0; $i < count($aData); $i++) {[m
[32m+[m[32m                    if (!$aData[$i]['finished']) {[m
[32m+[m[32m                        $stop = false;[m
[32m+[m[32m                        $game->setStatus('finished');[m
[32m+[m[32m                    }[m
                 }[m
[31m-            }[m
[31m-            if ($stop) {[m
[31m-                $game->setData(serialize($aData));[m
[31m-                $oDoctrine->getManager()->flush();[m
[31m-                [m
[31m-                //dump(array_multisort($aData,  SORT_DESC, 'bank'));[m
[31m-                uasort($aData, [$this, 'cm_decroissant']);[m
[31m-                [m
[31m-                dump($aData);[m
[32m+[m[32m                /*** si tt le monde a fini ***/[m
[32m+[m[32m                if ($stop) {[m
[32m+[m[32m                    $game->setData(serialize($aData));[m
[32m+[m[32m                    $oDoctrine->getManager()->flush();[m
[32m+[m[32m                    return $this->redirectToRoute('classement', array('gameId' => $game->getId()));[m
[32m+[m[32m                }[m
[32m+[m[32m                /*** Si le joueur n'a pas fini la partie ***/[m
[32m+[m[32m            } else {[m
[32m+[m[32m                //recuprer la cagnotte[m
[32m+[m[32m                //$key = array_search($aData[$index]['position'], \AppBundle\Model\Board::BOARD);[m[41m [m
[32m+[m[32m                $key = 0;[m
[32m+[m[32m                // Recuperation de la casse correspondant à la position dans le tableau BOARD[m
[32m+[m[32m                while (\AppBundle\Model\Board::BOARD[$key]['index'] != $aData[$index]['position']) {[m
[32m+[m
[32m+[m[32m                    $key++;[m
[32m+[m[32m                }[m
[32m+[m[32m                /*** mettre a jour la cagnotte ***/[m
[32m+[m[32m                $aData[$index]['bank'] = $aData[$index]['bank'] + \AppBundle\Model\Board::BOARD[$key]['valeur'];[m
[32m+[m[32m                /*** desactivation du tour du joueur ***/[m
                 [m
[31m-                return $this->render('@App/GameBoard/gameOver.html.twig', [[m
[31m-                   'game'=>$game,[m
[31m-                   'data'=> $aData,[m
[31m-                ]);[m
[31m-[m
             }[m
 [m
[31m-        } else {[m
[32m+[m[32m            /***donner le tour au prochaine joueur qui peut jouer***/[m
 [m
[31m-            //recuprer la cagnotte[m
[31m-            //$key = array_search($aData[$index]['position'], \AppBundle\Model\Board::BOARD); [m
[31m-           $key=0; [m
[31m-           while ( \AppBundle\Model\Board::BOARD[$key]['index'] != $aData[$index]['position']) {[m
[32m+[m[32m            $nextPlayer = ($index + 1) % count($aData);[m
 [m
[31m-                $key++;[m
[32m+[m[32m            while ($aData[$nextPlayer]['finished']) {[m
[32m+[m[32m                $nextPlayer = ($nextPlayer + 1) % count($aData);[m
[32m+[m[32m            }[m
 [m
[31m-           }[m
[32m+[m[32m            $aData[$nextPlayer]['turn'] = TRUE;[m
[32m+[m[41m            [m
 [m
[31m-           $aData[$index]['bank']=$aData[$index]['bank']+ \AppBundle\Model\Board::BOARD[$key]['valeur'];[m
[31m-           $aData[$index]['turn'] = FALSE;[m
[32m+[m[32m            $game->setData(serialize($aData));[m
[32m+[m[32m            $oDoctrine->getManager()->flush();[m
[32m+[m[32m            //return new Response('debug');[m
[32m+[m[32m            if ($gameOver) {[m[41m                [m
[32m+[m[32m                return $this->redirectToRoute('gameOver', array('gameId' => $game->getId()));[m
[32m+[m[41m                [m
[32m+[m[32m            } else {[m
[32m+[m[32m                return $this->render('@App/GameBoard/game_brod.html.twig', [[m
[32m+[m[32m                            'game' => $game,[m
[32m+[m[32m                            'dice' => $dice,[m
[32m+[m[32m                            'pos_jeton' => $aData[$index]['position'],[m
[32m+[m[32m                            'checkPermission' => true,[m
[32m+[m[32m                            'data' => $aData,[m
[32m+[m[32m                ]);[m
[32m+[m[32m            }[m
[32m+[m[32m            /*** Si c'est pas le tour du joueur => ON RETOURNE AU PLATEAU ***/[m
[32m+[m[32m            // Pour gérer problème de rafraîchissement de la page en JavaScript[m
[32m+[m[32m        } else {[m
 [m
[31m-       }[m
[32m+[m[32m            dump($aData);[m
 [m
[31m-       $nextPlayer = ($index+1) % count($aData);[m
[32m+[m[32m            return $this->render('@App/GameBoard/game_brod.html.twig', [[m
[32m+[m[32m                        'game' => $game,[m
[32m+[m[32m                        'dice' => null,[m
[32m+[m[32m                        'pos_jeton' => $aData[$index]['position'],[m
[32m+[m[32m                        'checkPermission' => true,[m
[32m+[m[32m                        'data' => $aData,[m
[32m+[m[32m            ]);[m
[32m+[m[32m        }[m
[32m+[m[32m    }[m
 [m
[32m+[m[32m    /**[m
[32m+[m[32m     * @Route("game_over/{gameId}", name="gameOver")[m
[32m+[m[32m     */[m
[32m+[m[32m    public function gameOverAction(Request $request, $gameId) {[m
 [m
[31m-       while ($aData[$nextPlayer]['finished']){[m
[31m-           $nextPlayer = ($nextPlayer+1)% count($aData);[m
[31m-       }[m
[32m+[m[32m        $oDoctrine = $this->getDoctrine();[m
 [m
[31m-       $aData[$nextPlayer]['turn'] = TRUE;[m
[32m+[m[32m        //Recuperation d'objet game avec son ID[m
[32m+[m[32m        $game = $oDoctrine->getRepository(Game::class)->find($gameId);[m
 [m
[31m-        $game->setData(serialize($aData));[m
[31m-        $oDoctrine->getManager()->flush();[m
[31m-            //return new Response('debug');[m
[31m-        if ($gameOver)[m
[31m-        {[m
[32m+[m[32m        $aData = unserialize($game->getData());[m
 [m
[31m-            return $this->render('@App/GameBoard/gameOver.html.twig', [[m
[31m-                       'game'=>$game,[m
[31m-                       'data'=> $aData,[m
[31m-               ]);[m
[32m+[m[32m        $stop = true; //verifier si tt l emonde a fini[m
[32m+[m[32m        for ($i = 0; $i < count($aData); $i++) {[m
[32m+[m[32m            if (!$aData[$i]['finished']) {[m
[32m+[m[32m                $stop = false;[m
[32m+[m[32m                $game->setStatus('finished');[m
[32m+[m[32m            }[m
[32m+[m[32m        }[m
[32m+[m[32m        /*         * * si tt le monde a fini** */[m
[32m+[m[32m        if ($stop) {[m
 [m
[32m+[m[32m            return $this->redirectToRoute('classement', array('gameId' => $game->getId()));[m
         } else {[m
[31m-            return $this->render('@App/GameBoard/game_brod.html.twig', [[m
[31m-                    'game' => $game,        [m
[31m-                    'dice' => $dice,[m
[31m-                    'pos_jeton' => $aData[$index]['position'],[m
[31m-                    'checkPermission' => true,[m
[31m-                    'data'=> $aData,[m
[32m+[m
[32m+[m[32m            return $this->render('@App/GameBoard/gameOver.html.twig', [[m
[32m+[m[32m                        'game' => $game,[m
[32m+[m[32m                        'data' => $aData,[m
             ]);[m
[31m-                [m
         }[m
     }[m
 [m
     /**[m
[31m-     * @Route("game_over/{gameId}", name="gameOver")[m
[32m+[m[32m     * @Route("classement/{gameId}", name="classement")[m
      */[m
[31m-    public function gameOverAction(Request $request, $gameId) {[m
[32m+[m[32m    public function classementAction(Request $request, $gameId) {[m
 [m
         $oDoctrine = $this->getDoctrine();[m
[31m-        [m
[32m+[m
         //Recuperation d'objet game avec son ID[m
         $game = $oDoctrine->getRepository(Game::class)->find($gameId);[m
[31m-        [m
[32m+[m
         $aData = unserialize($game->getData());[m
[31m-        [m
[31m-        return $this->render('@App/GameBoard/gameOver.html.twig', [[m
[31m-            'game'=>$game,[m
[31m-            'data'=> $aData,[m
[32m+[m
[32m+[m
[32m+[m[32m        dump($aData);[m
[32m+[m[32m        // Classement final[m
[32m+[m[32m        uasort($aData, [$this, 'cm_decroissant']);[m
[32m+[m
[32m+[m[32m        dump($aData);[m
[32m+[m
[32m+[m[32m        return $this->render('@App/GameBoard/classement.html.twig', [[m
[32m+[m[32m                    'game' => $game,[m
[32m+[m[32m                    'data' => $aData,[m
         ]);[m
     }[m
 [m
[1mdiff --git a/src/AppBundle/Model/Board.php b/src/AppBundle/Model/Board.php[m
[1mindex 6afba4f..1262250 100644[m
[1m--- a/src/AppBundle/Model/Board.php[m
[1m+++ b/src/AppBundle/Model/Board.php[m
[36m@@ -46,8 +46,8 @@[m [mclass Board {[m
         36 => Array('index' => 3, 'valeur' => 1),[m
         37 => Array('index' => 2, 'valeur' => 50),[m
         38 => Array('index' => 1, 'valeur' => 1),[m
[31m-        39 => Array('index' => 33, 'valeur' => 50),[m
[31m-        40 => Array('index' => 0, 'valeur' => 50),[m
[32m+[m[32m        39 => Array('index' => 0, 'valeur' => 50),[m
[32m+[m[41m        [m
     );[m
 [m
     public $grid = [];[m
[1mdiff --git a/src/AppBundle/Resources/views/GameBoard/gameOver.html.twig b/src/AppBundle/Resources/views/GameBoard/gameOver.html.twig[m
[1mindex ebd8522..38fef3d 100644[m
[1m--- a/src/AppBundle/Resources/views/GameBoard/gameOver.html.twig[m
[1m+++ b/src/AppBundle/Resources/views/GameBoard/gameOver.html.twig[m
[36m@@ -3,31 +3,42 @@[m
 [m
 {% block body %}[m
     <h1>Game Over !!!!</h1>[m
[31m-    [m
[32m+[m
     <ul>[m
         {{dump (data)}}[m
         {% set gameover = true %}[m
         {% for joueur in data %}[m
             {% if joueur.finished %}[m
                 <li> {{ joueur.player }}--{{ joueur.bank }}</li>[m
[31m-            {% else  %}[m
[31m-                {% set gameover = false %}[m
[31m-            {% endif %}[m
[31m-        {% endfor %}[m
[31m-    </ul>[m
[31m-        [m
[31m-       [m
[31m-        {% if not gameover %}[m
[31m-        <a href="{{ path('gameOver', {'gameId' : game.id})}}" class="btn btn-primary">[m
[31m-                        Actualisez ...[m
[31m-       </a>[m
[31m-        {% else %}[m
[31m-            <h2>Classement final</h2>[m
[31m-            {% for joueur in data %}[m
[31m-            [m
[31m-                <li> {{ joueur.player }}--{{ joueur.bank }}</li>[m
[31m-            [m
[32m+[m[32m                {% else  %}[m
[32m+[m[32m                    {% set gameover = false %}[m
[32m+[m[32m                {% endif %}[m
             {% endfor %}[m
[31m-        {%endif %}[m
[31m-        [m
[32m+[m[32m    </ul>[m
[32m+[m
[32m+[m[32m{% endblock %}[m
[32m+[m[32m{% block javascripts %}[m
[32m+[m[32m    <script>[m
[32m+[m[32m        {#{% include asset('bundles/app/JS/ajax.js') %}#}[m
[32m+[m
[32m+[m[32m             $(document).ready(function () {[m
[32m+[m[32m                 console.log('READY TO GO !');[m
[32m+[m
[32m+[m[32m                 /*    $.ajax({[m
[32m+[m[32m                  async   : true,[m
[32m+[m[32m                  type    : 'GET',[m
[32m+[m[32m                  url     : 'ajax.php', //[m
[32m+[m[32m                  data    : 'numPage=' + $(this).attr('data-page'),[m
[32m+[m[32m                  success : function(data){[m
[32m+[m[32m                  $('main').html(data);[m
[32m+[m[32m                  }[m[41m [m
[32m+[m[32m                  });*/[m
[32m+[m[32m                 function refresh() {[m
[32m+[m[32m                     //location.reload(true);[m
[32m+[m[32m                     window.location = '{{ path('gameOver', {'gameId' : game.id}) }}';[m
[32m+[m[32m                 }[m
[32m+[m[32m                 setInterval(refresh, 5000);[m
[32m+[m[32m             });[m
[32m+[m
[32m+[m[32m    </script>[m
 {% endblock %}[m
\ No newline at end of file[m
[1mdiff --git a/src/AppBundle/Resources/views/GameBoard/game_brod.html.twig b/src/AppBundle/Resources/views/GameBoard/game_brod.html.twig[m
[1mindex 8026ae4..f9cb291 100644[m
[1m--- a/src/AppBundle/Resources/views/GameBoard/game_brod.html.twig[m
[1m+++ b/src/AppBundle/Resources/views/GameBoard/game_brod.html.twig[m
[36m@@ -11,13 +11,14 @@[m
                         {% for j in 0..10 %}[m
                             <td class="case_game">[m
                                 <img src="{{ asset('bundles/app/images/' ~ constant('\\AppBundle\\Model\\Board::BOARD')[num_case].valeur ~ '.jpg') }}"/>[m
[31m-                                {% if constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index == pos_jeton %}[m
[31m-                                    <img src="{{ asset('bundles/app/images/jetons/rouge.jpg')}}"/>[m
[31m-                                {% endif %}[m
[32m+[m[41m                                [m
                                 {#  couleur joueurs#}[m
                                                                   [m
                                     {% for joueur in data %}[m
[32m+[m[32m                                        {# tester si le joeur est dans la ces #}[m
[32m+[m[32m                                        {% if joueur.position == constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index%}[m
                                         <img class="jetons" src=" {{ asset('bundles/app/images/jetons/'~joueur.color~'.jpg') }}" />[m
[32m+[m[32m                                        {% endif %}[m
                                     {% endfor %}[m
                                 [m
                             </td>[m
[36m@@ -28,9 +29,14 @@[m
                         <tr>[m
                             <td class="case_game">[m
                                 <img src="{{ asset('bundles/app/images/' ~ constant('\\AppBundle\\Model\\Board::BOARD')[num_case].valeur ~ '.jpg') }}"/>[m
[31m-                                {% if constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index == pos_jeton %}[m
[31m-                                    <img src="{{ asset('bundles/app/images/jetons/rouge.jpg')}}"/>[m
[31m-                                {% endif %}[m
[32m+[m[41m                               [m
[32m+[m[32m                                {% for joueur in data %}[m
[32m+[m[32m                                        {# tester si le joeur est dans la ces #}[m
[32m+[m[32m                                        {% if joueur.position == constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index%}[m
[32m+[m[32m                                        <img class="jetons" src=" {{ asset('bundles/app/images/jetons/'~joueur.color~'.jpg') }}" />[m
[32m+[m[32m                                        {% endif %}[m
[32m+[m[32m                                    {% endfor %}[m
[32m+[m[41m                                    [m
                             </td>[m
                             {% set num_case=num_case+1 %}[m
                             {% for j in 0..8 %}[m
[36m@@ -38,9 +44,14 @@[m
                             {% endfor %}[m
                             <td class="case_game">[m
                                 <img src="{{ asset('bundles/app/images/' ~ constant('\\AppBundle\\Model\\Board::BOARD')[num_case].valeur ~ '.jpg') }}"/>[m
[31m-                                {% if constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index == pos_jeton %}[m
[31m-                                    <img src="{{ asset('bundles/app/images/jetons/rouge.jpg')}}"/>[m
[31m-                                {% endif %}[m
[32m+[m[41m                                [m
[32m+[m[32m                                {% for joueur in data %}[m
[32m+[m[32m                                        {# tester si le joeur est dans la ces #}[m
[32m+[m[32m                                        {% if joueur.position == constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index%}[m
[32m+[m[32m                                        <img class="jetons" src=" {{ asset('bundles/app/images/jetons/'~joueur.color~'.jpg') }}" />[m
[32m+[m[32m                                        {% endif %}[m
[32m+[m[32m                                    {% endfor %}[m
[32m+[m[41m                                    [m
                             </td>[m
                             {% set num_case=num_case+1 %}[m
                         </tr>[m
[36m@@ -49,17 +60,26 @@[m
                         {% for j in 0..9 %}[m
                             <td class="case_game">[m
                                 <img src="{{ asset('bundles/app/images/' ~ constant('\\AppBundle\\Model\\Board::BOARD')[num_case].valeur ~ '.jpg') }}"/>[m
[31m-                                {% if constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index == pos_jeton %}[m
[31m-                                    <img src="{{ asset('bundles/app/images/jetons/rouge.jpg')}}"/>[m
[31m-                                {% endif %}[m
[32m+[m[41m                                [m
[32m+[m[32m                                {% for joueur in data %}[m
[32m+[m[32m                                        {# tester si le joeur est dans la ces #}[m
[32m+[m[32m                                        {% if joueur.position == constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index%}[m
[32m+[m[32m                                        <img class="jetons" src=" {{ asset('bundles/app/images/jetons/'~joueur.color~'.jpg') }}" />[m
[32m+[m[32m                                        {% endif %}[m
[32m+[m[32m                                    {% endfor %}[m
[32m+[m[41m                                    [m
                             </td>[m
                             {% set num_case=num_case+1 %}[m
                         {% endfor %}[m
                         <td class="case_game">[m
                             DEPART[m
[31m-                            {% if constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index == pos_jeton %}[m
[31m-                                <img src="{{ asset('bundles/app/images/jetons/rouge.jpg')}}"/>[m
[31m-                            {% endif %}[m
[32m+[m[32m                                    {% for joueur in data %}[m
[32m+[m[32m                                        {# tester si le joeur est dans la ces #}[m
[32m+[m[32m                                        {% if joueur.position == constant('\\AppBundle\\Model\\Board::BOARD')[num_case].index%}[m
[32m+[m[32m                                        <img class="jetons" src=" {{ asset('bundles/app/images/jetons/'~joueur.color~'.jpg') }}" />[m
[32m+[m[32m                                        {% endif %}[m
[32m+[m[32m                                    {% endfor %}[m
[32m+[m[41m                                    [m
                         </td>[m
                     </tr>[m
                 </table>[m
[36m@@ -124,9 +144,10 @@[m
                 } [m
             });*/[m
             function refresh() {[m
[31m-               location.reload(true);[m
[32m+[m[32m               //location.reload(true);[m
[32m+[m[32m               window.location = '{{ path('gameboard', {'gameId' : game.id}) }}';[m
             }[m
[31m-            setInterval(refresh, 5000);[m
[32m+[m[32m            //setInterval(refresh, 5000);[m
         });[m
         [m
         </script>[m
[36m@@ -147,7 +168,7 @@[m
             }[m
             [m
             .jetons {[m
[31m-                 width: 10px;[m
[32m+[m[32m                 width: 30px;[m
                  height: auto;[m
             }[m
             body { background: #F5F5F5; font: 18px/1.5 sans-serif; }[m
