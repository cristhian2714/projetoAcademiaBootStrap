<?php
    session_start();
    require_once __DIR__ . '/Conexao.php';
    require_once __DIR__ . '/Consultar.php';

    use Projeto\DAO\Conexao;
    use Projeto\DAO\Consultar;

    // ── Credenciais do administrador (hardcoded) ──
    define('ADMIN_EMAIL', 'crisegustavo@gmail.com');
    define('ADMIN_SENHA', '123456');

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha']     ?? '';

        if(empty($email) || empty($senha)){
            $_SESSION['login_erro'] = 'Preencha todos os campos.';
            header('Location: ../login_aluno.php');
            exit;
        }//fim if

        // ── Verificar se é o administrador ──
        if($email === ADMIN_EMAIL && $senha === ADMIN_SENHA){
            $_SESSION['aluno_logado'] = true;
            $_SESSION['aluno_nome']   = 'Administrador';
            $_SESSION['aluno_email']  = ADMIN_EMAIL;
            $_SESSION['aluno_plano']  = '';
            $_SESSION['aluno_codigo'] = 0;
            $_SESSION['tipo']         = 'admin';
            header('Location: ../index.php?page=dashboard');
            exit;
        }//fim if

        // ── Verificar se é aluno cadastrado ──
        $conexao   = new Conexao();
        $consultar = new Consultar();
        $aluno     = $consultar->consultarAlunoPorEmail($conexao, $email);

        if($aluno && password_verify($senha, $aluno['senha'])){
            $_SESSION['aluno_logado']  = true;
            $_SESSION['aluno_codigo']  = $aluno['codigo'];
            $_SESSION['aluno_nome']    = $aluno['nome'];
            $_SESSION['aluno_email']   = $aluno['email'];
            $_SESSION['aluno_plano']   = $aluno['plano_nome'];
            $_SESSION['tipo']          = 'aluno';
            // Aluno vai direto para seus treinos
            header('Location: ../index.php?page=treinos&aluno=' . $aluno['codigo']);
            exit;
        }else{
            $_SESSION['login_erro'] = 'E-mail ou senha incorretos.';
            header('Location: ../login_aluno.php');
            exit;
        }//fim if
    }else{
        header('Location: ../login_aluno.php');
        exit;
    }//fim if
?>
