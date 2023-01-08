<?php


    class Game {

        private $characters = ['alice', 'kagura', 'pharsa', 'minotaur','valir','cyclops', 'natalia', 'grock', 'eudora', 'popol', 'yve', 'badang', 'karrie', 'moskov'];
        
        private $level;

        private $selected_pairs = [];

        private $random_pair;

        public function __construct($difficulty) {

            if($difficulty == 'novice')  $this->level = 6 ;

            if($difficulty == 'intermediaire') $this->level = 8 ;

            if($difficulty == 'expert') $this->level = 10 ;
        }

        public function select_pairs() {

            for($x = 0; $x < $this->level; $x++) {

                $this->random_pair = array_rand($this->characters);

                    array_push($this->selected_pairs, $this->characters[$this->random_pair] . '1');

                    array_push($this->selected_pairs, $this->characters[$this->random_pair] . '2');

                    unset($this->characters[$this->random_pair]);
            }

            shuffle($this->selected_pairs);
            
            return $this->selected_pairs;
        }



    }