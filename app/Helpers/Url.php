<?php

class Url {

    public static function redirect($url) {
        header("Location:" . URL.DIRECTORY_SEPARATOR.$url);
    }

}