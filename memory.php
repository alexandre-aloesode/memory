<?php

// Pour éviter l'erreur "PHP CLASS INCOMPLETE" je dois require ma class avant de démarrer ma session.
    require './Classes/Card.php';

    require './Classes/Game.php';

    session_id() == '' ? session_start() : null ;

    if(isset($_POST['level'])) {

        $_SESSION['level'] = $_POST['level'];
    }

    if(isset($_SESSION['level'])) {

        if(!isset($_SESSION['turn']) || isset($_POST['reset'])) {

            $game = new Game($_SESSION['level']);

            $game = $game->select_pairs();

// Pour la suite du jeu j'ai besoin de conserver l'ordre de distribution. Quand la partie commence je crée donc $_SESSION[game] qui comprend toutes mes cartes
// Dans le construct de chaque carte j'ai ajouté un push automatique dans cette variable.

            $_SESSION['game'] = [];
                
            $card1 = new Card($game[0]);

            $card2 = new Card($game[1]);

            $card3 = new Card($game[2]);

            $card4 = new Card($game[3]);

            $card5 = new Card($game[4]);

            $card6 = new Card($game[5]);

            $card7 = new Card($game[6]);

            $card8 = new Card($game[7]);

            $card9 = new Card($game[8]);

            $card10 = new Card($game[9]);

            $card11 = new Card($game[10]);
                    
            $card12 = new Card($game[11]);

            if($_SESSION['level'] == 'Intermediaire' || $_SESSION['level'] == 'Expert') {

                $card13 = new Card($game[12]);
                $card14 = new Card($game[13]);
                $card15 = new Card($game[14]);
                $card16 = new Card($game[15]);
            }

            if($_SESSION['level'] == 'Expert') {

                $card17 = new Card($game[16]);
                $card18 = new Card($game[17]);
                $card19 = new Card($game[18]);
                $card20 = new Card($game[19]);
            }
        
// Au démarrage du jeu je crée 5 variables de session.
    // -turn qui me permet de compter les coups et me sert à la génération des cartes
    // -found_cards: tableau vide dans lequel je stocke les paires trouvées et me permet de les garder retournées pendant le reste de la partie 
    // -chosen_cards: tableau vide dans lequel je stocke les 2 cartes que l'utilisateur retourne. Je reset le tableau tous les 2 tours/cliques
    // -last_round_cards: dans mon code, 1 tour = 2 cliques donc 2 cartes, sauf que la deuxième ne s'affichait pas s'il n'y avait pas de paire trouvée. 
    // Je stocke donc les 2 cartes du tour précédent dans cette variable pour que l'utilisateur puisse les voir même s'il n'a pas trouvé là paire.
    // Au prochain clique les 2 cartes redeviennent retournées.
    // -remaining_cards: je stocke l'attribut name de chaque carte dans ce tableau, et dès qu'une paire est trouvée je retire les 2 cartes de ce tableau. 
    //  Quand il est vide ça déclenche ma condition de victoire

            $_SESSION['turn'] = 0 ;

            $_SESSION['found_cards'] = [];

            $_SESSION['chosen_cards'] = [];

            $_SESSION['last_round_cards'] = [];

            $_SESSION['remaining_cards'] = [];

            for($x = 0; isset($_SESSION['game'][$x]); $x++) {

                array_push($_SESSION['remaining_cards'], $_SESSION['game'][$x]->name);
            }
        }

        if(isset($_POST['reset'])) {

            $_SESSION['turn'] = 0;

            $_SESSION['found_cards'] = [];

            $_SESSION['chosen_cards'] = [];     

            $_SESSION['last_round_cards'] = [];

            $_SESSION['remaining_cards'] = [];

            for($x = 0; isset($_SESSION['game'][$x]); $x++) {

                array_push($_SESSION['remaining_cards'], $_SESSION['game'][$x]->name);
            }

            header('Location: memory.php');
        }

// quand l'utilisateur clique sur une carte, le POST me récupère l'attribut name que j'ajoute dans mon tableau stockant les 2 cartes sélectionnées dans le tour en cours
// et j'incrémente de 1 mon $_SESSION[turn]
        if(isset($_POST['card'])) {

            array_push($_SESSION['chosen_cards'], $_POST['card']);

            $_SESSION['turn'] ++;

        }

// Tous les 2 tours, je check si les 2 cartes sélectionnées font une paire. Vu que les cartes ont un attribut name suivi d'un numéro, 1 ou 2,
// je dois retirer le numéro pour faire ma comparaison. J'ai donc 2 variables, card1 et card2, et la fonction substr me permet d'enlever le numéro du name.
// Avant ça, j'ajoute les 2 cartes du tour en cours dans last_round_cards pour que l'utilisateur puisse les voir même s'il n'a pas trouvé de paire
// Si une paire a été trouvée, je la rajoute dans le tableau des found_cards et supprime les 2 cartes du tableau remaining_cards.
// Enfin, je vide le tableau chosen_cards pour démarrer un nouveau tour.

        if(isset($_POST['card']) && $_SESSION['turn'] % 2 == 0 && $_SESSION['turn'] > 0) {

            array_push($_SESSION['last_round_cards'], $_SESSION['chosen_cards'][0]);
            array_push($_SESSION['last_round_cards'], $_SESSION['chosen_cards'][1]);

            $card1 = substr($_SESSION['chosen_cards'][0], 0 , -1);

            $card2 = substr($_SESSION['chosen_cards'][1], 0 , -1);

            if($card1 == $card2) {

                array_push($_SESSION['found_cards'], $_SESSION['chosen_cards'][0]);

                array_push($_SESSION['found_cards'], $_SESSION['chosen_cards'][1]);
                
                if($_SESSION['remaining_cards'] !== 'win') {
                    $_SESSION['remaining_cards'] = array_filter($_SESSION['remaining_cards'], static function ($element) {
                        return $element !== $_SESSION['chosen_cards'][0]; });
                    
                    $_SESSION['remaining_cards'] = array_filter($_SESSION['remaining_cards'], static function ($element) {
                        return $element !== $_SESSION['chosen_cards'][1]; });
                }
            // unset($_SESSION['remaining_cards'][$_SESSION['chosen_cards'][0]]);
            // unset($_SESSION['remaining_cards'][$_SESSION['chosen_cards'][1]]);
            }

            $_SESSION['chosen_cards'] = [];
        }
    
//A chaque tour impair, je check si le tableau des last_round_cards contient quelque chose, et si c'est le cas je le vide.
        if($_SESSION['turn'] % 2 !== 0 && $_SESSION['turn'] > 0 && !empty($_SESSION['last_round_cards'])) {

            $_SESSION['last_round_cards'] = [];
        }

// Ma condition de victoire

        if(empty($_SESSION['remaining_cards']) && isset($_POST['card'])) {
        
            if(isset($_SESSION['user'])) {

            require './Classes/User.php';

            $user = new User();

            $user->save_game($_SESSION['level'], $_SESSION['turn'], 'OUI');

//Ci_dessous je change la valeur de remaining_cards car si elle reste la même et l'utilisateur refresh sa page après avoir gagné, ça continue d'update ses stats.
//Je réutilise également le 'win' pour mes conditions d'affichage plus bas dans le code.
            $_SESSION['remaining_cards'] = 'win';

            }
        }
    }
    
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="formulaires.css" rel = "stylesheet">
    <link href="index.css" rel = "stylesheet">
    <link href="header.css" rel = "stylesheet">
    <link href="footer.css" rel = "stylesheet">
    <link href="memory.css" rel = "stylesheet">
    <title>index</title>
</head>

<body>

    <?php include 'header.php'?>

    <main>

        <div id="game">

            <div id="count">

                <p>Nombre de coups :</p>

                <p> <?= isset($_SESSION['turn']) ? $_SESSION['turn'] : null ?> </p>

            </div>

            <?php 


                require './Classes/Form.php'; 

                $form = new Form();

                echo $form->start_form('post');

                    if(!isset($_SESSION['level'])) {

                        $form_level = new Form();

                        echo $form_level->start_form_with_id('post' , 'form_level');

                            echo $form_level->text('h3', 'Bienvenue, à quel niveau souhaitez-vous jouer?');

                            echo $form_level->button_with_class_and_value('level', 'level_button', 'Novice');

                            echo $form_level->button_with_class_and_value('level', 'level_button', 'Intermediaire');

                            echo $form_level->button_with_class_and_value('level', 'level_button', 'Expert');

                        echo $form_level->end_form();
                    }

                    elseif(isset($_SESSION['level'])) {

                    if($_SESSION['remaining_cards'] !== 'win') {


                        if($_SESSION['turn'] == 0) {   

                            // $_SESSION['game'] = [$card1, $card2, $card3, $card4, $card5, $card6, $card7, $card8, $card9, $card10, $card11, $card12];

                            for($x = 0; isset($_SESSION['game'][$x]); $x++) {

                                echo $_SESSION['game'][$x]->display_back();
                                    
                            }
                        }

// Pour le reste de la partie, grâce à $_SESSION[game] j'ai mon ordre de distribution conservé. J'ai donc juste à boucler dessus pour afficher les cartes.
// Si la carte fait partie d'une paire trouvée, elle reste affichée, si elle est dans chosen_cards ou last round cards, donc fait partie des 2 cartes que l'utilisateur a choisit de retourner pendant
// le tour en cours, elle est affichée
// Si la carte ne se trouve dans aucun de ces 3 tableaux, je la garde retournée.

                        else {
                                    
                            for($i = 0; isset($_SESSION['game'][$i]); $i++) {

                                echo in_array($_SESSION['game'][$i]->name, $_SESSION['found_cards']) || 
                                    in_array($_SESSION['game'][$i]->name, $_SESSION['chosen_cards']) || 
                                    in_array($_SESSION['game'][$i]->name, $_SESSION['last_round_cards']) ?
                                    $_SESSION['game'][$i]->display_card() : $_SESSION['game'][$i]->display_back();
                            }
                        }
                                
                        // echo $form->buttonWithID('reset', 'reset', 'reset');

                    }

                    if($_SESSION['remaining_cards'] == 'win') {

                        echo 'Félicitations ! Vous avez trouvé toutes les paires en ' . $_SESSION['turn'] . ' coups. <br>';

                        echo 'Votre score:' . ($_SESSION['turn'] / 6) ; 

                        echo '<br> Score = nombre coups / nombre paires';

                        echo $form->button_with_class('reset', 'reset', 'Rejouer');
                    }
                }

                echo $form->end_form();

            ?>

        </div>

    </main>

    <?php include 'footer.php' ?>

</body>
</html>