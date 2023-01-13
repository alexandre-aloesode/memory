<?php 

    class Card {

        
    public $name;

    public function __construct($card) {

        $this->name = $card;
        
    }

    public function display_card($level) {

        if($level == 'Novice') return '<img src="./images/' . substr($this->name , 0, -1).'.jpg" class="card-novice">';

        if($level == 'Intermediaire') return '<img src="./images/' . substr($this->name , 0, -1).'.jpg" class="card-intermediaire">';

        if($level == 'Expert') return '<img src="./images/' . substr($this->name , 0, -1).'.jpg" class="card-expert">';

    }

    public function display_back($level) {

        if($level == 'Novice') return
        
        '<button type="submit" name="card" class="card-novice" value="' . $this->name .'">

            <img src="./images/back-side.jpg" class="card-novice">

        </button>';


        if($level == 'Intermediaire') return
        
        '<button type="submit" name="card" class="card-intermediaire" value="' . $this->name .'">

            <img src="./images/back-side.jpg" class="card-intermediaire">

        </button>';


        if($level == 'Expert') return
        
        '<button type="submit" name="card" class="card-expert" value="' . $this->name .'">

            <img src="./images/back-side.jpg" class="card-expert">

        </button>';
    }

    }
?>