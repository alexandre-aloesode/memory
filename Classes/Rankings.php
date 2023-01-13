<?php

Class Ranking {


    private $level;

    private $bdd;

    public function __construct($level) {

        $this->level = $level;

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
    
    
    public function display_ranking() {

        // $request_users = "SELECT id, login FROM utilisateurs";
        // $query_users = $this->bdd->prepare($request_users);
        // $query_users->execute();
        // $result_request_users = $query_users->fetchAll();

        // for($u = 0; isset($result_request_users[$u]); $u++) {
        //     $request_user_games = "SELECT SUM("
        // }

        
        $request_ranking = "SELECT COUNT(parties.id), SUM(parties.coups), parties.id_utilisateur, parties.difficulte, utilisateurs.id, utilisateurs.login FROM parties
        INNER JOIN utilisateurs on parties.id_utilisateur = utilisateurs.id
        WHERE parties.difficulte = '$this->level'";

        $query_ranking = $this->bdd->prepare($request_ranking);
        $query_ranking->execute();

        $result_ranking = $query_ranking->fetchAll();

        echo    '<table class="game_table">

                    <thead>

                    <h2>Classement ' . $this->level . '

                        <tr>

                            <th>Pseudo</th>

                            <th>Total de parties</th>

                            <th>Moyenne de coups</th>

                        </tr>

                    </thead>

                    <tbody>';

        for($x = 0; isset($result_ranking[$x]); $x++) {

            echo        '<tr>

                            <td>' . $result_ranking[$x][5] . '</td>

                            <td>' . $result_ranking[$x][0] . '</td>

                            <td>' . $result_ranking[$x][1] . '</td>

                        </tr>';
        }

        echo        '</tbody>

                </table>';

    }
}
