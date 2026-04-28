<?php
    session_start();
    // Destroi a sessão do aluno
    session_unset();
    session_destroy();
    header('Location: ../login_aluno.php');
    exit;
?>
