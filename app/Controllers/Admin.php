<?php

class Admin extends Controller
{

    private $sessao_acesso;
    private $acesso;
    private $home;

    private $userModel;
    private $operadorModel;
    private $superModel;
    private $coordenacaoModel;
    private $assistenciaModel;
    private $assistenciaUpModel;
    private $cidadaoModel;
    private $representanteModel;
    private $supervisaoModel;
    private $adminModel;

    public function __construct()
    {

        $this->sessao_acesso = Sessao::sessaoUser();

        if ($this->sessao_acesso['acesso'] == 'Administração') {
            $this->home = URL . '/admin/index';
            $this->acesso = 'Administração';
        }

        $this->userModel = $this->model('UserModel');
        $this->operadorModel = $this->model('OperadorModel');
        $this->superModel = $this->model('SupervisaoModel');
        $this->coordenacaoModel = $this->model('CoordenacaoModel');
        $this->assistenciaModel = $this->model('AssistenciaModel');
        $this->assistenciaUpModel = $this->model('AssistenciaUpModel');
        $this->cidadaoModel = $this->model('CidadaoModel');
        $this->representanteModel = $this->model('RepresentanteModel');
        $this->supervisaoModel = $this->model('SupervisaoModel');
        $this->adminModel = $this->model('AdminModel');
    }

    public function index()
    {

        //ASSISTÊNCIAS
        $count_assistencias = '';
        $count_assistencias_nao_finalizadas = '';
        $count_assistencias_finalizadas = '';
        $count_assistencias_mes_atual = '';
        $count_assistencias_updates_mes_atual = '';

        $mes_atual = date('m');
        //UPDATES
        $count_updates = 0;
        $count_updates_nao_finalizadas = 0;
        $count_updates_finalizadas = 0;
        $count_updates_mes_atual = 0;

        $count_cidadaos_assistidos = 0;
        // $count_updates_updates_mes_atual = '';

        //XXXXXXXX

        // todas Updates
        $count_updates_res = $this->assistenciaModel->countUpdatesTodas();
        if ($count_updates_res['erro'] == '' && $count_updates_res['count'] != '') {
            $count_updates = $count_updates_res['count'];
        }

        // Updates não finalizadas
        $count_updates_nao_finalizadas_res = $this->assistenciaModel->countUpdatesNaoFinalizadas();
        if ($count_updates_nao_finalizadas_res['erro'] == '' && $count_updates_nao_finalizadas_res['count'] != '') {
            $count_updates_nao_finalizadas = $count_updates_nao_finalizadas_res['count'];
        }

        // Updates finalizadas
        $count_updates_finalizadas_res = $this->assistenciaModel->countUpdatesFinalizadas();
        if ($count_updates_finalizadas_res['erro'] == '' && $count_updates_finalizadas_res['count'] != '') {
            $count_updates_finalizadas = $count_updates_finalizadas_res['count'];
        }

        // Updates registradas no mês atual
        $count_updates_mes_atual_res = $this->assistenciaModel->countUpdatesRegistradasMesAtual($mes_atual);
        if ($count_updates_mes_atual_res['erro'] == '' && $count_updates_mes_atual_res['count'] != '') {
            $count_updates_mes_atual = $count_updates_mes_atual_res['count'];
        }

        // Updates atualizadas no mês atual
        // $count_updates_updates_mes_atual_res = $this->assistenciaModel->countUpdatesUpdatesMesAtual($mes_atual);
        // if ($count_updates_updates_mes_atual_res['erro'] == '' && $count_updates_updates_mes_atual_res['count'] != '') {
        //     $count_updates_updates_mes_atual = $count_updates_updates_mes_atual_res['count'];
        // }

        //XXXXXXXXX

        //CIDADÃOS
        $count_cidadaos = '';
        $count_com_assistencias_ativas = 0;
        $count_cidadaos_cadastrados_no_mes = '';
        $count_todos_assistidos_res = 0;

        //Total de Cidadãos registrados
        $count_cidadaos_res = $this->cidadaoModel->countCidadaosTodos();
        if ($count_cidadaos_res['erro'] == '' && $count_cidadaos_res['count'] != '') {
            $count_cidadaos = $count_cidadaos_res['count'];
        }

        //Total de Cidadãos com Assistncias ativas
        $count_ativas_res = $this->cidadaoModel->cidadaosAssistenciasAtivas();
        if ($count_ativas_res['erro'] == '' && $count_ativas_res['ids_cidadaos'] != '') {
            $count_com_assistencias_ativas = count($count_ativas_res['ids_cidadaos']);
        }

        //Assistidos
        $count_todos_assistidos_res = $this->cidadaoModel->todosCidadaosAssistidos();
        if ($count_todos_assistidos_res['erro'] == '' && $count_todos_assistidos_res['ids_cidadaos'] != '') {
            $count_todos_assistidos = count($count_todos_assistidos_res['ids_cidadaos']);
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
        $count_all_supervisores = '';
        $count_all_admin = '';

        //Total de Operadores
        $count_all_operadores_res = $this->operadorModel->countAllOperadores();
        if ($count_all_operadores_res['erro'] == '' && $count_all_operadores_res['count'] != '') {
            $count_all_operadores = $count_all_operadores_res['count'];
        }

        //Total de Representantes
        $count_all_representantes_res = $this->representanteModel->countAllRepresentantes();
        if ($count_all_representantes_res['erro'] == '' && $count_all_representantes_res['count'] != '') {
            $count_all_representantes = $count_all_representantes_res['count'];
        }

        //Total de Supervisores
        $count_all_supervisores_res = $this->supervisaoModel->countAllSupervisores();
        if ($count_all_supervisores_res['erro'] == '' && $count_all_supervisores_res['count'] != '') {
            $count_all_supervisores = $count_all_supervisores_res['count'];
        }

        //Total de Administradores
        $count_all_admin_res = $this->adminModel->countAllAdministradores();
        if ($count_all_admin_res['erro'] == '' && $count_all_admin_res['count'] != '') {
            $count_all_admin = $count_all_admin_res['count'];
        }

        //Total de Cidadãos aniversariantes do dia
        $num_cidadaos_nivers = 0;
        $nivers_cidadaos_res = $this->cidadaoModel->countAniversariantes();
        if ($nivers_cidadaos_res['erro'] != 0) {
            $num_cidadaos_nivers = $nivers_cidadaos_res['count'];
        }

        $dados = [
            'mes_ano_atual' => date('MY'),
            // Assistências
            'count_assistencias' => $count_assistencias,
            'count_assistencias_nao_finalizadas' => $count_assistencias_nao_finalizadas,
            'count_assistencias_finalizadas'     => $count_assistencias_finalizadas,
            'count_assistencias_mes_atual'       => $count_assistencias_mes_atual,
            'count_assistencias_updates_mes_atual' => $count_assistencias_updates_mes_atual,

            // Updates
            'count_updates' => $count_updates,
            'count_updates_nao_finalizadas' => $count_updates_nao_finalizadas,
            'count_updates_finalizadas'     => $count_updates_finalizadas,
            'count_updates_mes_atual'       => $count_updates_mes_atual,

            // Cidadãos
            'count_cidadaos'    => $count_cidadaos,
            'count_cidadaos_cadastrados_no_mes' => $count_cidadaos_cadastrados_no_mes,
            'count_com_assistencias_ativas'     => $count_com_assistencias_ativas,
            'count_todos_assistidos'            => $count_todos_assistidos,

            // Coordenação
            'count_coordenadorias'                  => $count_coordenadorias,
            'count_coordenadores'                   => $count_coordenadores,

            //Operadores
            'count_all_operadores'                  => $count_all_operadores,
            'count_all_representantes'              => $count_all_representantes,
            'count_all_supervisores'                => $count_all_supervisores,
            'count_all_admin'                       => $count_all_admin,

            'assistencias_todas_mes_atual' => 12,

            'num_cidadaos_nivers' => $num_cidadaos_nivers
        ];

        $this->view('admin/index', $dados);
    }

    public function all_supervisores()
    {

        $super = $this->superModel->allSupervisores();

        if (count($super['supervisores']) == 0) {
            $supervisores = false;
        } else {
            $supervisores = json_encode($super['supervisores']);
        }

        $dados = [
            'supers' => $supervisores,
            'hoje'       => date('d/m/Y'),
            'num_registros' => count($super['supervisores'])
        ];

        $dados['home'] = $this->home;

        return $this->view('supervisao/all_supervisores', $dados);
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

        $this->view('admin/create_coordenadoria', $dados);
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

        $this->view('admin/all_coordenadorias', $dados);
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

        $this->view('admin/coordenadoria', $dados);
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

        $this->view('admin/edit_coordenadoria', $dados);
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
            'home'          => $this->home,
            'link_coordenadorias' => URL . '/admin/all_coordenadorias'
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

    //Todos os Operadores, inclusive os Admins
    public function all_operadores()
    {

        $rep_res = $this->operadorModel->allOperators();
        if ($rep_res['erro'] == '' && $rep_res['operadores'] != '') {
            $operadores = $rep_res['operadores'];
        }

        $dados = [
            'operadores' => json_encode($operadores),
            'num_registros' => count($operadores),
            'hoje'          => date('d/m/Y'),
            'home'          => $this->home
        ];

        $this->view('operadores/all_operadores', $dados);
    }

    // GERENCIAMENTO DE ASSISTÊNCIAS
    //Geral - recentes

    public function assistencias()
    {

        $id_operador = $_SESSION['user']['id'];
        $dados = [];

        $updates_res = $this->assistenciaUpModel->allUpdatesRecentes();
        
        $updates = $updates_res['updates'];

        $assistencias = [];
        $array_assistidos = [];
        for ($i = 0; $i < count($updates); $i++) {

            $descricao = $updates[$i]['status_compl_updated'];

            //Primeiro registro
            $primeiro_registro = '';
            $st_primeiro_reg = '';
            $desc_comp_primeiro_reg = '';

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
                'data_primeiro_registro' => $data_primeiro_registro,

            ];
        }

        //Título
        $titulo = '<b>REGISTROS RECENTES EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS</b>';
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

            'titulo_relatorio'        => '<b>RELATÓRIO</b> ' . $titulo

        ];


        $this->view('admin/assistencias', $dados);
    }

    //Assistências não finalizadas
    public function assistencias_nao_finalizadas()
    {

        $dados = [];

        $updates_res = $this->assistenciaUpModel->allUpdatesNaoFinalizadas();
        
        $updates = $updates_res['updates'];

        $assistencias = [];
        $array_assistidos = [];
        for ($i = 0; $i < count($updates); $i++) {

            $descricao = $updates[$i]['status_compl_updated'];

            //Primeiro registro
            $primeiro_registro = '';
            $st_primeiro_reg = '';
            $desc_comp_primeiro_reg = '';

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
        }

        //Título
        $titulo = '<b>REGISTROS RECENTES EM ASSISTÊNCIAS NÃO FINALIZADAS</b>';
        
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

            'titulo_relatorio'        => '<b>RELATÓRIO</b> ' . $titulo

        ];


        $this->view('admin/assistencias', $dados);
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

            $descricao = $updates[$i]['status_compl_updated'];

            //Primeiro registro
            $primeiro_registro = '';
            $st_primeiro_reg = '';
            $desc_comp_primeiro_reg = '';

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

        //Título
        $titulo = '<b>REGISTROS RECENTES EM ASSISTÊNCIAS FINALIZADAS</b>';
        
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

            'titulo_relatorio'        => '<b>RELATÓRIO</b> ' . $titulo

        ];


        $this->view('admin/assistencias', $dados);
    }

    /**
     * Número total de Assistências e de assistidos
     */
    public function totaisAssistenciasCidadaos()
    {

        //Número total de Assietências
        $dados['count_assistencias'] = '';
        $count_ass = $this->assistenciaModel->countAssistenciasTodas();
        if ($count_ass['erro'] == '') {
            $dados['count_assistencias'] = $count_ass['count'];
        }

        //Número total de Assistidos
        $array_ids = [];
        $ids_cidadaos = $this->assistenciaModel->allIdsAssistidos();
        if ($ids_cidadaos['erro'] == '') {

            foreach ($ids_cidadaos['ids'] as $ids) {
                if (!in_array($ids, $ids_cidadaos)) {
                    $array_ids[] = $ids;
                }
            }
            $dados['count_cidadaos_assistidos'] = count($array_ids);
        }

        //Não finalizadas
        $nao_finalizadas = $this->assistenciaModel->countAssistenciasNaoFinalizadas();
        $dados['nao_finalizadas'] = $nao_finalizadas['count'];

        //Finalizadas
        $finalizadas = $this->assistenciaModel->countAssistenciasFinalizadas();
        $dados['finalizadas'] = $finalizadas['count'];

        return $dados;
    }

    // Geral - No Finalizadas
    public function ass_nao_finalizadas()
    {

        $dados = $this->assistenciaModel->assNaoFinalizadas();

        $dados['anos'] = Times::anos_12();
        $meses = Times::meses();
        $dados['meses'] = $meses;

        //Totais de Assistências e Assistidos
        $totais = $this->totaisAssistenciasCidadaos();
        $dados['count_assistencias'] = $totais['count_assistencias'];
        $dados['count_cidadaos_assistidos'] = $totais['count_cidadaos_assistidos'];
        $dados['count_assistencias_nao_finalizadas'] = $totais['nao_finalizadas'];
        $dados['count_assistencias_finalizadas'] = $totais['finalizadas'];

        $dados['home'] = $this->home;

        $this->view('admin/assistencias', $dados);
    }

    // Geral - Finalizadas
    public function ass_finalizadas()
    {

        $dados = $this->assistenciaModel->assFinalizadas();

        $dados['anos'] = Times::anos_12();
        $meses = Times::meses();
        $dados['meses'] = $meses;

        //Totais de Assistências e Assistidos
        $totais = $this->totaisAssistenciasCidadaos();
        $dados['count_assistencias'] = $totais['count_assistencias'];
        $dados['count_cidadaos_assistidos'] = $totais['count_cidadaos_assistidos'];
        $dados['count_assistencias_nao_finalizadas'] = $totais['nao_finalizadas'];
        $dados['count_assistencias_finalizadas'] = $totais['finalizadas'];

        $dados['home'] = $this->home;

        $this->view('admin/assistencias', $dados);
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

        //XXXXXXXXXXXXXXX  ATENÇÃO  XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        $status = 'todas';
        // $status = filter_input(INPUT_POST, 'status_assistencia');

        //Título - ATENÇÃO
        $titulo = '<b>REGISTROS EM ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS EM ' . $dia . '/' . $mes . '/' . $ano . '</b>';
        // if ($status == 'nao_finalizadas') {
        //     $titulo = '<b>REGISTROS DE ASSISTÊNCIAS INICIADAS OU ATUALIZADAS EM ' . $dia . '/' . $mes . '/' . $ano . ' (não finalizadas)</b>';
        // }
        // if ($status == 'finalizadas') {
        //     $titulo = '<b>REGISTROS DE ASSISTÊNCIAS FINALIZADAS EM ' . $dia . '/' . $mes . '/' . $ano;
        // }
        // if ($status == 'todas') {
        //     $titulo = '<b>REGISTROS DE ASSISTÊNCIAS INICIADAS OU ATUALIZADAS EM ' . $dia . '/' . $mes . '/' . $ano . ' (todas)</b>';
        // }

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

            'titulo_relatorio'        => '<b>RELATÓRIO DE</b> ' . $titulo,


        ];

        $this->view('admin/assistencias', $dados);
    }

    // Geral por mês e ano
    public function ass_mes_ano()
    {

        $mes = filter_input(INPUT_POST, 'mes');
        $ano = filter_input(INPUT_POST, 'ano');

        $dados = [];

        //XXXXXXXXXXXXXXX  ATENÇÃO  XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        $status = 'todas';
        // $status = filter_input(INPUT_POST, 'status_assistencia');

        //Título
        $titulo = '<b>ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS NO MÊS ' . $mes . '/' . $ano . '</b>';
        // if ($status == 'nao_finalizadas') {
        //     $titulo = '<b>ASSISTÊNCIAS REGISTRADAS NO MÊS ' . $mes . '/' . $ano . ' (não finalizadas)</b>';
        // }
        // if ($status == 'finalizadas') {
        //     $titulo = '<b>ASSISTÊNCIAS REGISTRADAS NO MÊS ' . $mes . '/' . $ano . ' (finalizadas)</b>';
        // }
        // if ($status == 'todas') {
        //     $titulo = '<b>ASSISTÊNCIAS REGISTRADAS NO MÊS ' . $mes . '/' . $ano . ' (todas)</b>';
        // }

        $updates_res = $this->assistenciaUpModel->allUpdatesByMesAno($mes, $ano, $status);
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

            'titulo_relatorio'        => '<b>RELATÓRIO DE</b> ' . $titulo,
        ];

        $this->view('admin/assistencias', $dados);
    }

    // Geral por mês e ano
    public function ass_periodo()
    {

        $dt_inicial = filter_input(INPUT_POST, 'dt_inicial');
        $dt_final = filter_input(INPUT_POST, 'dt_final');

        $dados = [];

        //XXXXXXXXXXXXXXX  ATENÇÃO  XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        $status = 'todas';
        // $status = filter_input(INPUT_POST, 'status_assistencia');

        //Título ATENÇÃO
        $dt_i = new DateTime($dt_inicial);
        $inicial = $dt_i->format('d/m/Y');
        $dt_f = new DateTime($dt_final);
        $final = $dt_f->format('d/m/Y');

        $titulo = $titulo = '<b>ASSISTÊNCIAS INICIADAS, ATUALIZADAS OU FINALIZADAS ENTRE ' . $inicial . ' e ' . $final . '</b>';

        // if ($status == 'nao_finalizadas') {
        //     $titulo = '<b>ASSISTÊNCIAS REGISTRADAS ENTRE ' . $inicial . ' e ' . $final . ' (não finalizadas)</b>';
        // }
        // if ($status == 'finalizadas') {
        //     $titulo = '<b>ASSISTÊNCIAS REGISTRADAS ENTRE ' . $inicial . ' e ' . $final . ' (finalizadas)</b>';
        // }
        // if ($status == 'todas') {
        //     $titulo = '<b>ASSISTÊNCIAS REGISTRADAS ENTRE ' . $inicial . ' e ' . $final . ' (todas)</b>';
        // }

        $updates_res = $this->assistenciaUpModel->allUpdatesByPeriodo($dt_inicial, $dt_final, $status);
        // if ($updates_res['erro'] != '') {
        //     Sessao::mensagem('assistencias', $updates_res['erro']);
        //     return $this->view('admin/minhas_assistencias', $dados);
        // }
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
            'titulo_relatorio'        => '<b>RELATÓRIO DE</b> ' . $titulo,

        ];

        $this->view('admin/assistencias', $dados);
    }

    //MINHAS ASSISTÊNCIAS/UPDATES
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

        $this->view('admin/minhas_assistencias', $dados);
    }

    // Não Finalizadas
    public function minhas_ass_nao_finalizadas()
    {

        $dados = $this->minhas_assistencias_recentes_generica('nao_finalizadas');

        $this->view('admin/minhas_assistencias', $dados);
    }

    // Finalizadas
    public function minhas_ass_finalizadas()
    {

        $dados = $this->minhas_assistencias_recentes_generica('finalizadas');

        $this->view('admin/minhas_assistencias', $dados);
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

            $titulo = '<b>MEUS REGISTROS EM ASSISTÊNCIAS ENTRE ' . $dt_i . ' e ' . $dt_f . '</b>';
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

            'titulo_relatorio'        => '<b>RELATÓRIO</b> ' . $titulo

        ];

        return $dados;
    }

    // Minhas Updates Por data
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

        $this->view('admin/minhas_assistencias', $dados);
    }

    // Minhas updates por mês e ano
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

        $this->view('admin/minhas_assistencias', $dados);
    }

    // Minhas Updates por período
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
        $this->view('admin/minhas_assistencias', $dados);
    }

    //Cidadãos aniversariantes do dia
    public function aniversariantes_dia()
    {
        $nivers_res = $this->cidadaoModel->aniversariantesDia();

        $aniversariantes = 0;
        $num_registros = 0;

        if ($nivers_res['erro'] == '') {

            $aniversariantes = json_encode($nivers_res['aniversariantes']);
            $num_registros = count($nivers_res['aniversariantes']);
        } else {
            Sessao::mensagem('aniversariantes', 'ERRO ao buscar aniversariantes', 'alert alert-danger');
        }

        $dados = [
            'aniversariantes' => $aniversariantes,
            'num_registros' => $num_registros,
            'home' => $this->home
        ];

        $this->view('admin/aniversariantes_dia', $dados);
    }

    //Página para envio de mensagem de parabéns ao cidadão aniversariante
    public function msg_aniversario($id)
    {

        $cid_res = $this->cidadaoModel->cidadao($id);
        if ($cid_res['erro'] == '') {
            $cidadao = $cid_res['cidadao'];

            // Whatsapp
            $whatsapp = '';
            if (!empty($cidadao['celular'])) {
                $num = $cidadao['celular'];
                $sem_parenteses1 = str_replace('(', '', $num);
                $sem_parenteses2 = str_replace(')', '', $sem_parenteses1);
                $sem_espaco = str_replace(' ', '', $sem_parenteses2);
                $whatsapp = str_replace('-', '', $sem_espaco);
            }

            $dados = [
                'id' => $cidadao['id'],
                'nome' => $cidadao['nome'],
                'data_nasc' => $cidadao['data_nascimento'],
                'idade' => Times::idade_anos($cidadao['data_nascimento']),
                'celular' => $cidadao['celular'],
                'email' => $cidadao['email'],
                'whatsapp' => $whatsapp,
                'facebook' => $cidadao['facebook'],
                'instagram' => $cidadao['instagram'],
                'twitter' => $cidadao['twitter'],
                'telegram' => $cidadao['telegram'],
                'tiktok' => $cidadao['tiktok'],
                'home' => $this->home,
                'url_retorno' => URL . '/admin/aniversariantes_dia',
                'url_delete_aniversariante' => URL . '/admin/delete_aniversariante'

            ];
        }

        $this->view('cidadao/msg_aniversario', $dados);
    }

    //Retira Cidadão da lista de aniversariantes do dia
    public function delete_aniversariante()
    {

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        $id_cidadao = trim($form['id_cidadao']);
        $nome_cidadao = trim($form['nome_cidadao']);

        $delete_res = $this->cidadaoModel->deleteAniversariante($id_cidadao);
        if ($delete_res['erro'] == '' && $delete_res['num_rows'] == 1) {

            Sessao::mensagem('aniversariantes', '<b>' . $nome_cidadao . ' </b>foi retirado da lista de aniversariantes do dia.');
        } else {
            Sessao::mensagem('aniversariantes', 'ERRO ao retirar Cidadão da lista de aniversariantes.');
        }

        $this->aniversariantes_dia();
    }

    //ASSISTÊNCIAS
    /**
     * Atualiza o Status da Assistência
     */
    public function update_status_assistencia($id_assistencia, $status)
    {

        if ($status == 'Finalizada') {
            $dados =
                [
                    'finalizada' => $status,
                    'home'               => $this->home
                ];
        }

        $assistencia_res = $this->assistenciaModel->getAssistenciaById($id_assistencia);
        if ($assistencia_res['erro'] == '' && $assistencia_res['assistencia'] != '') {
            $assistencia = $assistencia_res['assistencia'];

            // data de updated recente
            $data_up_recente = $this->assistenciaModel->dataRecenteUpdate($id_assistencia);

            //status_update atual
            $update_assist = $this->assistenciaModel->updateAssistByData($data_up_recente, $id_assistencia);
            $status_atual = $update_assist['assistencia_update']['status_updated'];
        }

        $cidadao_res = $this->cidadaoModel->getNomeIdCidadao($assistencia['id_cidadao']);
        if ($cidadao_res['erro'] == '' && $cidadao_res['cidadao'] != '') {
            $cidadao = $cidadao_res['cidadao'];
            $nome_cidadao = $cidadao['nome'];
        }

        $dados = [
            'id_assistencia' => $assistencia['id'],
            'id_cidadao'   => $assistencia['id_cidadao'],
            'nome_cidadao' => $nome_cidadao,
            'descricao'    => $assistencia['descricao'],
            'status_atual' => $status_atual,
            'finalizada'   => $assistencia['status_assist'],
            'home'               => $this->home
        ];

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
        if (isset($form)) {
            $dados = [
                'id_assistencia' => trim($form['id_assistencia']),
                'id_cidadao' => trim($form['id_cidadao']),
                'nome_cidadao' => $nome_cidadao,
                'descricao'    => $assistencia['descricao'],
                'id_updated_by' => $_SESSION['user']['id'],
                'name_updated_by' => $_SESSION['user']['nome'],
                'status_atual'    => trim($form['status_atual']),
                'status_complemento' => $form['status_complemento'],
                'status_updated'  => $form['novo_status'],
                'status_compl_updated' => $form['status_complemento'],
                'id_coordenadoria' => $assistencia['id_coordenadoria'],
                'nome_coordenadoria' => $assistencia['nome_coordenadoria'],
                'home'               => $this->home
            ];

            if ($status == 'Finalizada') {
                Sessao::mensagem('assistencia', 'ASSISTÊNCIA FINALIZADA; abra uma nova Assistência', 'alert alert-warning');
                $dados = [
                    'finalizada' => 'Finalizada',
                    'id_cidadao' => $dados['id_cidadao']
                ];
                return $this->view('admin/update_status_assistencia', $dados);
            }

            //Verifica se já existe o status_update - Proteje do refresh da página
            $update_existe = $this->assistenciaModel->updateStatusExiste($dados['status_updated'], $dados['status_compl_updated']);
            if ($update_existe['erro'] == '' && $update_existe['num_rows'] != '' && $update_existe['num_rows'] != 0) {
                return;
            }

            // Começando
            $erro = '';

            if (empty($dados['status_compl_updated']) && $dados['status_updated'] == $dados['status_atual']) {
                Sessao::mensagem('assistencia', 'Complemente o Novo Status para justificar a atualização.', 'alert alert-warning');
                $erro = 'erro';
            }

            if ($dados['status_updated'] != $dados['status_atual'] && empty($dados['status_compl_updated']) && $dados['status_updated'] != 'Finalizada' && $dados['status_updated'] != 'Selecione') {
                Sessao::mensagem('assistencia', 'Complemente o Novo Status para justificar a atualização.', 'alert alert-danger');
                $erro = 'erro';
            }

            if ($dados['status_updated'] == 'Selecione') {
                Sessao::mensagem('assistencia', 'Selecione o novo Status!', 'alert alert-danger');
                $erro = 'erro';
            }

            if ($erro != 'erro') {

                //Aterar o Status
                $update = $this->assistenciaModel->updateStatus($dados);
                if ($update['erro'] == '' && $update['id_updated_status'] != '') {
                    if ($dados['status_updated'] == 'Finalizada') {
                        Sessao::mensagem('Assistência finalizada com sucesso!');
                    } else {
                        Sessao::mensagem('assistencia', 'Alteração de Status de Assistência efetuada com sucesso!');
                    }
                }
            }
        }

        $this->view('admin/update_status_assistencia', $dados);
    }

    public function finalizar_assistencia($id_assistencia)
    {

        // Assistência
        $res_assistencia = $this->assistenciaModel->getAssistenciaById($id_assistencia);
        if ($res_assistencia['erro'] == '' && $res_assistencia['assistencia'] != '') {
            $assistencia = $res_assistencia['assistencia'];

            //Se existe
            if ($assistencia['status_assist'] == 'Finalizada') {
                return $this->cidadao($assistencia['id_cidadao']);
            }

            // Cidadão
            $cidadao_nome = $this->cidadaoModel->getNomeIdCidadao($assistencia['id_cidadao']);
        }

        $dados = [
            'id_cidadao'          => $cidadao_nome['cidadao']['id'],
            'id_assistencia' => $assistencia['id'],
            'descricao'      => $assistencia['descricao'],
            'status_assist'  => 'Finalizada',
            'status_complemento' => '',
            'nome_cidadao' => $cidadao_nome['cidadao']['nome'],
            'id_updated_by'      => $_SESSION['user']['id'],
            'name_updated_by'    => $_SESSION['user']['nome'],
            'status_updated'     => 'Finalizada',
            'status_compl_updated' => '',
            'id_coordenadoria' => $assistencia['id_coordenadoria'],
            'nome_coordenadoria' => $assistencia['nome_coordenadoria'],
            'home'      => $this->home,
        ];

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {



            // Na Assistencia_update
            $dados['status_compl_updated'] = trim($form['status_complemento']);

            $erro = '';

            if (empty($dados['status_compl_updated'])) {
                Sessao::mensagem('assistencia', 'Informe alguma ação realizada!', 'alert alert-danger');
                $erro = 'erro';
            }

            if ($erro == '') {

                //Na Assistência
                $dados['status_complemento'] = 'Finalizada';

                //Finaliza em assistencia
                $assist_res = $this->assistenciaModel->finalizarAssistencia($dados);
                if ($assist_res['erro'] == '' && $assist_res['affected_rows'] == 1) {

                    //create_update
                    $up = $this->assistenciaModel->updateStatus($dados);
                    if ($up['erro'] == '' && $up['id_updated_status'] != '') {
                        Sessao::mensagem('assistencia', 'Assistência finalizada com sucesso!');
                        return $this->cidadao($dados['id_cidadao']);
                    }
                } else {
                    Sessao::mensagem('assistencia', 'ERRO ao finalizar Assistência, tente mais tarde!', 'alert alert-danger');
                }
            }
        }

        $this->view('admin/finalizar_assistencia', $dados);
    }

    public function cidadao($id = null)
    {

        //Cidadao
        $cidadao = $this->cidadaoModel->cidadao($id);

        $msg_assistencias = '';

        //Todas as Assistências do Cidadão
        $assistencias = [];
        $assistencias_res = $this->assistenciaModel->allAssistenciasByCidadao($id);
        if (count($assistencias_res['assistencias']) == 0) {
            $msg_assistencias = 'Cidadão sem registro de Assistências!';
        }

        //Assisteências por Coordenadoria
        if ($_SESSION['user']['id_coordenadoria'] != 0) {
            $assistencias_res = $this->assistenciaModel->allAssistenciasByCidadaoCoordenadoria($id, $_SESSION['user']['id_coordenadoria']);
            if (count($assistencias_res['assistencias']) == 0) {
                $msg_assistencias = 'Cidadão sem registro de Assistências!';
            }
        }

        //Assistências por Representante
        if ($_SESSION['user']['acesso'] == 'Representante') {
            $assistencias_res = $this->assistenciaModel->allAssistenciasByCidadaoRepresentante($id, $_SESSION['user']['id']);
            if (count($assistencias_res['assistencias']) == 0) {
                $msg_assistencias = 'Cidadão sem registro de Assistências!';
            }
        }

        if ($assistencias_res['erro'] == '' && $assistencias_res['assistencias'] != null) {

            //Assistências - array
            foreach ($assistencias_res['assistencias'] as $as) {

                // data da assistência
                $dt = new DateTime($as['date_at']);
                $data = $dt->format('d/m/Y');

                // status atual
                $status_atual = $as['status_assist'];

                //Assistencias_update - array
                $updates = [];
                $assist_up_res = $this->assistenciaModel->getAssistenciasUpdate($as['id']);

                if ($assist_up_res['erro'] == '' && $assist_up_res['assist_up'] != '') {

                    // Status atual
                    $status_atual = 'Sem atualizações'; //'Inalterada';
                    if (count($assist_up_res['assist_up']) > 1) {
                        $status_atual = $assist_up_res['assist_up'][0]['status_updated'];
                    }

                    //Descrição do statusl atual
                    $desc_status_atual = ' --- ';

                    // Data da update atual
                    $dt_js = $assist_up_res['assist_up'][0]['updated_at'];
                    $dt_up = new DateTime($dt_js);
                    $data_format = $dt_up->format('d/m/Y');
                    $data_up = ' --- ';

                    if ($data_format != $data && $assist_up_res['assist_up'][0]['status_compl_updated']) {
                        $data_up = $data_format;
                        $desc_status_atual = $assist_up_res['assist_up'][0]['status_compl_updated'];
                        $status_atual = $assist_up_res['assist_up'][0]['status_updated'];
                    }
                    // $data = $dt_up->format('d/m/Y');

                    //Updates
                    foreach ($assist_up_res['assist_up'] as $up) {

                        $d_up = new DateTime($up['updated_at']);
                        $data_up = $d_up->format('d/m/Y H:i:s');

                        $updates[] = [
                            'id' => $up['id'],
                            'id_assistencia' => $up['id_assistencia'],
                            'status_compl_updated' => $up['status_compl_updated'],
                            'name_updated_by' => $up['name_updated_by'],
                            'status_updated'  => $up['status_updated'],
                            'data'            => $data_up
                        ];
                    }
                }

                // array de assistências
                $assistencias[] = [
                    'id' => $as['id'],
                    'data' => $data,
                    'descricao' => $as['descricao'],
                    'complemento' => ' - ' . $as['descricao_complemento'],
                    'status_atual' => $status_atual,
                    'ultima_atualizacao' => $data_up,
                    'desc_status_atual' => $desc_status_atual,
                    'updates' => $updates,
                    'nome_coordenadoria' => $as['nome_coordenadoria']
                ];
            }

            // $dados['assistencias'] = json_encode($assistencias);


        }

        if ($cidadao['erro'] != '') {
            Sessao::mensagem('ERRO ao buscar dados de Cidadão!');
        } else {
            $dados = [
                'cidadao' => $cidadao['cidadao'],
                'assistencias' => $assistencias,
                'hoje' => date('d/m/Y'),
                'num_registros' => '',
                'msg_assistencias' => $msg_assistencias,
                'home'      => $this->home,
                'whatsapp'  => '',
                'idade' => Times::idade_anos($cidadao['cidadao']['data_nascimento'])
            ];
        }

        // Whatsapp
        if (!empty($cidadao['cidadao']['celular'])) {
            $num = $cidadao['cidadao']['celular'];
            $sem_parenteses1 = str_replace('(', '', $num);
            $sem_parenteses2 = str_replace(')', '', $sem_parenteses1);
            $sem_espaco = str_replace(' ', '', $sem_parenteses2);
            $whatsapp = str_replace('-', '', $sem_espaco);
            $dados['whatsapp'] = $whatsapp;
        }

        $this->view('cidadao/cidadao', $dados);
    }
}
