<?php


    class Game {
        /**
         * @var array contains card names
         */
        private $characters = ['alice', 'kagura', 'pharsa', 'minotaur','valir','cyclops', 'natalia', 'grock', 'eudora', 'popol', 'yve', 'badang', 'karrie', 'moskov'];
        
        public $level;

        private $selected_pairs = [];

        private $random_card;

        public function __construct($difficulty) {

            if($difficulty == 'Novice')  $this->level = 6 ;

            if($difficulty == 'Intermediaire') $this->level = 8 ;

            if($difficulty == 'Expert') $this->level = 10 ;
        }

        /**
         * @var function 
         */
        public function select_pairs() {

            for($x = 0; $x < $this->level; $x++) {

                $this->random_card = array_rand($this->characters);

                    array_push($this->selected_pairs, $this->characters[$this->random_card] . '1');

                    array_push($this->selected_pairs, $this->characters[$this->random_card] . '2');

                    unset($this->characters[$this->random_card]);
            }

            shuffle($this->selected_pairs);
            
            return $this->selected_pairs;
        }



    }