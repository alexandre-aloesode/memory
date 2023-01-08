<?php 

    class Card {

    public $name;

    public function __construct($card) {

        $this->name = $card;
        
        array_push($_SESSION['game'], $this);
    }

    public function display_card() {

        return '<img src="./images/' . substr($this->name , 0, -1).'.jpg" class="card">';
    }

    public function display_back() {

        return
        
        '<button type="submit" name="card" class="card" value="' . $this->name .'">

            <img src="./images/back-side.jpg" class="card">

        </button>';
    }

    }
?>