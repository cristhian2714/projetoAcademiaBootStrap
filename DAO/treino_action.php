<?php
session_start();

require_once 'Conexao.php';
require_once 'Inserir.php';
require_once 'Atualizar.php';
require_once 'Excluir.php';

use Projeto\DAO\Conexao;
use Projeto\DAO\Inserir;
use Projeto\DAO\Excluir;

$conexao = new Conexao();

$action = isset($_GET['default_action']) ? $_GET['default_action'] : '';

try {
    if ($action == 'save_treino' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $codigoAluno = (int)$_POST['codigoAluno'];
        $codigoInstrutor = (int)$_POST['codigoInstrutor'];
        $objetivo = trim($_POST['objetivo']);
        $frequencia = trim($_POST['frequencia']);
        $duracao = trim($_POST['duracao']);

        $inserir = new Inserir();
        $treinoId = $inserir->inserirTreino($conexao, $codigoAluno, $codigoInstrutor, $objetivo, $frequencia, $duracao);

        if ($treinoId) {
            $_SESSION['msg'] = 'Treino criado! Agora adicione os exercícios.';
            $_SESSION['msg_type'] = 'success';
            header("Location: ../index.php?page=treino_detalhes&treino=$treinoId");
        } else {
            $_SESSION['msg'] = 'Erro ao criar treino.';
            $_SESSION['msg_type'] = 'danger';
            header("Location: ../index.php?page=treinos");
        }
        exit;

    } elseif ($action == 'delete_treino' && isset($_GET['codigo'])) {
        $codigo = (int)$_GET['codigo'];
        $alunoId = (int)($_GET['aluno'] ?? 0);
        
        $excluir = new Excluir();
        $excluir->excluirTreino($conexao, $codigo);
        
        $_SESSION['msg'] = 'Treino excluído com sucesso!';
        $_SESSION['msg_type'] = 'success';
        
        header("Location: ../index.php?page=treinos&aluno=$alunoId");
        exit;

    } elseif ($action == 'add_exercicio' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $codigoTreinamento = (int)$_POST['codigoTreinamento'];
        $codigoExercicio = (int)$_POST['codigoExercicio'];
        $series = (int)$_POST['series'];
        $repeticoes = (int)$_POST['repeticoes'];
        
        $inserir = new Inserir();
        $inserir->inserirExercicioTreino($conexao, $codigoTreinamento, $codigoExercicio, $series, $repeticoes);

        // Retorna para a mesma tela de detalhes
        header("Location: ../index.php?page=treino_detalhes&treino=$codigoTreinamento");
        exit;

    } elseif ($action == 'remove_exercicio' && isset($_GET['codigo']) && isset($_GET['treino'])) {
        $codigo = (int)$_GET['codigo'];
        $treinoId = (int)$_GET['treino'];
        
        $excluir = new Excluir();
        $excluir->excluirExercicioTreino($conexao, $codigo);
        
        header("Location: ../index.php?page=treino_detalhes&treino=$treinoId");
        exit;
    }

} catch (\Exception $e) {
    $_SESSION['msg'] = 'Erro: ' . $e->getMessage();
    $_SESSION['msg_type'] = 'danger';
    
    // Redirect heurístico se der erro
    if (isset($_POST['codigoTreinamento']) || isset($_GET['treino'])) {
        $target = isset($_POST['codigoTreinamento']) ? $_POST['codigoTreinamento'] : $_GET['treino'];
        header("Location: ../index.php?page=treino_detalhes&treino=$target");
    } else {
        header('Location: ../index.php?page=treinos');
    }
    exit;
}

header('Location: ../index.php?page=dashboard');
exit;
