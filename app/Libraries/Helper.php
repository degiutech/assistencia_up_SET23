<?php

/**
 * [ HELPER ] - Classe auxiliar para prover funções distintas
 * 
 * @autor Marcos Antonio Galvão <marcosgalvaotecnologia@gmail.com>
 * @copyright 2021-2021 MAG-TEC
 */

 class Helper {


    public static function resumeText($text, $limit, $continue = null) {

        $text = strip_tags(trim($text));
        $limit = (int) $limit;

        $array = explode(' ', $text);
        $totalWords = count($array);
        $shortText = implode(' ', array_slice($array, 0, $limit));

        $continue = (empty($continue) ? ' ...' : ''.$continue);
        $result = ($limit < $totalWords ? $shortText . $continue : $text);

        return $result;

        echo '<hr>';
        var_dump($array, $totalWords);
        echo '<hr>';

    }
 }