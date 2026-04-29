<?php
    session_start();

    require_once 'Conexao.php';
    require_once 'Consultar.php';
    require_once 'Inserir.php';
    require_once 'Atualizar.php';
    require_once 'Excluir.php';

    use Projeto\DAO\Conexao;
    use Projeto\DAO\Consultar;
    use Projeto\DAO\Inserir;
    use Projeto\DAO\Atualizar;
    use Projeto\DAO\Excluir;

    $conexao = new Conexao();
    $action  = isset($_GET['default_action']) ? $_GET['default_action'] : 'save';

    try{
        if($action == 'delete' && isset($_GET['codigo'])){
            $codigo    = (int)$_GET['codigo'];
            $consultar = new Consultar();

            // Verificar se o aluno possui treinos ativos antes de excluir
            $treinosAluno = $consultar->consultarTreinosAluno($conexao, $codigo);
            if(!empty($treinosAluno)){
                $_SESSION['msg']      = 'Não é possível excluir este aluno! Ele possui ' . count($treinosAluno) . ' treino(s) ativo(s). Exclua os treinos primeiro.';
                $_SESSION['msg_type'] = 'danger';
            }else{
                $excluir = new Excluir();
                $excluir->excluirAluno($conexao, $codigo);
                $_SESSION['msg']      = 'Aluno excluído com sucesso!';
                $_SESSION['msg_type'] = 'success';
            }//fim if

        }elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
            $codigo       = isset($_POST['codigo'])       ? (int)$_POST['codigo']       : 0;
            $nome         = $_POST['nome']                ?? '';
            $dtNascimento = $_POST['dtNascimento']        ?? '';
            $peso         = (float)($_POST['peso']        ?? 0);
            $altura       = (float)($_POST['altura']      ?? 0);
            $objetivo     = $_POST['objetivo']            ?? '';
            $email        = $_POST['email']               ?? '';
            $senha        = $_POST['senha']               ?? '';
            $codigoPlano  = (int)($_POST['codigoPlano']   ?? 0);

            // Validação de data de nascimento
            $dataObj = DateTime::createFromFormat('Y-m-d', $dtNascimento);
            $hoje    = new DateTime();
            if(!$dataObj || $dataObj > $hoje){
                throw new Exception('A data de nascimento não pode ser uma data futura.');
            }
            $idade = $hoje->diff($dataObj)->y;
            if($idade < 10){
                throw new Exception('O aluno deve ter no mínimo 10 anos de idade.');
            }
            if($idade > 100){
                throw new Exception('Data de nascimento inválida (idade máxima: 100 anos).');
            }

            if($codigo > 0){
                //Atualizar (senha é opcional — se vazio, mantém a atual)
                $atualizar = new Atualizar();
                $atualizar->atualizarAluno($conexao, $codigo, $nome, $dtNascimento, $peso, $altura, $objetivo, $email, $codigoPlano, $senha);
                $_SESSION['msg'] = 'Aluno atualizado com sucesso!';
            }else{
                //Inserir (email e senha obrigatórios)
                $inserir = new Inserir();
                $inserir->inserirAluno($conexao, $nome, $dtNascimento, $peso, $altura, $objetivo, $email, $senha, $codigoPlano);
                $_SESSION['msg'] = 'Aluno cadastrado com sucesso!';
            }//fim if

            $_SESSION['msg_type'] = 'success';
        }//fim if

    }catch(Exception $e){
        $_SESSION['msg']      = 'Erro: ' . $e->getMessage();
        $_SESSION['msg_type'] = 'danger';
    }//fim try

    header('Location: ../index.php?page=alunos');
    exit;
?>
