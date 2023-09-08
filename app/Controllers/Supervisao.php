<?php

class Supervisao extends Controller
{

    private $sessao_acesso;
    private $home;

    private $representanteModel;
    private $coordenacaoModel;
    private $supervisaoModel;
    private $assistenciaModel;
    private $assistenciaUpModel;
    private $cidadaoModel;

    public function __construct()
    {

        if (!Sessao::estaLogado()) :
        
        else :
            $this->sessao_acesso = Sessao::sessaoUser();

            if ($this->sessao_acesso['acesso'] == 'Supervisão') {
                $this->home = URL . '/supervisao';
            } 

        endif;

        $this->representanteModel = $this->model('RepresentanteModel');
        $this->coordenacaoModel = $this->model('CoordenacaoModel');
        $this->supervisaoModel = $this->model('SupervisaoModel');
        $this->assistenciaModel = $this->model('AssistenciaModel');
        $this->assistenciaUpModel = $this->model('AssistenciaUpModel');
        $this->cidadaoModel = $this->model('CidadaoModel');
    }

    public function index()
    {

        //ASSISTÊNCIAS
        $count_assistencias = 0;
        $count_assistencias_nao_finalizadas = 0;
        $count_assistencias_finalizadas = 0;
        $count_assistencias_mes_atual = 0;
        $count_assistencias_updates_mes_atual = 0;

        // todas Assistências
        $count_assistencias_res = $this->assistenciaModel->countUpdatesTodas();
        if ($count_assistencias_res['erro'] == '' && $count_assistencias_res['count'] != '') {
            $count_assistencias = $count_assistencias_res['count'];
        }

        // Assistências não finalizadas
        $count_assistencias_nao_finalizadas_res = $this->assistenciaModel->countUpdatesNaoFinalizadas();
        if ($count_assistencias_nao_finalizadas_res['erro'] == '' && $count_assistencias_nao_finalizadas_res['count'] != '') {
            $count_assistencias_nao_finalizadas = $count_assistencias_nao_finalizadas_res['count'];
        }

        // Assistências finalizadas
        $count_assistencias_finalizadas_res = $this->assistenciaModel->countUpdatesFinalizadas();
        if ($count_assistencias_finalizadas_res['erro'] == '' && $count_assistencias_finalizadas_res['count'] != '') {
            $count_assistencias_finalizadas = $count_assistencias_finalizadas_res['count'];
        }

        // Assistências registradas no mês atual
        $mes_atual = date('m');
        $count_assistencias_mes_atual_res = $this->assistenciaModel->countAssistenciasRegistradasMesAtual($mes_atual);
        if ($count_assistencias_mes_atual_res['erro'] == '' && $count_assistencias_mes_atual_res['count'] != '') {
            $count_assistencias_mes_atual = $count_assistencias_mes_atual_res['count'];
        }

        // Assistências atualizadas no mês atual
        $count_assistencias_updates_mes_atual_res = $this->assistenciaModel->countAssistenciasUpdatesMesAtual($mes_atual);
        if ($count_assistencias_updates_mes_atual_res['erro'] == '' && $count_assistencias_updates_mes_atual_res['count'] != '') {
            $count_assistencias_updates_mes_atual = $count_assistencias_updates_mes_atual_res['count'];
        }

        //CIDADÃOS
        $count_cidadaos = '';
        $count_cidadaos_cadastrados_no_mes = '';

        //Total de Cidadãos registrados
        $count_cidadaos_res = $this->cidadaoModel->countCidadaosTodos();
        if ($count_cidadaos_res['erro'] == '' && $count_cidadaos_res['count'] != '') {
            $count_cidadaos = $count_cidadaos_res['count'];
        }

        // Cidadãos cadastrados no mês atual
        $count_cidadaos_cadastrados_no_mes_res = $this->cidadaoModel->countCadastradosMes($mes_atual);
        if ($count_cidadaos_cadastrados_no_mes_res['erro'] == '' && $count_cidadaos_cadastrados_no_mes_res['count'] != '') {
            $count_cidadaos_cadastrados_no_mes = $count_cidadaos_cadastrados_no_mes_res['count'];
        }

        //COORDENADORIAS
        $count_coordenadorias = '';
        $count_coordenadores = '';

        //Total de Coordenadorias
        $count_coordenadorias_res = $this->coordenacaoModel->countCoordenadorias();
        if ($count_coordenadorias_res['erro'] == '' && $count_coordenadorias_res['count'] != '') {
            $count_coordenadorias = $count_coordenadorias_res['count'];
        }

        //Total de Coordenadores
        $count_coordenadores_res = $this->coordenacaoModel->countCoordenadores();
        if ($count_coordenadores_res['erro'] == '' && $count_coordenadores_res['count'] != '') {
            $count_coordenadores = $count_coordenadores_res['count'];
        }

        //OPERADORES
        $count_all_operadores = '';
        $count_all_representantes = '';
        // $count_all_supervisores = '';
        // $count_all_admin = '';

        //Total de Operadores
        // $count_all_operadores_res = $this->operadorModel->countAllOperadores();
        // if ($count_all_operadores_res['erro'] == '' && $count_all_operadores_res['count'] != '') {
        //     $count_all_operadores = $count_all_operadores_res['count'];
        // }

        //Total de Representantes
        $count_all_representantes_res = $this->representanteModel->countAllRepresentantes();
        if ($count_all_representantes_res['erro'] == '' && $count_all_representantes_res['count'] != '') {
            $count_all_representantes = $count_all_representantes_res['count'];
        }

        //Total de Supervisores
        // $count_all_supervisores_res = $this->supervisaoModel->countAllSupervisores();
        // if ($count_all_supervisores_res['erro'] == '' && $count_all_supervisores_res['count'] != '') {
        //     $count_all_supervisores = $count_all_supervisores_res['count'];
        // }

        //Total de Administradores
        // $count_all_admin_res = $this->adminModel->countAllAdministradores();
        // if ($count_all_admin_res['erro'] == '' && $count_all_admin_res['count'] != '') {
        //     $count_all_admin = $count_all_admin_res['count'];
        // }


        $dados = [
            'mes_ano_atual' => date('MY'),
            // Assistências
            'count_assistencias' => $count_assistencias,
            'count_assistencias_nao_finalizadas' => $count_assistencias_nao_finalizadas,
            'count_assistencias_finalizadas'     => $count_assistencias_finalizadas,
            'count_assistencias_mes_atual'       => $count_assistencias_mes_atual,
            'count_assistencias_updates_mes_atual' => $count_assistencias_updates_mes_atual,
            // Cidadãos
            'count_cidadaos'    => $count_cidadaos,
            'count_cidadaos_cadastrados_no_mes' => $count_cidadaos_cadastrados_no_mes,

            // Coordenação
            'count_coordenadorias'                  => $count_coordenadorias,
            'count_coordenadores'                   => $count_coordenadores,

            //Operadores
            'count_all_operadores'                  => $count_all_operadores,
            'count_all_representantes'              => $count_all_representantes,
            // 'count_all_supervisores'                => $count_all_supervisores,
            // 'count_all_admin'                       => $count_all_admin,

            'assistencias_todas_mes_atual' => 12
        ];

        $this->view('supervisao/index', $dados);
    }

    public function create_coordenadoria()
    {

        $dados['home'] = $this->home;

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {
            $dados = [
                'created_by' => $_SESSION['user']['nome'],
                'nome'       => $form['nome'],
                'nome_erro'  => ''
            ];

            if (empty(trim($dados['nome']))) {
                $dados['nome_erro'] = 'O nome não pode ser vazio';
            } else {

                $create = $this->coordenacaoModel->novaCoordenadoria($dados);
                if ($create == 'Esta Coordenadoria já existe!') {
                    Sessao::mensagem('coord', $create, 'alert alert-danger');
                    return $this->all_coordenadorias();
                    exit();
                }

                if ($create['erro'] == '' && $create['id'] != '') {

                    Sessao::mensagem('coord', 'Coordenadoria criada com sucesso!');

                    return $this->all_coordenadorias();
                    exit();
                } else {
                    Sessao::mensagem('coord', 'ERRO ao criar Coordenadoria, tente mais tarde!', 'alert alert-danger');
                }
            }
        } else {

            $dados = [
                'created_by' => $_SESSION['user']['nome'],
                'nome'       => '',
                'nome_erro'  => ''
            ];
        }

        $this->view('supervisao/create_coordenadoria', $dados);
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

        $this->view('supervisao/all_coordenadorias', $dados);
    }

    /**
     * Coordenadoria para admin e supervisor
     */
    public function coordenadoria($id_coordenadoria, $filtro = null)
    {

        //Coordenadoria
        $dados_res = $this->coordenacaoModel->getDadosCoordenadoria($id_coordenadoria);
        if ($dados_res['erro'] != '') {
            $dados['erro'] = 'ERRO em Coordenadoria. ' . $dados_res['erro'];
            Sessao::mensagem('coordenadoria', 'ERRO ao buscar Coordenadoria; tente mais tarde! <a href="' . URL . '/coordenacao/all_coordenadorias" class="btn btn-success">OK</a>', 'alert alert-danger');
        }
        $coordenadoria = $dados_res['coordenadoria'];
        $coordenadores = $dados_res['coordenadores'];
        $nome_coordenadoria = $coordenadoria['nome'];
        $id_coordenadoria = $coordenadoria['id'];

        //Updates
        if ($filtro == 'recentes') {
            $updates_res = $this->assistenciaUpModel->allUpdatesRecentesByIdCoordenadoria($id_coordenadoria);
            $titulo = '<b>Assistências recentes da Coordenadoria de ' . $coordenadoria['nome'] . '</b>';
        }
        if ($filtro == 'data') {
            $input_data = filter_input(INPUT_POST, 'por_data');
            $ex_data = explode('-', $input_data);
            $dia = $ex_data[2];
            $mes = $ex_data[1];
            $ano = $ex_data[0];
            $updates_res = $this->assistenciaUpModel->allUpdatesByDataByCoordenadoria($id_coordenadoria, $dia, $mes, $ano);
            $titulo = '<b>Assistências registradas em ' . $dia . '/' . $mes . '/' . $ano . ' - ' . $coordenadoria['nome'] . '</b>';
        }
        if ($filtro == 'mes_ano') {
            $mes = filter_input(INPUT_POST, 'mes');
            $ano = filter_input(INPUT_POST, 'ano');
            $updates_res = $this->assistenciaUpModel->allUpdatesByMesAnoByCoordenadoria($id_coordenadoria, $mes, $ano);
            $titulo = '<b>Assistências registradas no mês ' . $mes . '/' . $ano . ' - ' . $coordenadoria['nome'] . '</b>';
        }
        if ($filtro == 'periodo') {
            $dt_inicial = filter_input(INPUT_POST, 'dt_inicial');
            $dt_final = filter_input(INPUT_POST, 'dt_final');
            $updates_res = $this->assistenciaUpModel->allUpdatesByPeriodoByCoordenadoria($id_coordenadoria, $dt_inicial, $dt_final);
            $ini = new DateTime($dt_inicial);
            $inicial = $ini->format('d/m/Y');
            $fin = new DateTime($dt_final);
            $final = $fin->format('d/m/Y');
            $titulo = '<b>Assistências registradas no período de ' . $inicial . ' à ' . $final . ' - ' . $coordenadoria['nome'] . '</b>';
        }
        if ($updates_res['erro'] != '') {
            // $dados = [];
            Sessao::mensagem('coordenadoria', 'ERRO ao buscar Assistências; tente mais tarde! <a href="' . URL . '/coordenacao/all_coordenadorias" class="btn btn-success">OK</a>', 'alert alert-danger');
            echo json_encode($updates_res);
            exit($this->view('admin/coordenadoria', $dados));
        }
        $updates = $updates_res['updates'];

        $assistencias = [];
        $array_assistidos = [];

        if (count($updates) > 0) {
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
                $nome_coordenadoria_up = $updates[$i]['nome_coordenadoria'];
                $id_coordenadoria_up = $updates[$i]['id_coordenadoria'];

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
                    'nome_coordenadoria' => $nome_coordenadoria_up,
                    'id_coordenadoria' => $id_coordenadoria_up,
                    'descricao'          => $descricao,
                    'data_primeiro_registro' => $data_primeiro_registro
                ];
            }
        }

        $dados = [

            'assistencias'       => $assistencias,
            'count_assistencias' => count($updates),
            'count_assistidos'         => '<b>Num assistidos: ' . count($array_assistidos) . '</b>',
            'num_registros'          => '<b>Num registros: ' . count($updates) . '</b>',
            'id_coordenadoria'         => $id_coordenadoria,
            'nome_coordenadoria'       => $nome_coordenadoria,
            'coordenadores'            => $coordenadores,
            'titulo'                   => $titulo,

            'anos'                      => Times::anos_12(),
            'meses'                     => Times::meses(),
            'home' => $this->home,
            'data' => date('d/m/Y'),

        ];

        $this->view('supervisao/coordenadoria', $dados);
    }

    /**
     * Etitar Coordenadoria
     */
    public function edit_coordenadoria($id)
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

        $this->view('supervisao/edit_coordenadoria', $dados);
    }

    public function all_coordenadores()
    {
        $coordenadores = $this->coordenacaoModel->allCoordenadores();

        $dados = [
            'coordenadores' => json_encode($coordenadores['coordenadores']),
            'hoje'       => date('d/m/Y'),
            'num_registros' => count($coordenadores['coordenadores']),
            'home'          => $this->home,
            'link_coordenadorias' => URL . '/supervisao/all_coordenadorias'
        ];

        $this->view('coordenacao/all_coordenadores', $dados);
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

    // GERENCIAMENTO DE ASSISTÊNCIAS
    //Geral - recentes
    public function assistencias()
    {

        $dados = [];

        $updates_res = $this->assistenciaUpModel->allUpdatesRecentes();
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

        //Título
        $titulo = '<b>ASSISTÊNCIAS RECENTES (todas)</b>';
        // $assistencias = $titulo;
        $num_registros = 'Registros: ' . count($updates);

        $count_assistidos = count($array_assistidos);

        $meses = Times::meses();

        $dados = [
            'titulo'          => $titulo,
            'num_registros'   => $num_registros,
            'count_assistidos' => 'Assistidos(as): ' . $count_assistidos,
            'anos'             => Times::anos_12(),
            'updates'         => $updates,
            'meses'            => $meses,
            'home'             => $this->home,
            'titulo_botao'     => 'recentes',

            'assistencias'     => $assistencias,

            //contadores geral
            'count_geral'      => $this->assistenciaUpModel->countUpdates('geral'),
            'count_geral_nao_finalizadas'      => $this->assistenciaUpModel->countUpdates('não finalizadas'),
            'count_geral_finalizadas'      => $this->assistenciaUpModel->countUpdates('finalizadas'),

        ];

        $this->view('supervisao/assistencias', $dados);
    }

    public function assistencias_nao_finalizadas()
    {

        $updates_res = $this->assistenciaUpModel->allUpdatesNaoFinalizadas();
        
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

        //Título
        $titulo = '<b>ASSISTÊNCIAS NÃO FINALIZADAS (recentes)</b>';
        // $assistencias = $titulo;
        $num_registros = 'Registros: ' . count($updates);

        $count_assistidos = count($array_assistidos);

        $meses = Times::meses();

        $dados = [
            'titulo'          => $titulo,
            'num_registros'   => $num_registros,
            'count_assistidos' => 'Assistidos(as): ' . $count_assistidos,
            'anos'             => Times::anos_12(),
            'updates'         => $updates,
            'meses'            => $meses,
            'home'             => $this->home,
            'titulo_botao'     => 'não finalizadas',

            'assistencias'     => $assistencias,

            //contadores geral
            'count_geral'      => $this->assistenciaUpModel->countUpdates('geral'),
            'count_geral_nao_finalizadas'      => $this->assistenciaUpModel->countUpdates('não finalizadas'),
            'count_geral_finalizadas'      => $this->assistenciaUpModel->countUpdates('finalizadas'),


        ];

        $this->view('supervisao/assistencias', $dados);
    }

    //Assistências finalizadas
    public function assistencias_finalizadas()
    {

        $dados = [];

        $updates_res = $this->assistenciaUpModel->allUpdatesFinalizadas();
        if ($updates_res['erro'] != '') {
            Sessao::mensagem('assistencias', $updates_res['erro']);
            return $this->view('admin/minhas_assistencias', $dados);
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

        //Título
        $titulo = '<b>ASSISTÊNCIAS FINALIZADAS (recentes)</b>';
        // $assistencias = $titulo;
        $num_registros = 'Registros: ' . count($updates);

        $count_assistidos = count($array_assistidos);

        $meses = Times::meses();

        $dados = [
            'titulo'          => $titulo,
            'num_registros'   => $num_registros,
            'count_assistidos' => 'Assistidos(as): ' . $count_assistidos,
            'anos'             => Times::anos_12(),
            'updates'         => $updates,
            'meses'            => $meses,
            'home'             => $this->home,
            'titulo_botao'     => 'finalizadas',

            'assistencias'     => $assistencias,

            //contadores geral
            'count_geral'      => $this->assistenciaUpModel->countUpdates('geral'),
            'count_geral_nao_finalizadas'      => $this->assistenciaUpModel->countUpdates('não finalizadas'),
            'count_geral_finalizadas'      => $this->assistenciaUpModel->countUpdates('finalizadas'),


        ];


        $this->view('supervisao/assistencias', $dados);
    }

    // Geral - Não Finalizadas
    public function ass_nao_finalizadas()
    {

        $dados = $this->assistenciaModel->assNaoFinalizadas();

        $dados['anos'] = Times::anos_12();
        $meses = Times::meses();
        $dados['meses'] = $meses;

        $dados['home'] = $this->home;

        $this->view('supervisao/assistencias', $dados);
    }

    // Geral - Finalizadas
    public function ass_finalizadas()
    {

        $dados = $this->assistenciaModel->assFinalizadas();

        $dados['anos'] = Times::anos_12();
        $meses = Times::meses();
        $dados['meses'] = $meses;

        $dados['home'] = $this->home;

        $this->view('supervisao/assistencias', $dados);
    }

    // Geral por data
    public function ass_por_data()
    {

        $dia = date("d");
        $mes = date("m");
        $ano = date("Y");

        if (filter_input(INPUT_POST, 'por_data')) {
            $por_data = filter_input(INPUT_POST, 'por_data');
            $explode = explode('-', $por_data);
            $dia = $explode[2];
            $mes = $explode[1];
            $ano = $explode[0];
        }

        $status = filter_input(INPUT_POST, 'status_assistencia');

        //Título
        $titulo = '<b>ASSISTÊNCIAS REGISTRADAS EM ' . $dia . '/' . $mes . '/' . $ano . ' (todas)</b>';
        if ($status == 'nao_finalizadas') {
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS EM ' . $dia . '/' . $mes . '/' . $ano . ' (não finalizadas)</b>';
        }
        if ($status == 'finalizadas') {
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS EM ' . $dia . '/' . $mes . '/' . $ano . ' (finalizadas)</b>';
        }
        if ($status == 'todas') {
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS EM ' . $dia . '/' . $mes . '/' . $ano . ' (todas)</b>';
        }

        $dados = [];

        $updates_res = $this->assistenciaUpModel->allUpdatesByData($dia, $mes, $ano, $status);
        // if ($updates_res['erro'] != '') {
        //     Sessao::mensagem('assistencias', $updates_res['erro']);
        //     return $this->view('admin/minhas_assistencias', $dados);
        // }
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

        $dados = [
            'titulo'          => $titulo,
            'num_registros'   => $num_registros,
            'count_assistidos' => 'Assistidos: ' . $count_assistidos,
            'anos'             => Times::anos_12(),
            'updates'         => $updates,
            'meses'            => $meses,
            'home'             => $this->home,
            'titulo_botao'     => 'por_data',

            'assistencias'     => $assistencias,

            //contadores geral
            'count_geral'      => $this->assistenciaUpModel->countUpdates('geral'),
            'count_geral_nao_finalizadas'      => $this->assistenciaUpModel->countUpdates('não finalizadas'),
            'count_geral_finalizadas'      => $this->assistenciaUpModel->countUpdates('finalizadas'),


        ];

        $this->view('supervisao/assistencias', $dados);
    }

    // Geral por mês e ano
    public function ass_mes_ano()
    {

        $mes = filter_input(INPUT_POST, 'mes');
        $ano = filter_input(INPUT_POST, 'ano');

        $dados = [];

        $status = filter_input(INPUT_POST, 'status_assistencia');

        //Título
        $titulo = '<b>ASSISTÊNCIAS REGISTRADAS NO MÊS ' . $mes . '/' . $ano . '</b>';
        if ($status == 'nao_finalizadas') {
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS NO MÊS ' . $mes . '/' . $ano . ' (não finalizadas)</b>';
        }
        if ($status == 'finalizadas') {
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS NO MÊS ' . $mes . '/' . $ano . ' (finalizadas)</b>';
        }
        if ($status == 'todas') {
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS NO MÊS ' . $mes . '/' . $ano . ' (todas)</b>';
        }

        $updates_res = $this->assistenciaUpModel->allUpdatesByMesAno($mes, $ano, $status);
        if ($updates_res['erro'] != '') {
            Sessao::mensagem('assistencias', $updates_res['erro']);
            return $this->view('admin/minhas_assistencias', $dados);
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

        $dados = [
            'titulo'          => $titulo,
            'num_registros'   => $num_registros,
            'count_assistidos' => 'Assistidos: ' . $count_assistidos,
            'anos'             => Times::anos_12(),
            'updates'         => $updates,
            'meses'            => $meses,
            'home'             => $this->home,
            'titulo_botao'     => 'mes_ano',

            'assistencias'     => $assistencias,

            //contadores geral
            'count_geral'      => $this->assistenciaUpModel->countUpdates('geral'),
            'count_geral_nao_finalizadas'      => $this->assistenciaUpModel->countUpdates('não finalizadas'),
            'count_geral_finalizadas'      => $this->assistenciaUpModel->countUpdates('finalizadas'),

        ];

        $this->view('supervisao/assistencias', $dados);
    }

    // Geral por mês e ano
    public function ass_periodo()
    {

        $dt_inicial = filter_input(INPUT_POST, 'dt_inicial');
        $dt_final = filter_input(INPUT_POST, 'dt_final');

        $dados = [];

        $status = filter_input(INPUT_POST, 'status_assistencia');

        //Título
        $dt_i = new DateTime($dt_inicial);
        $inicial = $dt_i->format('d/m/Y');
        $dt_f = new DateTime($dt_final);
        $final = $dt_f->format('d/m/Y');

        if ($status == 'nao_finalizadas') {
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS ENTRE ' . $inicial . ' e ' . $final . ' (não finalizadas)</b>';
        }
        if ($status == 'finalizadas') {
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS ENTRE ' . $inicial . ' e ' . $final . ' (finalizadas)</b>';
        }
        if ($status == 'todas') {
            $titulo = '<b>ASSISTÊNCIAS REGISTRADAS ENTRE ' . $inicial . ' e ' . $final . ' (todas)</b>';
        }

        $updates_res = $this->assistenciaUpModel->allUpdatesByPeriodo($dt_inicial, $dt_final, $status);
        if ($updates_res['erro'] != '') {
            Sessao::mensagem('assistencias', $updates_res['erro']);
            return $this->view('admin/minhas_assistencias', $dados);
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

        $dados = [
            'titulo'          => $titulo,
            'num_registros'   => $num_registros,
            'count_assistidos' => 'Assistidos: ' . $count_assistidos,
            'anos'             => Times::anos_12(),
            'updates'         => $updates,
            'meses'            => $meses,
            'home'             => $this->home,
            'titulo_botao'     => 'periodo',

            'assistencias'     => $assistencias,

            //contadores geral
            'count_geral'      => $this->assistenciaUpModel->countUpdates('geral'),
            'count_geral_nao_finalizadas'      => $this->assistenciaUpModel->countUpdates('não finalizadas'),
            'count_geral_finalizadas'      => $this->assistenciaUpModel->countUpdates('finalizadas'),

        ];

        $this->view('supervisao/assistencias', $dados);
    }

    //MINHAS ASSISTÊNCIAS
    //GENÉRICA RECENTES
    public function minhas_assistencias_recentes_generica($status)
    {

        $id_operador = $_SESSION['user']['id'];


        if ($status == 'nao_finalizadas') {
            $titulo = '<b>MINHAS ASSISTÊNCIAS NÃO FINALIZADAS (recentes)</b>';
            $titulo_botao = 'não finalizadas';
        }
        if ($status == 'todas') {
            $titulo = '<b>MINHAS ASSISTÊNCIAS RECENTES (todas)</b>';
            $titulo_botao = 'recentes';
        }
        if ($status == 'finalizadas') {
            $titulo = '<b>MINHAS ASSISTÊNCIAS FINALIZADAS (recentes)</b>';
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

    public function minhas_assistencias($filtro = null)
    {

        $dados = $this->minhas_assistencias_recentes_generica('todas');

        $this->view('supervisao/minhas_assistencias', $dados);
    }

    // Não Finalizadas
    public function minhas_ass_nao_finalizadas()
    {

        $dados = $this->minhas_assistencias_recentes_generica('nao_finalizadas');

        $this->view('supervisao/minhas_assistencias', $dados);
    }

    // Finalizadas
    public function minhas_ass_finalizadas()
    {

        $dados = $this->minhas_assistencias_recentes_generica('finalizadas');

        $this->view('supervisao/minhas_assistencias', $dados);
    }

    //Filtros de busca de Assistências
    public function minhas_assistencias_by_filtros($dia, $mes, $ano, $dt_inicial, $dt_final, $status)
    {

        $id_operador = $_SESSION['user']['id'];

        //por data
        if ($dia && $mes && $ano) {
            $updates_res = $this->assistenciaModel->updatesByOperadorByData($id_operador, $dia, $mes, $ano, $status);
            $titulo = '<b>MINHAS ASSISTÊNCIAS REGISTRADAS EM ' . $dia . '/' . $mes . '/' . $ano . ' (' . $status . ')</b>';
        }
        //mês e ano
        if (!$dia && $mes && $ano) {
            $updates_res = $this->assistenciaModel->updatesByOperadorMesAno($id_operador, $mes, $ano, $status);
            $titulo = '<b>MINHAS ASSISTÊNCIAS REGISTRADAS EM ' . Times::mes_string($mes) . '/' . $ano . ' (' . $status . ')</b>';
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

            $titulo = '<b>MINHAS ASSISTÊNCIAS REGISTRADAS ENTRE ' . $dt_i . ' e ' . $dt_f . ' (' . $status . ')</b>';
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

        $this->view('supervisao/minhas_assistencias', $dados);
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

        $this->view('supervisao/minhas_assistencias', $dados);
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

        $this->view('supervisao/minhas_assistencias', $dados);
    }

    //CIDADÃOS
    //Todos
    public function cidadaos()
    {

        $cidadaos = $this->cidadaoModel->cidadaosTodos();

        $dados = [
            'cidadaos' => json_encode($cidadaos['cidadaos']),
            'hoje'     => date('d/m/Y'),
            'num_registros' => count($cidadaos['cidadaos'])
        ];


        $this->view('cidadao/cidadaos', $dados);
    }
}
