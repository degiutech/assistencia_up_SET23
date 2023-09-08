<?php

class Times
{

    public static function anos_12()
    {

        $data_atual = new DateTime();
        $ano_atual = $data_atual->format('Y');

        $anos = [];
        $ano = intval($ano_atual);
        for ($i = 0; $i <= 11; $i++) {

            $anos[] = $ano;
            $ano -= 1;
        }

        return $anos;
    }

    public static function meses()
    {

        $meses = [];


        $strings = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        ];


        for ($i = 0; $i < 12; $i++) {

            $mes = new DateTime('M');
            $mes->sub(new DateInterval('P' . $i . 'M'));
            $int = $mes->format('m');
            $string = $strings[strval($int)];

            array_push($meses, ['int' => $int, 'string' => $string]);
        }
        return $meses;
    }
    
    public static function mes_string($mes)
    {

        $strings = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        ];

        return $strings[$mes];
    }

    public static function idade_anos($data_nasc)
    {

        if (!$data_nasc || $data_nasc == '0000-00-00') {
            return 'Não informada';
        }

        $data = new DateTime($data_nasc);
        $resultado = $data->diff(new DateTime(date('Y-m-d')));
        return $resultado->format('%Y anos');
    }

    //Idade completa
    public static function idade_completa($data_nasc)
    {

        $data = new DateTime($data_nasc);
        $resultado = $data->diff(new DateTime(date('Y-m-d')));
        // return $resultado->format('%Y anos');
        $idade = [
            'dias' => $resultado->format('%d dias'),
            'meses' => $resultado->format('%m meses'),
            'anos' => $resultado->format('%Y anos')
        ];
        return json_encode($idade);
    }
}
