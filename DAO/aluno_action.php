<?php
session_start();

require_once 'Conexao.php';
require_once 'Inserir.php';
require_once 'Atualizar.php';
require_once 'Excluir.php';

use Projeto\DAO\Conexao;
use Projeto\DAO\Inserir;
use Projeto\DAO\Atualizar;
use Projeto\DAO\Excluir;

$conexao = new Conexao();

$action = isset($_GET['default_action']) ? $_GET['default_action'] : 'save';

try {
    if ($action == 'delete' && isset($_GET['codigo'])) {
        $codigo = (int)$_GET['codigo'];
        $excluir = new Excluir();
        $excluir->excluirAluno($conexao, $codigo);
        $_SESSION['msg'] = 'Aluno excluído com sucesso!';
        $_SESSION['msg_type'] = 'success';
        
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $codigo = isset($_POST['codigo']) ? (int)$_POST['codigo'] : 0;
        $nome = $_POST['nome'] ?? '';
        $dtNascimento = $_POST['dtNascimento'] ?? '';
        $peso = (float)($_POST['peso'] ?? 0);
        $altura = (float)($_POST['altura'] ?? 0);
        $objetivo = $_POST['objetivo'] ?? '';
        $codigoPlano = (int)($_POST['codigoPlano'] ?? 0);

        if ($codigo > 0) {
            // Update
            $atualizar = new Atualizar();
            $atualizar->atualizarAluno($conexao, $codigo, $nome, $dtNascimento, $peso, $altura, $objetivo, $codigoPlano);
            $_SESSION['msg'] = 'Aluno atualizado com sucesso!';
        } else {
            // Insert
            $inserir = new Inserir();
            $inserir->inserirAluno($conexao, $nome, $dtNascimento, $peso, $altura, $objetivo, $codigoPlano);
            $_SESSION['msg'] = 'Aluno cadastrado com sucesso!';
        }
        $_SESSION['msg_type'] = 'success';
    }
} catch (\Exception $e) {
    $_SESSION['msg'] = 'Erro: ' . $e->getMessage();
    $_SESSION['msg_type'] = 'danger';
}

header('Location: ../index.php?page=alunos');
exit;
