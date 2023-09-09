<?php

class Coordenacao extends Controller
{

    private $sessao_acesso;
    private $home;

    private $userModel;
    private $representanteModel;
    private $coordenacaoModel;
    private $assistenciaModel;
    private $assistenciaUpModel;
    private $cidadaoModel;

    public function __construct()
    {

        if (!Sessao::estaLogado()) :
        // URL::redirect('users/login_email');
        else :
            $this->sessao_acesso = Sessao::sessaoUser();

            if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
                $this->home = URL . '/coordenacao';
            } else {
                // $this->userController = $this->controller('Users');
                // $this->userController->index_acesso();
                // exit();
            }

        endif;

        $this->userModel = $this->model('UserModel');
        $this->representanteModel = $this->model('RepresentanteModel');
        $this->coordenacaoModel = $this->model('CoordenacaoModel');
        $this->assistenciaModel = $this->model('AssistenciaModel');
        $this->assistenciaUpModel = $this->model('AssistenciaUpModel');
        $this->cidadaoModel = $this->model('CidadaoModel');
    }

    public function index()
    {

        $id_coordenadoria = $_SESSION['user']['id_coordenadoria'];

        //CONTADORES
        $count_geral_res = $this->assistenciaUpModel->countUpByCoordenadoria($id_coordenadoria, 'geral');
        $count_geral_nao_finalizadas_res = $this->assistenciaUpModel->countUpByCoordenadoria($id_coordenadoria, 'nao_finalizadas');
        $count_geral_finalizadas_res = $this->assistenciaUpModel->countUpByCoordenadoria($id_coordenadoria, 'finalizadas');

        $count_assistencias = $count_geral_res['count'];
        $count_nao_finalizadas = $count_geral_nao_finalizadas_res['count'];
        $count_finalizadas = $count_geral_finalizadas_res['count'];
        $count_novas_mes_atual = 0;
        $count_up_mes_atual = 0;

        //Updates recentes
        $array_ups = [];
        $updates_res = $this->assistenciaUpModel->allUpdatesRecentesByIdCoordenadoria($id_coordenadoria);
        if ($updates_res['erro'] == '' && count($updates_res['updates']) > 0) {
            $updates = $updates_res['updates'];

            foreach ($updates as $up) {
                $primeiro_registro_res = $this->assistenciaUpModel->getAssistencia($up['id_assistencia']);
                if ($primeiro_registro_res['erro'] == '') {
                    $primeiro_registro = $primeiro_registro_res['assistencia'];
                }

                $dt_p_r = explode('-', $primeiro_registro['date_at']);

                $dt_ultima_at = new DateTime($up['updated_at']);
                $dt_ultima_atualizacao = $dt_ultima_at->format('d/m/Y');

                $data_primeiro_registro = $dt_p_r[2] . '/' . $dt_p_r[1] . '/' . $dt_p_r[0];
                $array_ups[] = [
                    'id_primeiro_registro' => $up['id_assistencia'],
                    'dt_primeiro_registro' => $data_primeiro_registro,
                    'desc_primeiro_registro' => $primeiro_registro['descricao'],
                    'dt_ultima_atualizacao' => $dt_ultima_atualizacao,
                    'desc_ultima_atualização' => $up['status_compl_updated'],
                    'status_atual' => $up['status_updated'],
                    'nome_operador' => $up['name_updated_by']
                ];

                //mês atual
                $mes_up = $dt_ultima_at->format('m');

                //Contadores atual
                if ($up['status_updated'] == 'Iniciada') {
                    if ($mes_up == date('m')) {
                        $count_novas_mes_atual += 1;
                    }
                }
                if ($up['status_updated'] != 'Iniciada') {
                    if ($mes_up == date('m')) {
                        $count_up_mes_atual += 1;
                    }
                }
            }
        }

        $dados = [
            'count_assistencias' => $count_assistencias,
            'count_nao_finalizadas' => $count_nao_finalizadas,
            'count_finalizadas'     => $count_finalizadas,
            'count_novas_mes_atual' => $count_novas_mes_atual,
            'count_up_mes_atual'    => $count_up_mes_atual,
            'assistencias'       => $array_ups,
        ];

        $this->view('coordenacao/index', $dados);
    }

    public function all_representantes()
    {

        $representantes = $this->representanteModel->allRepresentantes();

        $dados = [
            'representantes' => json_encode($representantes['representantes']),
            'hoje'       => date('d/m/Y'),
            'num_registros' => count($representantes['representantes'])
        ];

        $this->view('representante/all_representantes', $dados);
    }

    public function all_coordenadorias()
    {

        $dados['coordenadorias'] = [];

        $coordenadorias_result = $this->coordenacaoModel->allCoordenadorias();
        $coordenadores_result = $this->coordenacaoModel->allCoordenadores();

        if ($coordenadorias_result['coordenadorias'] != '' && $coordenadores_result['coordenadores'] != '') {

            $coordenadorias = $coordenadorias_result['coordenadorias'];
            $coordenadores = $coordenadores_result['coordenadores'];

            foreach ($coordenadorias as $coordenadoria) {

                $coordenadores_array = [];

                foreach ($coordenadores as $coordenador) {
                    if ($coordenador['id_coordenadoria'] == $coordenadoria['id']) {
                        $coordenadores_array[] = [
                            'id_coordenador' => $coordenador['id'],
                            'nome_coordenador' => $coordenador['nome']
                        ];
                    }
                    // $coordenadores_array[] = 
                }

                $dados['coordenadorias'][] = [
                    'id_coordenadoria'   => $coordenadoria['id'],
                    'nome_coordenadoria' => $coordenadoria['nome'],
                    'coordenadores'      => $coordenadores_array
                ];
            }
        }

        $dados['home'] = $this->home;

        $this->view('coordenacao/all_coordenadorias', $dados);
    }

    public function all_coordenadores()
    {

        $coord_res = $this->coordenacaoModel->allCoordenadores();
        if ($coord_res['erro'] == '' && $coord_res['coordenadores'] != '') {
            $coordenadores = $coord_res['coordenadores'];
        }

        $dados = [
            'coordenadores' => json_encode($coordenadores),
            'num_registros' => count($coordenadores),
            'hoje'          => date('d/m/Y'),
            'home'          => $this->home
        ];

        $this->view('coordenacao/all_coordenadores', $dados);
    }

    /**
     * Etitar Coordenadoria
     */
    public function edit($id)
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $dados = [
                'id' => trim($form['id']),
                'nome' => trim($form['nome']),
            ];

            $edit = $this->coordenacaoModel->editCoordenadoria($dados);
            if ($edit['erro'] == '' && $edit['num_rows'] != 0) {
                Sessao::mensagem('coord', 'Coordenadoria editada com sucesso!');
            } elseif ($edit['erro'] == '' && $edit['num_rows'] == 0) {
                Sessao::mensagem('coord', 'Não há novos dados para edição!', 'alert alert-warning');
            } else {
                Sessao::mensagem('coord', 'ERRO ao editar Coordenadoria, tente mais tarde!', 'alert alert-danger');
            }
        } else {

            $coord = $this->coordenacaoModel->getCoordenadoria($id);
            if ($coord['erro'] == '' && $coord['coordenadoria'] != '') {

                $dados = [
                    'nome' => $coord['coordenadoria']['nome'],
                    'id'   => $coord['coordenadoria']['id']
                ];
            }
        }

        $this->view('coordenacao/edit', $dados);
    }

    // GERENCIAMENTO DE ASSISTÊNCIAS DESTA COORDENADORIA
    //Geral - recentes
    public function assistencias()
    {
        $dados = $this->assistencias_recentes_generica('todas');

        $this->view('coordenacao/assistencias', $dados);
    }

    public function assistencias_nao_finalizadas()
    {
        $dados = $this->assistencias_recentes_generica('nao_finalizadas');

        $this->view('coordenacao/assistencias', $dados);
    }

    public function assistencias_finalizadas()
    {
        $dados = $this->assistencias_recentes_generica('finalizadas');
        $this->view('coordenacao/assistencias', $dados);
    }

    //FUNÇÃO GENÉRICA ASSISTENCIAS RECENTES (todas, não finalizadas e finalizadas)
    public function assistencias_recentes_generica($status)
    {

        $id_coordenadoria = $_SESSION['user']['id_coordenadoria'];

        if ($status == 'todas') {
            $titulo = '<b>REGISTROS RECENTES EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS</b>';
            $titulo_botao = 'recentes';
        }
        if ($status == 'nao_finalizadas') {
            $titulo = '<b>REGISTROS RECENTES EM ASSISTÊNCIAS NÃO FINALIZADAS</b>';
            $titulo_botao = 'não finalizadas';
        }
        if ($status == 'finalizadas') {
            $titulo = '<b>REGISTROS RECENTES EM ASSISTÊNCIAS FINALIZADAS</b>';
            $titulo_botao = 'finalizadas';
        }

        $updates_res = $this->assistenciaUpModel->allUpdatesRecentesByIdCoordenadoria($id_coordenadoria, $status);

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

                //status_assist
                $st_primeiro_reg = $primeiro_registro['status_assist'];

                //descrição_complemeto do primeiro registro
                $desc_comp_primeiro_reg = $primeiro_registro['descricao_complemento'];
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

            if ($status == 'nao_finalizadas') {
                if ($primeiro_registro['status_assist'] != 'Finalizada') {
                    $assistencias[] = [
                        'data' => $data,
                        'primeiro_registro' => $primeiro_registro,
                        'id_primeiro_registro' => $primeiro_registro['id'],
                        'status_assist'        => $st_primeiro_reg, // $primeiro_registro['status_assist'],
                        'status_updated'       => $updates[$i]['status_updated'],
                        'status_compl_updated'       => $updates[$i]['status_compl_updated'],
                        'nome_cidadao' => $nome_cidadao,
                        'id_cidadao'         => $primeiro_registro['id_cidadao'],
                        'tipo' => $tipo,
                        'nome_coordenadoria' => $nome_coordenadoria,
                        'id_coordenadoria' => $id_coordenadoria,
                        'descricao'          => $descricao,
                        'desc_comp_primeiro_reg' => $desc_comp_primeiro_reg,
                        'data_primeiro_registro' => $data_primeiro_registro
                    ];
                }
            } else {

                $assistencias[] = [
                    'data' => $data,
                    'primeiro_registro' => $primeiro_registro,
                    'id_primeiro_registro' => $primeiro_registro['id'],
                    'status_assist'        => $st_primeiro_reg, // $primeiro_registro['status_assist'],
                    'status_updated'       => $updates[$i]['status_updated'],
                    'status_compl_updated'       => $updates[$i]['status_compl_updated'],
                    'nome_cidadao' => $nome_cidadao,
                    'id_cidadao'         => $primeiro_registro['id_cidadao'],
                    'tipo' => $tipo,
                    'nome_coordenadoria' => $nome_coordenadoria,
                    'id_coordenadoria' => $id_coordenadoria,
                    'descricao'          => $descricao,
                    'desc_comp_primeiro_reg' => $desc_comp_primeiro_reg,
                    'data_primeiro_registro' => $data_primeiro_registro
                ];
            }
        }

        // $assistencias = $titulo;
        $num_registros = 'Registros: ' . count($updates);

        $count_assistidos = count($array_assistidos);

        $meses = Times::meses();

        //Contadores
        $count_geral_res = $this->assistenciaUpModel->countUpByCoordenadoria($id_coordenadoria, 'geral');
        $count_geral_nao_finalizadas_res = $this->assistenciaUpModel->countUpByCoordenadoria($id_coordenadoria, 'nao_finalizadas');
        $count_geral_finalizadas_res = $this->assistenciaUpModel->countUpByCoordenadoria($id_coordenadoria, 'finalizadas');

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
            'count_geral'      => $count_geral_res['count'],
            'count_geral_nao_finalizadas'      => $count_geral_nao_finalizadas_res['count'],
            'count_geral_finalizadas'      => $count_geral_finalizadas_res['count'],

            'titulo_relatorio'        => '<b>RELATÓRIO</b> ' . $titulo

        ];

        $this->view('coordenacao/assistencias', $dados);
    }

    // Geral - Não Finalizadas
    public function ass_nao_finalizadas()
    {

        $id_coordenadoria = $_SESSION['user']['id_coordenadoria'];

        $dados = $this->assistenciaModel->assNaoFinalizadasByCoordenadoria($id_coordenadoria);

        $dados['anos'] = Times::anos_12();
        $meses = Times::meses();
        $dados['meses'] = $meses;

        $dados['home'] = $this->home;

        $this->view('coordenacao/assistencias', $dados);
    }

    // Geral - Finalizadas
    public function ass_finalizadas()
    {

        $id_coordenadoria = $_SESSION['user']['id_coordenadoria'];

        $dados = $this->assistenciaModel->assFinalizadasByCoordenadoria($id_coordenadoria);

        $dados['anos'] = Times::anos_12();
        $meses = Times::meses();
        $dados['meses'] = $meses;

        $dados['home'] = $this->home;

        $this->view('coordenacao/assistencias', $dados);
    }

    //Filtros de busca de Assistências
    public function organiza_assistencias_by_filtros($dia, $mes, $ano, $dt_inicial, $dt_final, $status)
    {

        $id_coordenadoria = $_SESSION['user']['id_coordenadoria'];

        //por data
        if ($dia && $mes && $ano) {
            $updates_res = $this->assistenciaUpModel->allUpdatesByDataByCoordenadoria($id_coordenadoria, $dia, $mes, $ano, $status);
            $titulo = '<b>REGISTROS EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS EM ' . $dia . '/' . $mes . '/' . $ano . '</b>';
        }
        //mês e ano
        if (!$dia && $mes && $ano) {
            $updates_res = $this->assistenciaUpModel->allUpdatesByMesAnoByCoordenadoria($id_coordenadoria, $mes, $ano, $status);
            $titulo = '<b>REGISTROS EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS NO MÊS ' . Times::mes_string($mes) . '/' . $ano . '</b>';
        }
        //período
        if ($dt_inicial && $dt_final) {
            $updates_res = $this->assistenciaUpModel->allUpdatesByPeriodoByCoordenadoria($id_coordenadoria, $dt_inicial, $dt_final, $status);
            //data inicial
            $dti = new DateTime($dt_inicial);
            $dt_i = $dti->format('d/m/Y');
            //data final
            $dtf = new DateTime($dt_final);
            $dt_f = $dtf->format('d/m/Y');

            $titulo = '<b>REGISTROS EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS ENTRE ' . $dt_i . ' e ' . $dt_f . '</b>';
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

                //status_assist
                $st_primeiro_reg = $primeiro_registro['status_assist'];

                //descrição_complemeto do primeiro registro
                $desc_comp_primeiro_reg = $primeiro_registro['descricao_complemento'];
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
                'status_assist'        => $st_primeiro_reg, // $primeiro_registro['status_assist'],
                'status_updated'       => $updates[$i]['status_updated'],
                'status_compl_updated'       => $updates[$i]['status_compl_updated'],
                'nome_cidadao' => $nome_cidadao,
                'id_cidadao'         => $primeiro_registro['id_cidadao'],
                'tipo' => $tipo,
                'nome_coordenadoria' => $nome_coordenadoria,
                'id_coordenadoria' => $id_coordenadoria,
                'descricao'          => $descricao,
                'desc_comp_primeiro_reg' => $desc_comp_primeiro_reg,
                'data_primeiro_registro' => $data_primeiro_registro
            ];
        }

        // $assistencias = $titulo;
        $num_registros = 'Registros: ' . count($updates);

        $count_assistidos = count($array_assistidos);

        $meses = Times::meses();

        //Contadores
        $count_geral_res = $this->assistenciaUpModel->countUpByCoordenadoria($id_coordenadoria, 'geral');
        $count_geral_nao_finalizadas_res = $this->assistenciaUpModel->countUpByCoordenadoria($id_coordenadoria, 'nao_finalizadas');
        $count_geral_finalizadas_res = $this->assistenciaUpModel->countUpByCoordenadoria($id_coordenadoria, 'finalizadas');

        $dados = [
            'titulo'          => $titulo,
            'num_registros'   => $num_registros,
            'count_assistidos' => 'Assistidos: ' . $count_assistidos,
            'anos'             => Times::anos_12(),
            'updates'         => $updates,
            'meses'            => $meses,
            'home'             => $this->home,

            'assistencias'     => $assistencias,
            'count_geral'      => $count_geral_res['count'],
            'count_geral_nao_finalizadas'      => $count_geral_nao_finalizadas_res['count'],
            'count_geral_finalizadas'      => $count_geral_finalizadas_res['count'],

            'titulo_relatorio'        => '<b>RELATÓRIO</b> ' . $titulo,

        ];

        return $dados;
    }

    // Geral por data
    public function ass_por_data()
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



        $dados = $this->organiza_assistencias_by_filtros($dia, $mes, $ano, $dt_inicial = null, $dt_final = null, $status);

        $dados['titulo_botao'] = 'por_data';

        $this->view('coordenacao/assistencias', $dados);
    }

    // Geral por mês e ano
    public function ass_mes_ano()
    {

        $mes = date('m');
        $ano = date('Y');
        $status = 'todas';

        if (filter_input(INPUT_POST, 'mes') && filter_input(INPUT_POST, 'ano')) {
            $mes = filter_input(INPUT_POST, 'mes');
            $ano = filter_input(INPUT_POST, 'ano');
            $status = filter_input(INPUT_POST, 'status_assistencia');
        }

        $dados = [];

        $dados = $this->organiza_assistencias_by_filtros($dia = null, $mes, $ano, $dt_inicial = null, $dt_final = null, $status);

        $dados['titulo_botao'] = 'mes_ano';

        $this->view('coordenacao/assistencias', $dados);
    }

    // Geral por mês e ano
    public function ass_periodo()
    {

        if (!filter_input(INPUT_POST, 'dt_inicial') && !filter_input(INPUT_POST, 'dt_final')) {
            return $this->ass_mes_ano();
        }

        $dt_inicial = filter_input(INPUT_POST, 'dt_inicial');
        $dt_final = filter_input(INPUT_POST, 'dt_final');
        $status = filter_input(INPUT_POST, 'status_assistencia');

        $dados = $this->organiza_assistencias_by_filtros($dia = null, $mes = null, $ano = null, $dt_inicial, $dt_final, $status);

        $dados['titulo_botao'] = 'periodo';

        $this->view('coordenacao/assistencias', $dados);
    }

    //XXXXXXXXXXXXXXXXXXXXXXXXXXXXX FIM ASSISTENCIAS DESTA COORDENADORIA XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    //MINHAS ASSISTÊNCIAS
    //GENÉRICA RECENTES
    public function minhas_assistencias_recentes_generica($status)
    {

        $id_operador = $_SESSION['user']['id'];


        if ($status == 'nao_finalizadas') {
            $titulo = '<b>MEUS REGISTROS RECENTES EM ASSISTÊNCIAS NÃO FINALIZADAS</b>';
            $titulo_botao = 'não finalizadas';
        }
        if ($status == 'todas') {
            $titulo = '<b>MEUS REGISTROS RECENTES EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS</b>';
            $titulo_botao = 'recentes';
        }
        if ($status == 'finalizadas') {
            $titulo = '<b>MEUS REGISTROS RECENTES EM ASSISTÊNCIAS FINALIZADAS</b>';
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

                //status_assist
                $st_primeiro_reg = $primeiro_registro['status_assist'];

                //descrição_complemeto do primeiro registro
                $desc_comp_primeiro_reg = $primeiro_registro['descricao_complemento'];
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

            if ($status == 'nao_finalizadas') {
                if ($primeiro_registro['status_assist'] != 'Finalizada') {
                    $assistencias[] = [
                        'data' => $data,
                        'primeiro_registro' => $primeiro_registro,
                        'id_primeiro_registro' => $primeiro_registro['id'],
                        'status_assist'        => $st_primeiro_reg, // $primeiro_registro['status_assist'],
                        'status_updated'       => $updates[$i]['status_updated'],
                        'status_compl_updated'       => $updates[$i]['status_compl_updated'],
                        'nome_cidadao' => $nome_cidadao,
                        'id_cidadao'         => $primeiro_registro['id_cidadao'],
                        'tipo' => $tipo,
                        'nome_coordenadoria' => $nome_coordenadoria,
                        'id_coordenadoria' => $id_coordenadoria,
                        'descricao'          => $descricao,
                        'desc_comp_primeiro_reg' => $desc_comp_primeiro_reg,
                        'data_primeiro_registro' => $data_primeiro_registro
                    ];
                }
            } else {

                $assistencias[] = [
                    'data' => $data,
                    'primeiro_registro' => $primeiro_registro,
                    'id_primeiro_registro' => $primeiro_registro['id'],
                    'status_assist'        => $st_primeiro_reg, // $primeiro_registro['status_assist'],
                    'status_updated'       => $updates[$i]['status_updated'],
                    'status_compl_updated'       => $updates[$i]['status_compl_updated'],
                    'nome_cidadao' => $nome_cidadao,
                    'id_cidadao'         => $primeiro_registro['id_cidadao'],
                    'tipo' => $tipo,
                    'nome_coordenadoria' => $nome_coordenadoria,
                    'id_coordenadoria' => $id_coordenadoria,
                    'descricao'          => $descricao,
                    'desc_comp_primeiro_reg' => $desc_comp_primeiro_reg,
                    'data_primeiro_registro' => $data_primeiro_registro
                ];
            }
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

            'titulo_relatorio'        => '<b>RELATÓRIO</b> ' . $titulo,

        ];

        return $dados;
    }


    public function minhas_assistencias()
    {

        $dados = $this->minhas_assistencias_recentes_generica('todas');

        $this->view('coordenacao/minhas_assistencias', $dados);
    }

    // Não Finalizadas
    public function minhas_ass_nao_finalizadas()
    {

        $dados = $this->minhas_assistencias_recentes_generica('nao_finalizadas');

        $this->view('coordenacao/minhas_assistencias', $dados);
    }

    // Finalizadas
    public function minhas_ass_finalizadas()
    {

        $dados = $this->minhas_assistencias_recentes_generica('finalizadas');

        $this->view('coordenacao/minhas_assistencias', $dados);
    }

    //Filtros de busca de Assistências
    public function minhas_assistencias_by_filtros($dia, $mes, $ano, $dt_inicial, $dt_final, $status)
    {

        $status = 'todas';

        $id_operador = $_SESSION['user']['id'];

        //por data
        if ($dia && $mes && $ano) {
            $updates_res = $this->assistenciaModel->updatesByOperadorByData($id_operador, $dia, $mes, $ano, $status);
            $titulo = '<b>MEUS REGISTROS EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS EM ' . $dia . '/' . $mes . '/' . $ano . '</b>';
        }
        //mês e ano
        if (!$dia && $mes && $ano) {
            $updates_res = $this->assistenciaModel->updatesByOperadorMesAno($id_operador, $mes, $ano, $status);
            $titulo = '<b>MEUS REGISTROS EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS EM ' . Times::mes_string($mes) . '/' . $ano . '</b>';
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

            $titulo = '<b>MEUS REGISTROS EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS ENTRE ' . $dt_i . ' e ' . $dt_f . '</b>';
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

                //status_assist
                $st_primeiro_reg = $primeiro_registro['status_assist'];

                //descrição_complemeto do primeiro registro
                $desc_comp_primeiro_reg = $primeiro_registro['descricao_complemento'];
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
                'status_assist'        => $st_primeiro_reg, // $primeiro_registro['status_assist'],
                'status_updated'       => $updates[$i]['status_updated'],
                'status_compl_updated'       => $updates[$i]['status_compl_updated'],
                'nome_cidadao' => $nome_cidadao,
                'id_cidadao'         => $primeiro_registro['id_cidadao'],
                'tipo' => $tipo,
                'nome_coordenadoria' => $nome_coordenadoria,
                'id_coordenadoria' => $id_coordenadoria,
                'descricao'          => $descricao,
                'desc_comp_primeiro_reg' => $desc_comp_primeiro_reg,
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

            'titulo_relatorio'        => '<b>RELATÓRIO</b> ' . $titulo,

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

        $this->view('coordenacao/minhas_assistencias', $dados);
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

        $this->view('coordenacao/minhas_assistencias', $dados);
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

        $this->view('coordenacao/minhas_assistencias', $dados);
    }

    public function nav_filtros()
    {

        $dados['id_coordenadoria'] = $_SESSION['user']['id_coordenadoria'];

        $this->view('coordenacao/nav_filtros', $dados);
    }

    //ERROS
    public function filtro_by_operador()
    {

        //fazer busca em Ajax.php
        //selecione na tabela assistencias_update os ids de operadores onde a assistencia_update.id_coordenadoria = id_coordenadoria

        //

        //select * from assistencias_update where id_coordenadoria = id_coordenadoria

        $this->view('coordenacao/filtro_by_operador');
    }
}
