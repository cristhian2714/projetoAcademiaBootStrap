<?php
    session_start();
    require_once __DIR__ . '/Conexao.php';
    require_once __DIR__ . '/Consultar.php';
    require_once __DIR__ . '/Inserir.php';

    use Projeto\DAO\Conexao;
    use Projeto\DAO\Consultar;
    use Projeto\DAO\Inserir;

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $nome         = trim($_POST['nome']            ?? '');
        $dtNascimento = trim($_POST['dtNascimento']    ?? '');
        $peso         = (float)($_POST['peso']         ?? 0);
        $altura       = (float)($_POST['altura']       ?? 0);
        $objetivo     = trim($_POST['objetivo']        ?? '');
        $email        = trim($_POST['email']           ?? '');
        $senha        = $_POST['senha']                ?? '';
        $confirmar    = $_POST['confirmar_senha']       ?? '';
        $codigoPlano  = (int)($_POST['codigoPlano']    ?? 0);

        // Validações
        if(empty($nome) || empty($dtNascimento) || empty($objetivo) || empty($email) || empty($senha) || $codigoPlano <= 0){
            $_SESSION['cadastro_erro'] = 'Preencha todos os campos obrigatórios.';
            header('Location: ../cadastro_aluno.php');
            exit;
        }

        // Validação de data de nascimento
        $dataObj = DateTime::createFromFormat('Y-m-d', $dtNascimento);
        $hoje    = new DateTime();
        if(!$dataObj || $dataObj > $hoje){
            $_SESSION['cadastro_erro'] = 'A data de nascimento não pode ser uma data futura.';
            header('Location: ../cadastro_aluno.php');
            exit;
        }
        $idade = $hoje->diff($dataObj)->y;
        if($idade < 10){
            $_SESSION['cadastro_erro'] = 'O aluno deve ter no mínimo 10 anos de idade.';
            header('Location: ../cadastro_aluno.php');
            exit;
        }
        if($idade > 100){
            $_SESSION['cadastro_erro'] = 'Data de nascimento inválida (idade máxima: 100 anos).';
            header('Location: ../cadastro_aluno.php');
            exit;
        }

        if(strlen($senha) < 6){
            $_SESSION['cadastro_erro'] = 'A senha deve ter no mínimo 6 caracteres.';
            header('Location: ../cadastro_aluno.php');
            exit;
        }

        if($senha !== $confirmar){
            $_SESSION['cadastro_erro'] = 'As senhas não coincidem.';
            header('Location: ../cadastro_aluno.php');
            exit;
        }

        // Verificar se o email já existe
        $conexao   = new Conexao();
        $consultar = new Consultar();
        $existente = $consultar->consultarAlunoPorEmail($conexao, $email);

        if($existente){
            $_SESSION['cadastro_erro'] = 'Este e-mail já está cadastrado. Faça login.';
            header('Location: ../cadastro_aluno.php');
            exit;
        }

        // Inserir aluno
        $inserir  = new Inserir();
        $resultado = $inserir->inserirAluno($conexao, $nome, $dtNascimento, $peso, $altura, $objetivo, $email, $senha, $codigoPlano);

        if($resultado){
            // Logar automaticamente após cadastro
            $aluno = $consultar->consultarAlunoPorEmail($conexao, $email);
            if($aluno){
                $_SESSION['aluno_logado'] = true;
                $_SESSION['aluno_codigo'] = $aluno['codigo'];
                $_SESSION['aluno_nome']   = $aluno['nome'];
                $_SESSION['aluno_email']  = $aluno['email'];
                $_SESSION['aluno_plano']  = $aluno['plano_nome'];
                header('Location: ../index.php');
                exit;
            }
        }

        $_SESSION['cadastro_erro'] = 'Erro ao criar conta. Tente novamente.';
        header('Location: ../cadastro_aluno.php');
        exit;
    }else{
        header('Location: ../cadastro_aluno.php');
        exit;
    }
?>
