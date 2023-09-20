<?php

class Assistencias extends Controller
{

    private $sessao_acesso;
    private $home;
    private $home_assistencia;

    private $userModel;
    private $cidadaoModel;
    private $coordenacaoModel;
    private $assistenciaModel;

    private $cidadaoController;
    private $representanteController;

    public function __construct()
    {

        if (isset($_SESSION['user'])) {

            $this->sessao_acesso = Sessao::sessaoUser();

            if ($this->sessao_acesso['acesso'] == 'Administração') {
                $this->home = URL . '/admin';
            }
            if ($this->sessao_acesso['acesso'] == 'Supervisão') {
                $this->home = URL . '/supervisao';
            }
            if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
                $this->home = URL . '/coordenacao';
            }
            if ($this->sessao_acesso['acesso'] == 'Representante') {
                $this->home = URL . '/representante';
            }
        }

        $this->userModel = $this->model('UserModel');
        $this->cidadaoModel = $this->model('CidadaoModel');
        $this->coordenacaoModel = $this->model('CoordenacaoModel');
        $this->assistenciaModel = $this->model('AssistenciaModel');

        $this->cidadaoController = $this->controller('Cidadao');
        $this->representanteController = $this->controller('Representante');
    }

    public function index()
    {

        if ($this->sessao_acesso['acesso'] != 'Administração' && $this->sessao_acesso['acesso'] !== 'Supervisão') {
            if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
                return $this->assist_recentes_coordenadoria();
            }
            if ($this->sessao_acesso['acesso'] == 'Representante') {
                return $this->home;
            }
        }

        // Assistências recentes
        $res = $this->assistenciaModel->allAssistencias();

        if ($res['erro'] == '' && $res['assistencias'] != '') {

            $assistencias_model = $res['assistencias'];
            $assistencias = [];

            foreach ($assistencias_model as $assist) {

                $status_assistencia = $assist['status_assist'];
                $data = new DateTime($assist['date_at']);
                $data = $data->format('d/m/Y');

                $ultima_alteracao = $data;

                // Cidadão
                $get_cidadao = $this->cidadaoModel->getNomeIdCidadao($assist['id_cidadao']);

                // Update de Assistência
                $get_assistencias_update = $this->assistenciaModel->getAssistenciasUpdate($assist['id']);
                if ($get_assistencias_update['erro'] == '' && $get_assistencias_update['assist_up'] != '') {

                    if ($get_assistencias_update['assist_up']) {

                        $assist_ups_array = $get_assistencias_update['assist_up'];

                        $status_assistencia = $assist_ups_array[0]['status_updated'];

                        $data_up = new DateTime($assist_ups_array[0]['updated_at']);
                        $ultima_alteracao = date_format($data_up, 'd/m/Y');
                    }
                }

                $assistencias[] = [
                    'data'      => $data,
                    'descricao' => $assist['descricao'],
                    'nome_cidadao' => $get_cidadao['cidadao']['nome'],
                    'id_assistencia' => $assist['id'],
                    'nome_coordenadoria' => $assist['nome_coordenadoria'],
                    'status_assistencia' => $status_assistencia,
                    'ultima_alteracao'   => $ultima_alteracao
                ];
            }

            $dados = [
                'assistencias' => $assistencias,
                'home'      => $this->home,
                'hoje'         => date('d/m/Y'),
                'num_registros' => count($assistencias)
            ];
        }

        $this->view('assistencias/index', $dados);
    }

    /**
     * Atualiza o Status da Assistência
     */
    public function update_status($id_assistencia, $status)
    {
        //Link para retorno para gerenciamento de assistências
        if ($this->sessao_acesso['acesso'] == 'Administração') {
            $link_gerenciamento = URL . '/admin/assistencias';
        }
        if ($this->sessao_acesso['acesso'] == 'Supervisão') {
            $link_gerenciamento = URL . '/supervisao/assistencias';
        }
        if ($this->sessao_acesso['acesso'] == 'Coordenadoria') {
            $link_gerenciamento = URL . '/coordenacao/assistencias';
        }
        if ($this->sessao_acesso['acesso'] == 'Representante') {
            $link_gerenciamento = URL . '/representante/minhas_assistencias';
        }

        if ($status == 'Finalizada') {
            $dados =
                [
                    'finalizada' => $status,
                    'home'       => $this->home
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

            $sus_assistencia = $assistencia['sus'];
            $desc_juridica_atual = $assistencia['desc_juridica'];
            $num_proc_juridica_atual = $assistencia['num_proc_juridica'];
        }

        $cidadao_res = $this->cidadaoModel->getNomeIdCidadao($assistencia['id_cidadao']);
        if ($cidadao_res['erro'] == '' && $cidadao_res['cidadao'] != '') {
            $cidadao = $cidadao_res['cidadao'];
            $nome_cidadao = $cidadao['nome'];
            $sus_cidadao = $cidadao['sus'];
        }

        $sus = '';
        if ($sus_assistencia != '') {
            $sus = $sus_assistencia;
        }
        if ($sus_assistencia == '' && $sus_cidadao != '') {
            $sus = $sus_cidadao;
        }

        $dados = [
            'id_assistencia' => $assistencia['id'],
            'id_cidadao'        => $assistencia['id_cidadao'],
            'nome_cidadao'      => $nome_cidadao,
            'descricao'         => $assistencia['descricao'],
            'status_atual'      => $status_atual,
            'status_complemento' => '',
            'finalizada'        => $assistencia['status_assist'],
            'id_coordenadoria'  => $assistencia['id_coordenadoria'],
            'desc_juridica'     => $assistencia['desc_juridica'],
            'num_proc_juridica' => $assistencia['num_proc_juridica'],
            'sus'               => $sus,
            'home'              => $this->home,
            'link_gerenciamento' => $link_gerenciamento,

            'sus_erro'          => '',
            'num_proc_juridica_erro' => ''
        ];

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
        if (isset($form)) {

            $desc_juridica = $desc_juridica_atual;
            $num_proc_juridica = $num_proc_juridica_atual;

            if (isset($form['desc_juridica']) && isset($form['num_proc_juridica'])) {
                $desc_juridica = trim($form['desc_juridica']);
                $num_proc_juridica = trim($form['num_proc_juridica']);
            }

            if (isset($form['sus'])) {
                $sus = trim($form['sus']);
            }

            $dados = [
                'id_assistencia' => trim($form['id_assistencia']),
                'id_cidadao' => trim($form['id_cidadao']),
                'nome_cidadao' => $nome_cidadao,
                'descricao'    => $assistencia['descricao'],
                'id_updated_by' => $_SESSION['user']['id'],
                'name_updated_by' => $_SESSION['user']['nome'],
                'status_atual'    => trim($form['novo_status']),
                'status_complemento' => $form['status_complemento'],
                'status_updated'  => $form['novo_status'],
                'status_compl_updated' => $form['status_complemento'],
                'id_coordenadoria' => $assistencia['id_coordenadoria'],
                'nome_coordenadoria' => $assistencia['nome_coordenadoria'],
                'sus'                => $sus,
                'desc_juridica' => $desc_juridica,
                'num_proc_juridica' => $num_proc_juridica,
                'home'               => $this->home,
                'link_gerenciamento' => $link_gerenciamento,

                'sus_erro'                => '',
                'num_proc_juridica_erro'  => ''
            ];

            if ($status == 'Finalizada') {
                Sessao::mensagem('assistencia', 'ASSISTÊNCIA FINALIZADA; abra uma nova Assistência', 'alert alert-warning');
                $dados = [
                    'finalizada' => 'Finalizada',
                    'id_cidadao' => $dados['id_cidadao']
                ];
                return $this->view('assistencias/update_status', $dados);
            }

            //Verifica se já existe o status_update - Proteje do refresh da página
            $update_existe = $this->assistenciaModel->updateStatusExiste($dados['status_updated'], $dados['status_compl_updated']);
            if ($update_existe['erro'] == '' && $update_existe['num_rows'] != '' && $update_existe['num_rows'] != 0) {
                Sessao::mensagem('assistencia', 'Não há novos dados para serem alterados!', 'alert alert-warning');
                return $this->view('assistencias/update_status', $dados);
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

            if ($dados['status_updated'] == 'Selecione' || $dados['status_updated'] == 'Iniciada') {
                Sessao::mensagem('assistencia', 'Selecione o novo Status!', 'alert alert-danger');
                $erro = 'erro';
            }

            // SUS
            if (!empty($dados['sus']) && strlen($dados['sus']) < 18) {
                $dados['sus_erro'] = 'Número incompleto!';
                $erro = 'erro';
            }

            //Jurídico
            if ($num_proc_juridica_atual != $dados['num_proc_juridica']) {
                if (!empty($dados['num_proc_juridica']) && strlen($dados['num_proc_juridica']) < 25) {
                    $dados['num_proc_juridica_erro'] = 'Número incompleto!';
                    $erro = 'erro';
                }
            }

            if ($erro == '') {

                //Altera processo jurídico no caso de coordenadoria jurídica
                if (!empty($dados['desc_juridica']) || !empty($dados['num_proc_juridica'])) {
                    $this->assistenciaModel->addDadosJuridicos($dados['id_assistencia'], $dados['desc_juridica'], $dados['num_proc_juridica']);
                }

                //Insere cartão do SUS
                if (!empty($dados['sus'])) {
                    if ($sus_cidadao == '' || $sus_cidadao != $dados['sus']) {
                        $this->cidadaoModel->insertSus($dados['id_cidadao'], $sus);
                    }
                    if ($sus_assistencia == '' || $sus_assistencia != $dados['sus']) {
                        $this->assistenciaModel->insertSus($id_assistencia, $sus);
                    }
                }

                //Aterar o Status
                $update = $this->assistenciaModel->updateStatus($dados);
                if ($update['erro'] == '' && $update['id_updated_status'] != '') {
                    $dados['status_complemento'] = '';
                    if ($dados['status_updated'] == 'Finalizada') {
                        Sessao::mensagem('assistencia', 'Assistência finalizada com sucesso!');
                    } else {
                        Sessao::mensagem('assistencia', 'Atualização de Assistência efetuada com sucesso!');
                    }
                } else {
                    Sessao::mensagem('assistencia', 'ERRO ao atualizar/finalizar Assistência, tente mais tarde.');
                }
            } else {
                if ($dados['status_atual'] == 'Em andamento') {
                    $dados['status_atual'] = 'Em_andamento';
                }

                return $this->view('assistencias/update_status', $dados);
            }
        }

        $this->view('assistencias/update_status', $dados);
    }

    public function create($id)
    {

        $all_coordenadorias = $this->coordenacaoModel->allCoordenadorias();
        if ($all_coordenadorias['erro'] == '' && $all_coordenadorias['coordenadorias'] != '') {
            $coordenadorias = $all_coordenadorias['coordenadorias'];

            $user_id_coordenadoria = $_SESSION['user']['id_coordenadoria'];
        } else {
            $coordenadorias = 'Nenhuma Coordenadoria encontrada!';
        }

        $form = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);

        if (isset($form)) {

            $date_at = trim($form['date_at']);
            $dt = new DateTime($date_at);
            $dia = $dt->format('d');
            $mes = $dt->format('m');
            $ano = $dt->format('Y');

            $dados = [
                'id_cidadao' => trim($form['id_cidadao']),
                'nome_cidadao' => trim($form['nome_cidadao']),
                'name_created_by' => $_SESSION['user']['nome'],
                'id_created_by' => $_SESSION['user']['id'],

                'descricao' => trim($form['descricao']),
                'descricao_complemento' => trim($form['descricao_complemento']),
                'coordenadorias' => $coordenadorias,
                'id_coordenadoria' => $form['id_coordenadoria'],
                'nome_coordenadoria_selecionada' => $form['nome_coordenadoria_selecionada'],
                'desc_juridica'      => trim($form['descricao_juridica']),
                'num_proc_juridica'  => $form['num_proc_juridica'],
                'sus'                => trim($form['sus']),

                'status_assist'      => 'Iniciada',
                'status_complemento' => 'Assistência Iniciada',
                'date_at'             => $date_at,
                'dia'                   => $dia,
                'mes'                   => $mes,
                'ano'                   => $ano,
                'hora'             => date('H:i'),
                'user_id_coordenadoria' => $user_id_coordenadoria,
                'qual_coordenadoria'    => trim($form['qual_coordenadoria']),
                'home'      => $this->home,

                'descricao_erro' => '',
                'descricao_complemento_erro' => '',
                'id_coordenadoria_erro' => '',
                'status_assist_erro' => '',
                'sus_erro'              => '',
                'num_proc_juridica_erro'    => ''
            ];



            $erro = '';

            if ($dados['id_coordenadoria'] == 0) {
                $dados['id_coordenadoria_erro'] = 'Selecione uma Coordenadoria!';
                $erro = 'erro';
            } else {
                $res_nome_coord = $this->coordenacaoModel->getNomeCoordenadoria($dados['id_coordenadoria']);
                $dados['nome_coordenadoria_selecionada'] = $res_nome_coord['nome']['nome'];
            }

            //SUS
            if ($dados['qual_coordenadoria'] == 'saude') {

                if (!empty($dados['sus']) && strlen($dados['sus']) < 18) {
                    $dados['sus_erro'] = 'Número incompleto!';
                    $erro = 'erro';
                }
                if (!empty($dados['num_proc_juridica']) && strlen($dados['num_proc_juridica']) < 25) {
                    $dados['num_proc_juridica'] = '';
                    $dados['desc_juridica'] = '';
                }
            }

            // Jurídica
            if ($dados['qual_coordenadoria'] == 'juridica') {

                if (!empty($dados['num_proc_juridica']) && strlen($dados['num_proc_juridica']) < 25) {
                    $dados['num_proc_juridica_erro'] = 'Número incompleto!';
                    $erro = 'erro';
                }
                if (!empty($dados['sus']) && strlen($dados['sus']) < 18) {
                    $dados['sus'] = '';
                }
            }

            // Cadastra a Assistência
            if ($erro == '') {

                $cadastro = $this->assistenciaModel->create($dados);

                if ($cadastro['existe'] == 'existe') {

                    Sessao::mensagem('assistencia', 'Assistência existente!', 'alert alert-danger');

                    return $this->cidadaoController->cidadao($id);
                }
                if ($cadastro['erro'] == '' && $cadastro['id_assistencia'] != '') {

                    $dados_up = [
                        'id_assistencia' => $cadastro['id_assistencia'],
                        'id_coordenadoria' => $dados['id_coordenadoria'],
                        'nome_coordenadoria' => $dados['nome_coordenadoria_selecionada'],
                        'id_updated_by' => $_SESSION['user']['id'],
                        'name_updated_by' => $_SESSION['user']['nome'],
                        'status_updated'  => 'Iniciada',
                        'status_compl_updated' => 'Assistência Iniciada',
                    ];

                    // assistencia_update
                    $cadastro_up = $this->assistenciaModel->createUp($dados_up);
                    if ($cadastro_up['erro'] !== '') {

                        $remove_assistencia = $this->assistenciaModel->removeAssistencia($cadastro['id_assistencia']);

                        Sessao::mensagem('assistencia', 'ERRO ao registrar Assistência', 'alert alert-danger');
                    } else {

                        Sessao::mensagem('assistencia', 'Assistência registrada com sucesso!');
                    }

                    return $this->cidadaoController->cidadao($id);
                } else {
                    Sessao::mensagem('assistencia', 'ERRO ao registrar Assistência, tente mais tarde!', 'alert alert-danger');
                }
            }
        } else {

            $dados_cidadao = $this->cidadaoModel->getNomeIdCidadao($id);
            if ($dados_cidadao['erro'] == '' && $dados_cidadao['cidadao'] != '') {

                $id_cidadao = $dados_cidadao['cidadao']['id'];
                $nome_cidadao = $dados_cidadao['cidadao']['nome'];
                $sus = $dados_cidadao['cidadao']['sus'];
            }

            $dados = [
                'id_cidadao' => $id_cidadao,
                'nome_cidadao' => $nome_cidadao,
                'name_created_by' => $_SESSION['user']['nome'],
                'id_created_by' => $_SESSION['user']['id'],

                'descricao' => '',
                'descricao_complemento' => '',
                'coordenadorias' => $coordenadorias,
                'nome_coordenadoria_selecionada' => '',
                'id_coordenadoria' => 0,
                'status_assist' => 0,
                'status_complemento' => '',
                'date_at'   => date('Y-m-d'),
                'hora'   => date('H:i'),
                'user_id_coordenadoria' => $user_id_coordenadoria,
                'home'      => $this->home,
                'sus'       => $sus,
                'desc_juridica'      => '',
                'num_proc_juridica'  => '',

                'descricao_erro' => '',
                'descricao_complemento_erro' => '',
                'id_coordenadoria_erro' => '',
                'status_assist_erro' => '',
                'sus_erro'                   => '',
                'num_proc_juridica_erro'         => '',
            ];
        }

        $this->view('assistencias/create', $dados);
    }

    public function assistencia($id_assistencia)
    {

        $res_assistencia = $this->assistenciaModel->getAssistenciaById($id_assistencia);
        if ($res_assistencia['erro'] == '' && $res_assistencia['assistencia'] != '') {
            $assistencia = $res_assistencia['assistencia'];

            $dt = new DateTime($assistencia['date_at']);
            $data = $dt->format('d/m/Y');
            $data_hora = $data . ' ' . $assistencia['hora'] . ':00';

            $status_atual = $assistencia['status_assist'];
            $status_complemento = $assistencia['status_complemento'];


            //get coordenação


            //get cidadão
            $res_cidadao = $this->cidadaoModel->cidadao($assistencia['id_cidadao']);
            $cidadao = $res_cidadao['cidadao'];
        } else {
            echo ' ERRO ao buscar Assistência';
        }

        // updates
        $updates = [];
        $res_array_assistencias_update = $this->assistenciaModel->getAssistenciasUpdate($id_assistencia);
        if ($res_array_assistencias_update['erro'] == '' && $res_array_assistencias_update['assist_up'] != '') {

            $assistencias_update = array_reverse($res_array_assistencias_update['assist_up']);

            if ($assistencias_update) {
                foreach ($assistencias_update as $up) {

                    $dh = new DateTime($up['updated_at']);
                    $data_hora = $dh->format('d/m/Y H:i:s');

                    if ($up['status_updated'] == 'Iniciada') {
                        $status_compl_updated = $assistencia['descricao'];
                    } else {
                        $status_compl_updated = $up['status_compl_updated'];
                    }

                    $updates[] = [
                        'data_hora' => $data_hora,
                        'status_updated' => $up['status_updated'],
                        'complemento_updated' => $status_compl_updated,
                        'name_updated_by'     => $up['name_updated_by']
                    ];
                }

                $count_up = count($updates) - 1;
                $status_atual = $assistencias_update[$count_up]['status_updated'];

                $status_complemento = $assistencias_update[$count_up]['status_compl_updated'];

                $dh_up = new DateTime($assistencias_update[$count_up]['updated_at']);
                $data_hora = $dh_up->format('d/m/Y H:i:s');
            } else {
                $updates = false;
            }
        } else {
            echo ' ERRO ao buscar Atualizaçoes de Assistência';
        }

        $dados = [
            'assistencia' => $assistencia,
            'data_hora' => $data_hora,
            'data_hora_registro' => $data . ' ' . $assistencia['hora'] . ':00',
            'updates' => $updates,
            'cidadao' => $cidadao,
            'status_atual' => $status_atual,
            'status_complemento' => $status_complemento,
            'count_atualizacoes' => $count_up,
            'home'               => $this->home
        ];

        $this->view('assistencias/assistencia', $dados);
    }

    public function finalizar($id_assistencia)
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


        $this->view('assistencias/finalizar', $dados);
    }

    //Função reescrita de Cidadao
    public function cidadao($id)
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
                    'nome_coordenadoria' => $as['nome_coordenadoria'],
                    'id_coordenadoria'   => $as['id_coordenadoria'],
                    'desc_juridica'   => $as['desc_juridica'],
                    'num_proc_juridica'   => $as['num_proc_juridica']
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
                'idade' => Times::idade_anos($cidadao['cidadao']['data_nascimento']),
                'cpf'       => $cidadao['cidadao']['cpf'],
                'sus'       => $cidadao['cidadao']['sus'],
            ];
        }

        $this->view('cidadao/cidadao', $dados);
    }

    //Funções para Coordenadores

    /**
     * Assistências recentes por Coordenadoria
     */

    public function assist_recentes_coordenadoria()
    {

        if ($_SESSION['user']['id_coordenadoria'] != 0) {
            $id_coordenadoria = $_SESSION['user']['id_coordenadoria'];
        } else {
            return;
        }

        // Assistências recentes
        $res = $this->assistenciaModel->AssistenciasRecentesByCoordenadoria($id_coordenadoria);

        if ($res['erro'] == '' && $res['assistencias'] != '') {

            $assistencias_model = $res['assistencias'];
            $assistencias = [];

            foreach ($assistencias_model as $assist) {

                $status_assistencia = $assist['status_assist'];
                $data = new DateTime($assist['date_at']);
                $data = $data->format('d/m/Y');

                $ultima_alteracao = $data;

                // Cidadão
                $get_cidadao = $this->cidadaoModel->getNomeIdCidadao($assist['id_cidadao']);

                // Update de Assistência
                $get_assistencias_update = $this->assistenciaModel->getAssistenciasUpdate($assist['id']);
                if ($get_assistencias_update['erro'] == '' && $get_assistencias_update['assist_up'] != '') {

                    if ($get_assistencias_update['assist_up']) {

                        $assist_ups_array = $get_assistencias_update['assist_up'];

                        $status_assistencia = $assist_ups_array[0]['status_updated'];

                        $data_up = new DateTime($assist_ups_array[0]['updated_at']);
                        $ultima_alteracao = date_format($data_up, 'd/m/Y');

                        if (count($assist_ups_array) == 1) {
                            $ultima_alteracao = 'Sem atualizações';
                        }
                    }
                }

                $assistencias[] = [
                    'data'      => $data,
                    'descricao' => $assist['descricao'],
                    'nome_cidadao' => $get_cidadao['cidadao']['nome'],
                    'id_assistencia' => $assist['id'],
                    'nome_coordenadoria' => $assist['nome_coordenadoria'],
                    'status_assistencia' => $status_assistencia,
                    'ultima_alteracao'   => $ultima_alteracao
                ];
            }

            $dados = [
                'assistencias' => $assistencias,
                'home'      => $this->home,
            ];
        }

        $this->view('assistencias/assist_recentes', $dados);
    }

    //MINHAS ASSISTÊNCIAS
    public function recentes()
    {

        $assistencias = [];
        $assistencias_res = $this->assistenciaModel->allAssistenciasByOperador($_SESSION['user']['id']);
        if ($assistencias_res['erro'] == '' && $assistencias_res['assistencias'] != '') {


            foreach ($assistencias_res['assistencias'] as $ass) {

                $cidadao = $this->cidadaoModel->getNomeIdCidadao($ass['id_cidadao']);
                $nome_cidadao = $cidadao['cidadao']['nome'];
                $ass['nome_cidadao'] = $nome_cidadao;

                $up = $this->assistenciaModel->getAssistenciasUpdate($ass['id']);
                $ass['update'] = $up['assist_up'];

                // Status atual
                if (count($up['assist_up']) > 1) {
                    $ass['status_assist'] = $up['assist_up'][0]['status_updated'];
                }

                $assistencias[] = $ass;
            }
        } else {
            Sessao::mensagem('assistencia', 'ERRO ao buscar Assistências; tente mais tarde!', 'alert alert-damger');
        }

        $dados = [
            'titulo_pagina' => 'Página Representante',
            'nome_operador' => $_SESSION['user']['nome'],
            'assistencias'  => $assistencias,
            'home'      => $this->home,
        ];

        $this->view('representante/index', $dados);
    }

    //MAIS FILTROS
    public function filtro_coordenadoria() {

        $coordenadorias = '';

        $coord_res = $this->coordenacaoModel->allCoordenadorias();
        if ($coord_res['erro'] == '') {
            $coordenadorias = $coord_res['coordenadorias'];
        } else {
            Sessao::mensagem('assistencias', 'ERRO ao buscar Coordenadorias', 'alert alert-danger');
        }

        $dados = [
            'coordenadorias' => $coordenadorias
        ];

        $this->view('assistencias/filtro_coordenadoria', $dados);
    }
}
