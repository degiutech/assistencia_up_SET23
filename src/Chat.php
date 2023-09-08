<?php

namespace Chat;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require '../app/Models/ChatModel.php';

date_default_timezone_set('America/Cuiaba');

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        //Armazene a nova conexão para enviar mensagens para mais tarde
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    // public function onMessage(ConnectionInterface $from, $msg)
    // {
    //     // $numRecv = count($this->clients) - 1;
    //     // echo sprintf(
    //     //     'Connection %d sending message "%s" to %d other connection%s' . "\n",
    //     //     $from->resourceId,
    //     //     $msg,
    //     //     $numRecv,
    //     //     $numRecv == 1 ? '' : 's'
    //     // );

    //     $model = new \ChatModel;

    //     $msg_dec = json_decode($msg, true);

    //     $id_sala = $msg_dec['id_sala'];
    //     $dados = [];

    //     //Criar sala se não existir uma
    //     if (!$id_sala || $id_sala == '') {

    //         //Verificar se existe a sala
    //         $id_sala_res = $model->otherUserSala($msg_dec['id_conversando_com'], $msg_dec['id_user']);



    //         if ($id_sala_res || $id_sala_res != '') {
    //             $id_sala = $id_sala_res;
    //         } else {
    //             $dados = [
    //                 'id_user1' => $msg_dec['id_user'],
    //                 'nome_user1' => $msg_dec['name_user'],
    //                 'id_user2'  => $msg_dec['id_conversando_com'],
    //                 'nome_user2' => $msg_dec['nome_conversando_com'],
    //                 'grupo' => 'dupla'
    //             ];

    //             // Criando a sala
    //             $create_sala_res = $model->createSala($dados);
    //             if ($create_sala_res['erro'] == '') {
    //                 $id_sala = $create_sala_res['id_sala'];
    //             }
    //         }
    //     }


    //     //Pegar toda a conversa de uma sala
    //     if ($msg_dec['acao'] == 'pegar_conversa') {

    //         //Pega as mensagens by id_sala
    //         $msg_res = $model->getMesagesByIdSala($id_sala);
    //         if ($msg_res['erro'] != '') {
    //             exit('ERRO ao buscar conversa!');
    //         }
    //         $dados['mesages'] = $msg_res['mesages'];
    //         $dados['id_sala'] = $id_sala;
    //         $dados['id_user'] = $msg_dec['id_user'];
    //         $dados['id_conversando_com'] = $msg_dec['id_conversando_com'];
    //         $dados['acao'] = 'exibir_sala';

    //         //Quant. de mensagens
    //         $num_msgs = count($msg_res['mesages']);

    //         if ($num_msgs > 20) {
    //             //deletar mensagens 
    //         }
    //     }

    //     //Apenas a nensagem
    //     if ($msg_dec['acao'] == 'apenas_msg') {

    //         //data e hora
    //         $data_hora = date('d/m/Y H:i:s');

    //         $dados = [
    //             'msg'     => $msg_dec['msg'],
    //             'id_sala' => $id_sala,
    //             'id_emissor' => $msg_dec['id_user'],
    //             'nome_emissor' => $msg_dec['name_user'],
    //             'data_hora' => $data_hora
    //         ];

    //         //Insere a mensagem
    //         $insert_msg = $model->insert_mesages($dados);
    //         if ($insert_msg['erro'] != '') {
    //             echo 'ERRO';
    //         }

    //         //Dados para retornar ao front-end
    //         $dados = [
    //             'id_user' => $msg_dec['id_user'],
    //             'name_user' => $msg_dec['nome_conversando_com'],
    //             'id_conversando_com'  => $msg_dec['id_conversando_com'],
    //             'nome_conversando_com' => $msg_dec['name_user'],
    //             'grupo' => 'dupla',
    //             'id_sala' => $id_sala,
    //             'data_hora' => $data_hora,
    //             'msg'       => $msg_dec['msg'],
    //             'acao'      => 'apenas_msg'
    //         ];
    //     }

    //     $msg = json_encode($dados);

    //     foreach ($this->clients as $client) {
    //         // if ($from !== $client) {
    //         // O remetente não é o destinatário, envie para cada cliente conectado
    //         $client->send($msg);
    //         // }
    //     }
    // }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // $numRecv = count($this->clients) - 1;
        // echo sprintf(
        //     'Connection %d sending message "%s" to %d other connection%s' . "\n",
        //     $from->resourceId,
        //     $msg,
        //     $numRecv,
        //     $numRecv == 1 ? '' : 's'
        // );

        $model = new \ChatModel;

        $msg_dec = json_decode($msg, true);

        $id_sala = $msg_dec['id_sala'];
        $dados = [];

        //Criar sala se não existir uma
        if (!$id_sala || $id_sala == '') {

            //Verificar se existe a sala
            $id_sala_res = $model->otherUserSala($msg_dec['id_conversando_com'], $msg_dec['id_user']);



            if ($id_sala_res || $id_sala_res != '') {
                $id_sala = $id_sala_res;
            } else {
                $dados = [
                    'id_user1' => $msg_dec['id_user'],
                    'nome_user1' => $msg_dec['name_user'],
                    'id_user2'  => $msg_dec['id_conversando_com'],
                    'nome_user2' => $msg_dec['nome_conversando_com'],
                    'grupo' => 'dupla'
                ];

                // Criando a sala
                $create_sala_res = $model->createSala($dados);
                if ($create_sala_res['erro'] == '') {
                    $id_sala = $create_sala_res['id_sala'];
                }
            }
        }

        //Leitura da sala - global
        $leitura_sala = '';

        //Pegar toda a conversa de uma sala
        if ($msg_dec['acao'] == 'pegar_conversa') {

            //Pega as mensagens by id_sala
            $msg_res = $model->getMesagesByIdSala($id_sala);
            if ($msg_res['erro'] != '') {
                exit('ERRO ao buscar conversa!');
            }

            //Pega o status de leitura da sala e o id do emissor
            $leitura_atual_sala = $model->statusLeituraEmissorSala($id_sala);
            $leitura_sala = $leitura_atual_sala['leitura'];

            // Mantém ou altera o status de leitura da sala
            $tamanho_msgs = count($msg_res['mesages']);
            if ($tamanho_msgs > 0) {

                if ($leitura_atual_sala['leitura'] == 'Aguardando' && $leitura_atual_sala['id_emissor'] != $msg_dec['id_user']) {
                    // altera o status de leitura da sala
                    $model->updateLeituraEmissorSala($id_sala, 'Lida');
                }
            }

            $dados['mesages'] = $msg_res['mesages'];
            $dados['id_sala'] = $id_sala;
            $dados['id_user'] = $msg_dec['id_user'];
            $dados['id_conversando_com'] = $msg_dec['id_conversando_com'];
            $dados['leitura_atual_sala'] = 'Lida';
            $dados['acao'] = 'exibir_sala';

            //Quant. de mensagens
            $num_msgs = count($msg_res['mesages']);

            if ($num_msgs > 20) {
                //deletar mensagens 
            }
        }

        //Apenas a nensagem
        if ($msg_dec['acao'] == 'apenas_msg') {

            //data e hora
            $data_hora = date('d/m/Y H:i:s');

            $dados = [
                'msg'     => $msg_dec['msg'],
                'id_sala' => $id_sala,
                'id_emissor' => $msg_dec['id_user'],
                'nome_emissor' => $msg_dec['name_user'],
                'id_receptor' => $msg_dec['id_conversando_com'],
                'data_hora' => $data_hora
            ];

            //Insere a mensagem
            $insert_msg = $model->insert_mesages($dados);
            if ($insert_msg['erro'] != '') {
                echo 'ERRO';
            }

            //Altera o status de leitura da sala para Aguardando
            $model->updateLeituraEmissorSala($id_sala, 'Aguardando', $msg_dec['id_user']);

            //Dados para retornar ao front-end
            $dados = [
                'id_user' => $msg_dec['id_user'],
                'name_user' => $msg_dec['nome_conversando_com'],
                'id_conversando_com'  => $msg_dec['id_conversando_com'],
                'nome_conversando_com' => $msg_dec['name_user'],
                'grupo' => 'dupla',
                'id_sala' => $id_sala,
                'data_hora' => $data_hora,
                'msg'       => $msg_dec['msg'],
                'acao'      => 'apenas_msg',
                'leitura_sala' => $leitura_sala,
                'id_emissor' => $msg_dec['id_user'],
                'id_receptor' => $msg_dec['id_conversando_com']
            ];
        }

        if ($msg_dec['acao'] == 'Lida') {
            $model->updateLeituraEmissorSala($id_sala, 'Lida');
        }

        $msg = json_encode($dados);

        foreach ($this->clients as $client) {
            // if ($from !== $client) {
            // O remetente não é o destinatário, envie para cada cliente conectado
            $client->send($msg);
            // }
        }
    }


    public function onClose(ConnectionInterface $conn)
    {
        // A conexão foi encerrada, remova-a, pois não podemos mais enviar mensagens
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    public function criar_sala($dados)
    {
    }
}
