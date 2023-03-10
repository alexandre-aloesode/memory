<?php 

class Form {

    private $data;

    public function __construct($data = array()) {

        $this->data = $data;

    }


    public function start_form($method) {

        return '<form method="' . $method . '">';
    }

    public function start_form_with_id($method, $id) {

        return '<form method="' . $method . '" id="' . $id . '">';
    }


    public function end_form() {

        return '</form>';
    }

//grâce au construct et à la fonction ci-dessous je récupère les données tapées par l'utilisateur si par exemple son envoi de form fail pour x raison, afin qu'il n'ait pas à tout retaper.
    public function getValue($index) {

//opérateur ternaire prenant trois opérandes : une condition, une déclaration de résultat pour vrai et une déclaration de résultat pour faux. 
//Avant le point d'interrogation la condition, et séparés par les 2 points mes 2 déclarations de résultat.
        return isset($this->data[$index]) ? $this->data[$index] : null ;
    }

    public function text($balise, $message) {

        return '<' . $balise . '>' . $message . '</' . $balise . '>';

    }


    public function label($name, $desc) {

        return '<label for="' . $name . '">' . $desc . '</label>';

    }


    public function inputPOST($type, $name) {

        return '<input type="' . $type . '" name="' . $name . '" value="' . $this->getValue($name) . '"> <br>' ; 

    }

    

    public function inputNoValue($type, $name) {

        return '<input type="' . $type . '" name="' . $name . '"> <br>' ; 

    }


    public function inputWithValue($type, $name, $value) {

        return '<input type="' . $type . '" name="' . $name . '" value="' . $value . '"> <br>' ; 

    }

    public function passwordWithEye($name) {

        return

            '<input type="password" name="' . $name . '" class="password_with_eye">
            <i class="fa-regular fa-eye"></i>
            <br>'; 

    }

    
    public function button($name, $desc) {

        return '<button type="submit" name="' . $name . '">' . $desc . '</button>' ;
    }

    public function buttonWithID($name, $id, $desc) {

        return '<button type="submit" name="' . $name . '" id="' . $id .'">' . $desc . '</button>' ;
    }

    public function button_with_class($name, $class, $desc) {

        return '<button type="submit" name="' . $name . '" class="' . $class .'">' . $desc . '</button>' ;
    }

    public function button_with_class_and_value($name, $class, $desc) {

        return '<button type="submit" name="' . $name . '" class="' . $class .'" value="' . $desc . '">' . $desc . '</button>' ;
    }

}