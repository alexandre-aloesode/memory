<?php

class User {

    private $id;

    public $login;

    public $password;

    public $bdd;

    public $message;

    public $check;
    

    public function __construct() {

        try {

        $this->bdd = new PDO('mysql:host=localhost;dbname=memory;charset=utf8', 'root','');

        //$this->bdd = new PDO('mysql:host=localhost;dbname=alexandre-aloesode_memory;charset=utf8', 'Namrod','azertyAZERTY123!');

        $this->bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        }

        catch (PDOException $e) {

            echo 'Echec de la connexion : ' . $e->getMessage();
            
            exit;
        }
        
    }



    public function register() {

        $this->login = $_POST['login'];

        $this->password = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

        $this->check = 1;

        if(empty($_POST['login']) || empty($_POST['mdp']) || trim($_POST['login']) == '' || trim($_POST['mdp']) == '') {
                
            $this->check = 0;
            $this->message = 'Certains champs sont vides';
        }

        if($_POST['mdp'] !== $_POST['mdp_confirm']) {

            $this->check = 0;
            $this->message =  'Les mots de passe ne correspondent pas';
        }

        if($this->check == 1) {

            $request_check_login= "SELECT login FROM utilisateurs";

            $query_check_login = $this->bdd->prepare($request_check_login);
            $query_check_login->execute();

            $result_check_login = $query_check_login->fetchAll();

            for($x = 0; isset($result_check_login[$x]); $x++ ) {
                    if($result_check_login[$x][0] == $_POST['login']) {

                        $this->check = 0 ;
                        $this->message = 'Ce pseudo existe déjà';
                    }
            } 
        }

        if($this->check == 1) {
  
            $request_add_user = "INSERT INTO utilisateurs(login, password) VALUES (:login, :password)";

            $query_add_user = $this->bdd->prepare($request_add_user);

            $query_add_user->execute(array(':login' => $this->login, ':password' => $this->password));

            $this->message = 'Compte créé avec succès. <br> Vous êtes désormais connecté.';

            $_SESSION['user'] = $this->login;

        }
//Je souhaite connecter directement l'utilisateur qui crée son compte, les lignes suivantes me permettent de récupérer son id
        if(isset($_SESSION['user']) && !isset($_SESSION['userID'])) {

        $request_ID_user = "SELECT id FROM utilisateurs WHERE login = '$_SESSION[user]'";

        $query_ID_user = $this->bdd->prepare($request_ID_user);
        $query_ID_user->execute();

        $result_ID_user = $query_ID_user->fetchAll();

        $_SESSION['userID'] = $result_ID_user[0][0];
        }  
    }



    
    public function connect() {
    
        $this->check = 0;

        $this->login = $_POST['login'];
      
        $request_login= "SELECT * FROM utilisateurs";

        $query_login = $this->bdd->prepare($request_login);
        $query_login->execute();

        $result_login = $query_login->fetchAll();
    
        for($x = 0; isset($result_login[$x]); $x++){
    
            if($result_login[$x][1] == $this->login){
                    
                $this->check ++;
    
                    if(password_verify($_POST['mdp'], $result_login[$x][2])) {

                        $this->check ++;

                        $_SESSION['userID'] = $result_login[$x][0];
                        $_SESSION['user'] = $this->login;
                    }
            }       
        }
    
        if($this->check == 0){

            $this->message = "Ce nom d'utilisateur n'existe pas.";
        } 
            
        elseif($this->check == 1){

            $this->message = "Le nom d'utilisateur et le mot de passe ne correspondent pas.";
        } 
            
        elseif($this->check == 2){

            $this->message = "Connexion réussie.";
            $_SESSION['user'] = $_POST['login'];
        }
    }



    public function disconnect() {

        session_destroy();
        header('Location: index.php');

    }




    public function get_user_info() {

        $request_fetch_user_info= "SELECT * FROM utilisateurs where id = '$_SESSION[userID]'";

        $query_fetch_user_info = $this->bdd->prepare($request_fetch_user_info);
        $query_fetch_user_info->execute();
        
        $result_fetch_user_info = $query_fetch_user_info->fetchAll();

        $this->id = $result_fetch_user_info[0][0];
        $this->login = $result_fetch_user_info[0][1];
        $this->password = $result_fetch_user_info[0][2];
    }




    public function update_profile() {
 
    $this->check = 1 ;

        if(empty($_POST['login']) || empty($_POST['mdp']) || trim($_POST['login']) == '' || trim($_POST['mdp']) == '') {

            $this->check = 0;
            $this->message = 'Certains champs sont vides';
        }

        if($_POST['new_mdp'] !== $_POST['new_mdp_confirm']) {

            $this->check = 0;
            $this->message = 'Les nouveaux mots de passe ne correspondent pas';
        }

        if($this->check == 1) {

            $this->get_user_info();

            if(!password_verify($_POST['mdp'], $this->password)) {

                $this->check = 0;
                $this->message = 'Ancien mot de passe incorrect';
            }

            if($this->check == 1) {   

                $request_user_info= "SELECT id, login FROM `utilisateurs`";

                $query_user_info = $this->bdd->prepare($request_user_info);
                $query_user_info->execute();

                $result_user_info = $query_user_info->fetchAll();

                for($x = 0; isset($result_user_info[$x]); $x++ ) {

                        if($result_user_info[$x][1] == $_POST['login'] && $result_user_info[$x][0] !== $_SESSION['userID']) {

                            $this->check = 0;
                            $this->message = 'Ce pseudo existe déjà';
                        }
                }
            }
        }

        if($this->check == 1) {
                
        $modified_mdp_hashed = password_hash($_POST['new_mdp'], PASSWORD_DEFAULT);

        $update_user_profile = "UPDATE utilisateurs 
        SET login = :login, password = :password WHERE id = :id";

        $query_update_user_profile = $this->bdd->prepare($update_user_profile);
        
        $query_update_user_profile->execute(array(':login' => $_POST['login'], ':password' => $modified_mdp_hashed, ':id' => $_SESSION['userID']));

        $this->message = "informations modifiées.";
        
        }
    }




    public function delete() {

        $request_delete_profile = "DELETE FROM utilisateurs WHERE utilisateurs.id = '$this->id'";

        $query_delete_profile = $this->bdd->prepare($request_delete_profile);
        $query_delete_profile->execute();

        session_destroy();

        header('Location: index.php');
    }




    public function save_game($difficulte, $finie, $coups) {

        $date = new DateTime("now"); new DateTimezone("Europe/Paris");

        $this->get_user_info();
        
        $request_add_game = "INSERT INTO parties (date, difficulte, finie, coups, id_utilisateur) VALUES (:date, :difficulte, :finie, :coups, :id_utilisateur)";

        $query_add_game = $this->bdd->prepare($request_add_game);

        $query_add_game->execute(array(':date' => $date->format('Y-m-d H:i'), ':difficulte' => $difficulte, ':finie' => $finie, ':coups' => $coups / 2, ':id_utilisateur' => $this->id));

    }

    public function display_last_games($userID) {

        $request_last_games = "SELECT * FROM parties
        INNER JOIN utilisateurs on parties.id_utilisateur = utilisateurs.id
        WHERE utilisateurs.id = $userID
        LIMIT 10";

        $query_last_games = $this->bdd->prepare($request_last_games);
        $query_last_games->execute();

        $result_last_games = $query_last_games->fetchAll();

        echo '<table class="game_table">

                    <thead>

                        <h2>Dernières parties</h2>

                        <tr>

                            <th>Date</th>

                            <th>Difficulté</th>

                            <th>Finie</th>

                            <th>Nombre de coups</th>

                        </tr>

                    </thead>

                    <tbody>';

        for($x = 0; isset($result_last_games[$x]); $x++) {

            echo    '<tr>

                        <td>' . $result_last_games[$x][1] . '</td>

                        <td>' . $result_last_games[$x][2] . '</td>

                        <td>' . $result_last_games[$x][3] . '</td>

                        <td>' . $result_last_games[$x][4] . '</td>

                    </tr>';
        }

        echo        '</tbody>

                </table>';

    }

}

?>