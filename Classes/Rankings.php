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

        if($this->level == 'Novice') {

            require './Classes/Best_players_Novice.php';

        }

        if($this->level == 'Intermediaire') {

            require './Classes/Best_players_Interm.php';
            
        }

        if($this->level == 'Expert') {

            require './Classes/Best_players_Expert.php';
            
        }

        $request_users = "SELECT id FROM utilisateurs";
        $query_users = $this->bdd->prepare($request_users);
        $query_users->execute();
        $result_users = $query_users->fetchAll();

        ${'best_players_' . $this->level} = [];

        for($u = 0; isset($result_users[$u]); $u++) {

            ${'parties_user' . $result_users[$u][0]} = 0;

            ${'coups_user' . $result_users[$u][0]} = 0;

            $request_games = "SELECT * FROM parties INNER JOIN utilisateurs on parties.id_utilisateur = utilisateurs.id WHERE parties.difficulte = '$this->level'";
            $query_games = $this->bdd->prepare($request_games);
            $query_games->execute();
            $result_games = $query_games->fetchAll();

            for($i = 0; isset($result_games[$i]); $i++){

                if($result_users[$u][0] == $result_games[$i][5]) {

                    ${'parties_user' . $result_users[$u][0]}++;

                    ${'coups_user' . $result_users[$u][0]} = ${'coups_user' . $result_users[$u][0]} + $result_games[$i][4];

                    ${'name_user' . $result_users[$u][0]} = $result_games[$i][7];

                }

            }

            if(${'parties_user' . $result_users[$u][0]} > 0) {

                if($this->level == 'Novice') {

                array_push(${'best_players_' . $this->level}, new Best_Player_Novice(${'name_user' . $result_users[$u][0]}, ${'parties_user' . $result_users[$u][0]}, ${'coups_user' . $result_users[$u][0]} / ${'parties_user' . $result_users[$u][0]}));

                }

                if($this->level == 'Intermediaire') {
                    
                    array_push(${'best_players_' . $this->level}, new Best_Player_Intermediaire(${'name_user' . $result_users[$u][0]}, ${'parties_user' . $result_users[$u][0]}, ${'coups_user' . $result_users[$u][0]} / ${'parties_user' . $result_users[$u][0]}));
    
                }

                if($this->level == 'Expert') {
                    
                    array_push(${'best_players_' . $this->level}, new Best_Player_Expert(${'name_user' . $result_users[$u][0]}, ${'parties_user' . $result_users[$u][0]}, ${'coups_user' . $result_users[$u][0]} / ${'parties_user' . $result_users[$u][0]}));
    
                }
            }
        }


        for($x = 0; isset(${'best_players_' . $this->level}[$x]); $x++) {

            for($y = 0; isset(${'best_players_' . $this->level}[$y]); $y++) {

                if(${'best_players_' . $this->level}[$y]->average_count > ${'best_players_' . $this->level}[$x]->average_count) {

                    $temp = ${'best_players_' . $this->level}[$x];

                    ${'best_players_' . $this->level}[$x] = ${'best_players_' . $this->level}[$y];

                    ${'best_players_' . $this->level}[$y] = $temp;
                }
            
            }
        }
        

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

        for($n = 0; isset(${'best_players_' . $this->level}[$n]); $n++) {


            echo        '<tr>

                            <td>' . ${'best_players_' . $this->level}[$n]->name . '</td>

                            <td>' . ${'best_players_' . $this->level}[$n]->games . '</td>

                            <td>' . ${'best_players_' . $this->level}[$n]->average_count . '</td>

                        </tr>';
        }

        echo        '</tbody>

                </table>';

    }
}
