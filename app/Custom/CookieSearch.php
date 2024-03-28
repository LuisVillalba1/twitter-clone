<?php

namespace App\Custom;

class CookieSearch
{
    public $type;
    public $value;

    //la cookie podra ser una busqueda normal,asi como un usuario
    public function __construct($type,$value) {
        $this->type = $type;
        $this->value = $value;
    }

}