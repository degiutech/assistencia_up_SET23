<?php

class Representante extends Controller
{

    private $home;
    private $sessao_acesso;

    private $cidadaoModel;
    private $assistenciaModel;
    private $assistenciaUpModel;
    private $representanteModel;

    public function __construct()
    {

        if (!Sessao::estaLogado()) :
        // URL::redirect('users/login_email');
        else :
            $this->sessao_acesso = Sessao::sessaoUser();

            if ($this->sessao_acesso['acesso'] == 'Representante') {
                $this->home = URL . '/representante';
            } else {
                // $this->userController = $this->controller('Users');
                // $this->userController->index_acesso();
                // exit();
            }

        endif;

        $this->representanteModel = $this->model('RepresentanteModel');
        $this->assistenciaModel = $this->model('AssistenciaModel');
        $this->assistenciaUpModel = $this->model('AssistenciaUpModel');
        $this->cidadaoModel = $this->model('CidadaoModel');
    }

    public function index()
    {

        //Minhas Assistências - recentes todas
        $dados = $this->minhas_assistencias();

        $this->view('representante/index', $dados);
    }

    //MINHAS ASSISTÊNCIAS
    //Recentes
    public function minhas_assistencias()
    {
        $dados = $this->minhas_assistencias_recentes_generica('todas');
        $this->view('representante/index', $dados);
    }

    //Não finalizadas
    public function minhas_assistencias_nao_finalizadas()
    {
        $dados = $this->minhas_assistencias_recentes_generica('nao_finalizadas');
        $this->view('representante/index', $dados);
    }

    //Finalizadas
    public function minhas_assistencias_finalizadas()
    {
        $dados = $this->minhas_assistencias_recentes_generica('finalizadas');
        $this->view('representante/index', $dados);
    }

    //GENÉRICA RECENTES
    public function minhas_assistencias_recentes_generica($status)
    {

        $id_operador = $_SESSION['user']['id'];


        if ($status == 'nao_finalizadas') {
            $titulo = '<b>ASSISTÊNCIAS NÃO FINALIZADAS (recentes)</b>';
            $titulo_botao = 'não finalizadas';
        }
        if ($status == 'todas') {
            $titulo = '<b>ASSISTÊNCIAS RECENTES (todas)</b>';
            $titulo_botao = 'recentes';
        }
        if ($status == 'finalizadas') {
            $titulo = '<b>ASSISTÊNCIAS FINALIZADAS (recentes)</b>';
            $titulo_botao = 'finalizadas';
        }

        $updates_res = $this->assistenciaModel->updatesByOperador($id_operador, $status);

        if ($updates_res['erro'] != '') {
            Sessao::mensagem('assistencias', $updates_res['erro']);
            return $this->index();
        }
        $updates = $updates_res['updates'];

        $assistencias = [];
        $array_assistidos = [];
        for ($i = 0; $i < count($updates); $i++) {

            $descricao = $updates[$i]['status_compl_updated'];

            //Primeiro registro
            $primeiro_registro = '';
            $primeiro_registro_res = $this->assistenciaModel->getAssistenciaById($updates[$i]['id_assistencia']);
            if ($primeiro_registro_res['erro'] == '') {
                $primeiro_registro = $primeiro_registro_res['assistencia'];
                $dpr = new DateTime($primeiro_registro['date_at']);
                $data_primeiro_registro = $dpr->format('d/m/Y');

                //Descrição primeiro registro
                if ($updates[$i]['status_compl_updated'] == 'Assistência Iniciada') {
                    $descricao = $primeiro_registro['descricao'];
                }

                //array assistidos
                if (!in_array($primeiro_registro['id_cidadao'], $array_assistidos)) {
                    $array_assistidos[] = $primeiro_registro['id_cidadao'];
                }
            }

            //Nome do Cidadão
            $nome_cidadao = '';
            $cidadao_res = $this->cidadaoModel->getNomeIdCidadao($primeiro_registro['id_cidadao']);
            if ($cidadao_res['erro'] == '') {
                $nome_cidadao = $cidadao_res['cidadao']['nome'];
            }

            //Tipo de registro
            $tipo = '';
            if ($updates[$i]['status_updated'] == 'Iniciada') {
                $tipo = 'Primeiro registro';
            }
            if ($updates[$i]['status_updated'] == 'Finalizada') {
                $tipo = 'Finalização';
            }
            if ($updates[$i]['status_updated'] != 'Iniciada' && $updates[$i]['status_updated'] != 'Finalizada') {
                $tipo = 'Atualização';
            }

            //Coordenadoria
            $nome_coordenadoria = $updates[$i]['nome_coordenadoria'];
            $id_coordenadoria = $updates[$i]['id_coordenadoria'];

            //$data
            $dt = new DateTime($updates[$i]['updated_at']);
            $data = $dt->format('d/m/Y');
            $assistencias[$i] = [
                'data' => $data,
                'primeiro_registro' => $primeiro_registro,
                'id_primeiro_registro' => $primeiro_registro['id'],
                'status_assist'        => $primeiro_registro['status_assist'],
                'nome_cidadao' => $nome_cidadao,
                'id_cidadao'         => $primeiro_registro['id_cidadao'],
                'tipo' => $tipo,
                'nome_coordenadoria' => $nome_coordenadoria,
                'id_coordenadoria' => $id_coordenadoria,
                'descricao'          => $descricao,
                'data_primeiro_registro' => $data_primeiro_registro
            ];
        }

        // $assistencias = $titulo;
        $num_registros = 'Registros: ' . count($updates);

        $count_assistidos = count($array_assistidos);

        $meses = Times::meses();

        //Contadores
        //geral
        $count_geral = 0;
        $count_geral_res = $this->assistenciaUpModel->countUpdatesByOperador($id_operador, 'geral');
        if ($count_geral_res['erro'] == '') {
            $count_geral = $count_geral_res['count'];
        }
        //não finalizadas
        $count_geral_nao_finalizadas = 0;
        $count_geral_nao_finalizadas_res = $this->assistenciaUpModel->countUpdatesByOperador($id_operador, 'nao_finalizadas');
        if ($count_geral_nao_finalizadas_res['erro'] == '') {
            $count_geral_nao_finalizadas = $count_geral_nao_finalizadas_res['count'];
        }
        //finalizadas
        $count_geral_finalizadas = 0;
        $count_geral_finalizadas_res = $this->assistenciaUpModel->countUpdatesByOperador($id_operador, 'finalizadas');
        if ($count_geral_finalizadas_res['erro'] == '') {
            $count_geral_finalizadas = $count_geral_finalizadas_res['count'];
        }

        $dados = [
            'titulo'          => $titulo,
            'num_registros'   => $num_registros,
            'count_assistidos' => 'Assistidos(as): ' . $count_assistidos,
            'anos'             => Times::anos_12(),
            'updates'         => $updates,
            'meses'            => $meses,
            'home'             => $this->home,
            'titulo_botao'     => $titulo_botao,

            'assistencias'     => $assistencias,

            //contadores geral
            'count_geral'      => $count_geral,
            'count_geral_nao_finalizadas'      => $count_geral_nao_finalizadas,
            'count_geral_finalizadas'      => $count_geral_finalizadas,


        ];

        return $dados;
    }

    // Não Finalizadas
    public function minhas_ass_nao_finalizadas()
    {

        $id_operador = $_SESSION['user']['id'];

        $dados = $this->assistenciaModel->assByOperadorNaoFinalizadas($id_operador);

        $dados['anos'] = Times::anos_12();
        $meses = Times::meses();
        $dados['meses'] = $meses;

        $this->view('representante/index', $dados);
    }

    // Finalizadas
    public function minhas_ass_finalizadas()
    {

        $id_operador = $_SESSION['user']['id'];

        $dados = $this->assistenciaModel->assByOperadorFinalizadas($id_operador);

        $dados['anos'] = Times::anos_12();
        $meses = Times::meses();
        $dados['meses'] = $meses;

        $this->view('representante/index', $dados);
    }

    //Filtros de busca de Assistências
    public function minhas_assistencias_by_filtros($dia, $mes, $ano, $dt_inicial, $dt_final, $status)
    {

        $id_operador = $_SESSION['user']['id'];

        //por data
        if ($dia && $mes && $ano) {
            $updates_res = $this->assistenciaModel->updatesByOperadorByData($id_operador, $dia, $mes, $ano, $status);
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS EM ' . $dia . '/' . $mes . '/' . $ano . ' (' . $status . ')</b>';
        }
        //mês e ano
        if (!$dia && $mes && $ano) {
            $updates_res = $this->assistenciaModel->updatesByOperadorMesAno($id_operador, $mes, $ano, $status);
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS EM ' . Times::mes_string($mes) . '/' . $ano . ' (' . $status . ')</b>';
        }
        //período
        if ($dt_inicial && $dt_final) {
            $updates_res = $this->assistenciaModel->updatesByOperadorPeriodo($id_operador, $dt_inicial, $dt_final, $status);
            //data inicial
            $dti = new DateTime($dt_inicial);
            $dt_i = $dti->format('d/m/Y');
            //data final
            $dtf = new DateTime($dt_final);
            $dt_f = $dtf->format('d/m/Y');

            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS ENTRE ' . $dt_i . ' e ' . $dt_f . ' (' . $status . ')</b>';
        }

        if (!isset($updates_res)) {
            return $this->index();
        }

        if (isset($updates_res) && $updates_res['erro'] != '') {
            Sessao::mensagem('assistencias', $updates_res['erro']);
            return $this->index();
        }
        $updates = $updates_res['updates'];

        $assistencias = [];
        $array_assistidos = [];
        for ($i = 0; $i < count($updates); $i++) {

            // $assistencias[] = ['updates' => $updates[$i]];

            $descricao = $updates[$i]['status_compl_updated'];

            //Primeiro registro
            $primeiro_registro = '';
            $primeiro_registro_res = $this->assistenciaModel->getAssistenciaById($updates[$i]['id_assistencia']);
            if ($primeiro_registro_res['erro'] == '') {
                $primeiro_registro = $primeiro_registro_res['assistencia'];
                $dpr = new DateTime($primeiro_registro['date_at']);
                $data_primeiro_registro = $dpr->format('d/m/Y');

                //Descrição primeiro registro
                if ($updates[$i]['status_compl_updated'] == 'Assistência Iniciada') {
                    $descricao = $primeiro_registro['descricao'];
                }

                //array assistidos
                if (!in_array($primeiro_registro['id_cidadao'], $array_assistidos)) {
                    $array_assistidos[] = $primeiro_registro['id_cidadao'];
                }
            }

            //Nome do Cidadão
            $nome_cidadao = '';
            $cidadao_res = $this->cidadaoModel->getNomeIdCidadao($primeiro_registro['id_cidadao']);
            if ($cidadao_res['erro'] == '') {
                $nome_cidadao = $cidadao_res['cidadao']['nome'];
            }

            //Tipo de registro
            $tipo = '';
            if ($updates[$i]['status_updated'] == 'Iniciada') {
                $tipo = 'Primeiro registro';
            }
            if ($updates[$i]['status_updated'] == 'Finalizada') {
                $tipo = 'Finalização';
            }
            if ($updates[$i]['status_updated'] != 'Iniciada' && $updates[$i]['status_updated'] != 'Finalizada') {
                $tipo = 'Atualização';
            }

            //Coordenadoria
            $nome_coordenadoria = $updates[$i]['nome_coordenadoria'];
            $id_coordenadoria = $updates[$i]['id_coordenadoria'];

            //$data
            $dt = new DateTime($updates[$i]['updated_at']);
            $data = $dt->format('d/m/Y');
            $assistencias[$i] = [
                'data' => $data,
                'primeiro_registro' => $primeiro_registro,
                'id_primeiro_registro' => $primeiro_registro['id'],
                'status_assist'        => $primeiro_registro['status_assist'],
                'nome_cidadao' => $nome_cidadao,
                'id_cidadao'         => $primeiro_registro['id_cidadao'],
                'tipo' => $tipo,
                'nome_coordenadoria' => $nome_coordenadoria,
                'id_coordenadoria' => $id_coordenadoria,
                'descricao'          => $descricao,
                'data_primeiro_registro' => $data_primeiro_registro
            ];
        }

        // $assistencias = $titulo;
        $num_registros = 'Registros: ' . count($updates);

        $count_assistidos = count($array_assistidos);

        $meses = Times::meses();

        //Contadores
        //geral
        $count_geral = 0;
        $count_geral_res = $this->assistenciaUpModel->countUpdatesByOperador($id_operador, 'geral');
        if ($count_geral_res['erro'] == '') {
            $count_geral = $count_geral_res['count'];
        }
        //não finalizadas
        $count_geral_nao_finalizadas = 0;
        $count_geral_nao_finalizadas_res = $this->assistenciaUpModel->countUpdatesByOperador($id_operador, 'nao_finalizadas');
        if ($count_geral_nao_finalizadas_res['erro'] == '') {
            $count_geral_nao_finalizadas = $count_geral_nao_finalizadas_res['count'];
        }
        //finalizadas
        $count_geral_finalizadas = 0;
        $count_geral_finalizadas_res = $this->assistenciaUpModel->countUpdatesByOperador($id_operador, 'finalizadas');
        if ($count_geral_finalizadas_res['erro'] == '') {
            $count_geral_finalizadas = $count_geral_finalizadas_res['count'];
        }

        $dados = [
            'titulo'          => $titulo,
            'num_registros'   => $num_registros,
            'count_assistidos' => 'Assistidos: ' . $count_assistidos,
            'anos'             => Times::anos_12(),
            'updates'         => $updates,
            'meses'            => $meses,
            'home'             => $this->home,

            'assistencias'     => $assistencias,
            'count_geral'      => $count_geral,
            'count_geral_nao_finalizadas' => $count_geral_nao_finalizadas,
            'count_geral_finalizadas'     => $count_geral_finalizadas,

        ];

        return $dados;
    }

    // Por data
    public function minhas_ass_por_data()
    {

        $dia = date("d");
        $mes = date("m");
        $ano = date("Y");
        $status = 'todas';

        if (filter_input(INPUT_POST, 'por_data')) {
            $por_data = filter_input(INPUT_POST, 'por_data');
            $explode = explode('-', $por_data);
            $dia = $explode[2];
            $mes = $explode[1];
            $ano = $explode[0];
            $status = filter_input(INPUT_POST, 'status_assistencia');
        }



        $dados = $this->minhas_assistencias_by_filtros($dia, $mes, $ano, $dt_inicial = null, $dt_final = null, $status);

        $dados['titulo_botao'] = 'por_data';

        $this->view('representante/index', $dados);
    }

    // Por mês e ano
    public function minhas_ass_mes_ano()
    {

        $mes = date("m");
        $ano = date("Y");
        $status = 'todas';

        if (filter_input(INPUT_POST, 'mes') && filter_input(INPUT_POST, 'ano')) {
            $mes = filter_input(INPUT_POST, 'mes');
            $ano = filter_input(INPUT_POST, 'ano');
            $status = filter_input(INPUT_POST, 'status_assistencia');
        }

        $dados = $this->minhas_assistencias_by_filtros($dia = null, $mes, $ano, $dt_inicial = null, $dt_final = null, $status);

        $dados['titulo_botao'] = 'mes_ano';

        $this->view('representante/index', $dados);
    }

    // Por mês e ano
    public function minhas_ass_periodo()
    {
        if (!filter_input(INPUT_POST, 'dt_inicial') || !filter_input(INPUT_POST, 'dt_final')) {
            return $this->minhas_ass_mes_ano();
        }

        $dt_inicial = filter_input(INPUT_POST, 'dt_inicial');
        $dt_final = filter_input(INPUT_POST, 'dt_final');
        $status = filter_input(INPUT_POST, 'status_assistencia');

        $dados = $this->minhas_assistencias_by_filtros($dia = null, $mes = null, $ano = null, $dt_inicial, $dt_final, $status);

        $dados['titulo_botao'] = 'periodo';

        $this->view('representante/index', $dados);
    }

    public function all_representantes()
    {

        $representantes = $this->representanteModel->allRepresentantes();

        if ($representantes['erro'] == '' && $representantes['representantes'] != '') {

            // $representantes['representantes']['id_coordenadoria'] = 'nome';

            $dados = [
                'representantes' => json_encode($representantes['representantes']),
                'hoje'       => date('d/m/Y'),
                'num_registros' => count($representantes['representantes'])
            ];
        }

        $this->view('representante/all_representantes', $dados);
    }
}
