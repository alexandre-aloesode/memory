<?php

class Best_Player_Novice {

    public $name;

    public $games;

    public $average_count;

    public function __construct($name, $games, $average_count) {
        
        $this->name = $name;

        $this->games = $games;

        $this->average_count = $average_count;
    }


}

?>