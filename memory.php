<?php

// Pour éviter l'erreur "PHP CLASS INCOMPLETE" je dois require ma class avant de démarrer ma session.
    require './Classes/Card.php';

    session_id() == '' ? session_start() : null ;


// Au démarrage du jeu je crée 5 variables de session.
    // -turn qui me permet de compter les coups et me sert à la génération des cartes
    // -found_cards: tableau vide dans lequel je stocke les paires trouvées et me permet de les garder retournées pendant le reste de la partie 
    // -chosen_cards: tableau vide dans lequel je stocke les 2 cartes que l'utilisateur retourne. Je reset le tableau tous les 2 tours/cliques
    // -last_round_cards: dans mon code, 1 tour = 2 cliques donc 2 cartes, sauf que la deuxième ne s'affichait pas s'il n'y avait pas de paire trouvée. 
    // Je stocke donc les 2 cartes du tour précédent dans cette variable pour que l'utilisateur puisse les voir même s'il n'a pas trouvé là paire.
    // Au prochain clique les 2 cartes redeviennent retournées.
    // -remaining_cards: je stocke l'attribut name de chaque carte dans ce tableau, et dès qu'une paire est trouvée je retire les 2 cartes de ce tableau. 
    //  Quand il est vide ça déclenche ma condition de victoire

    if(!isset($_SESSION['found_cards']) || !isset($_SESSION['turn'])) {

        $_SESSION['turn'] = 0 ;

        $_SESSION['found_cards'] = [];

        $_SESSION['chosen_cards'] = [];

        $_SESSION['last_round_cards'] = [];

        $_SESSION['remaining_cards'] = ['alice1', 'alice2', 'kagura1', 'kagura2', 'pharsa1', 'pharsa2', 'minotaur1', 'minotaur2', 'valir1', 'valir2', 'cyclops1', 'cyclops2'];
    }

    if(isset($_POST['reset'])) {

        $_SESSION['turn'] = 0;

        $_SESSION['found_cards'] = [];

        $_SESSION['chosen_cards'] = [];     

        $_SESSION['last_round_cards'] = [];

        $_SESSION['remaining_cards'] = ['alice1', 'alice2', 'kagura1', 'kagura2', 'pharsa1', 'pharsa2', 'minotaur1', 'minotaur2', 'valir1', 'valir2', 'cyclops1', 'cyclops2'];

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

            $_SESSION['remaining_cards'] = array_filter($_SESSION['remaining_cards'], static function ($element) {
                return $element !== $_SESSION['chosen_cards'][0]; });
            
            $_SESSION['remaining_cards'] = array_filter($_SESSION['remaining_cards'], static function ($element) {
                return $element !== $_SESSION['chosen_cards'][1]; });
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
    if(empty($_SESSION['remaining_cards'])) {
        echo 'GG';
    }

    if(empty($_SESSION['remaining_cards']) && isset($_POST['card'])) {
       
        if(isset($_SESSION['user'])) {

        require './Classes/User.php';

        $user = new User();

        $user->save_game('novice', $_SESSION['turn'], 'OUI');

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
         
    $alice1 = new Card('alice1');

    $kagura1 = new Card('kagura1');

    $pharsa1 = new Card('pharsa1');

    $valir1 = new Card('valir1');

    $minotaur1 = new Card('minotaur1');

    $cyclops1 = new Card('cyclops1');

    $alice2 = new Card('alice2');

    $kagura2 = new Card('kagura2');

    $pharsa2 = new Card('pharsa2');

    $valir2 = new Card('valir2');

    $minotaur2 = new Card('minotaur2');
            
    $cyclops2 = new Card('cyclops2');

    $form = new Form();

    echo $form->start_form('post');

        if(!empty($_SESSION['remaining_cards'])) {

// Pour démarrer le jeu et générer les cartes aléatoirement, je stocke mes instances de cartes dans un tableau $cards. 
// Je boucle ensuite sur $cards et array_rand me permet de générer les cartes aléatoirement
// A chaque tour de boucle :
//    -je génère une carte avec array_rand
//    -j'affiche la carte retournée
//    -Pour la suite du jeu j'ai besoin de conserver l'ordre de distribution. Je crée donc $_SESSION[game] qui se remplit à chaque tour de boucle avec la carte générée
//    -Enfin j'enlève la carte de mon tableau $cards pour ne pas qu'elle soit générée une 2ème fois

            if($_SESSION['turn'] == 0) {   

                $cards=[$alice1, $alice2, $kagura1, $kagura2, $pharsa1, $pharsa2, $valir1, $valir2, $minotaur1, $minotaur2, $cyclops1, $cyclops2];

                $_SESSION['game'] = [];

                for($x = 0; $x < 12; $x++) {

                    $random_card = array_rand($cards);

                    echo $cards[$random_card]->display_back();

                    array_push($_SESSION['game'], $cards[$random_card]);

                    unset($cards[$random_card]);
                        
                }
            }

// Pour le reste de la partie, grâce à $_SESSION[game] j'ai mon ordre de distribution conservé. J'ai donc juste à boucler dessus pour afficher les cartes.
// Si la carte fait partie d'une paire trouvée, elle reste affichée, si elle est dans chosen_cards, donc fait partie des 2 cartes que l'utilisateur a choisit de retourner pendant
// le tour en cours, elle est affichée
// Si la carte ne se trouve dans aucun de ces 2 tableaux, je la garde retournée.

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

        if(empty($_SESSION['remaining_cards'])) {

            echo 'Félicitations ! Vous avez trouvé toutes les paires en ' . $_SESSION['turn'] . ' coups. <br>';

            echo 'Votre score:' . ($_SESSION['turn'] / 6) ; 

            echo '<br> Score = nombre coups / nombre paires';

            echo $form->buttonWithID('reset', 'reset', 'reset');
        }

    echo $form->end_form();

?>
    </div>

    </main>

    <?php include 'footer.php' ?>

</body>
</html>