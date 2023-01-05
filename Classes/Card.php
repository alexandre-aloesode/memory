<?php 

    class Card {

    public $name;

    public function __construct($card) {

        $this->name = $card;
    }

    public function display_card() {

        return '<img src="./images/' . $this->name .'.jpg" class="card">';
    }

    public function display_back() {

        return
        
        '<button type="submit" name="card" value="' . $this->name .'">

            <img src="./images/back-side.jpg" class="card">

        </button>';
    }

    // public function getNumber() {

    //     return $this->number;
    // }



    }
?>