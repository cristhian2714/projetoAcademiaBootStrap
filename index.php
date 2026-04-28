<?php
    session_start();

    // ── Proteção de rota: exige login ──
    if(!isset($_SESSION['aluno_logado']) || $_SESSION['aluno_logado'] !== true){
        header('Location: login_aluno.php');
        exit;
    }//fim if

    $isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';

    $page = isset($_GET['page']) ? $_GET['page'] : ($isAdmin ? 'dashboard' : 'treinos');

    // ── Páginas permitidas por role ──
    $admin_pages = ['dashboard', 'alunos', 'aluno_form', 'treinos', 'treino_form', 'treino_detalhes'];
    $aluno_pages = ['treinos', 'treino_detalhes'];

    $allowed_pages = $isAdmin ? $admin_pages : $aluno_pages;

    if(!in_array($page, $allowed_pages)){
        $page = $isAdmin ? 'dashboard' : 'treinos';
    }//fim if

    // ── Se for aluno, força sempre a ver os SEUS próprios treinos ──
    if(!$isAdmin && $page === 'treinos'){
        // Se não tiver o param aluno na URL, força para o código da sessão
        if(!isset($_GET['aluno'])){
            header('Location: index.php?page=treinos&aluno=' . $_SESSION['aluno_codigo']);
            exit;
        }//fim if
        // Impede aluno de ver treinos de outro aluno
        if((int)$_GET['aluno'] !== (int)$_SESSION['aluno_codigo']){
            header('Location: index.php?page=treinos&aluno=' . $_SESSION['aluno_codigo']);
            exit;
        }//fim if
    }//fim if
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isAdmin ? 'Admin – Gym System Pro' : 'Meus Treinos – Gym System Pro' ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body class="bg-dark text-light" style="font-family: 'Inter', sans-serif;">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top py-3 border-bottom border-secondary">
        <div class="container">
            <a class="navbar-brand text-success fw-bold" href="index.php">
                <i class="bi bi-lightning-charge-fill"></i> GYM SYSTEM
                <?php if($isAdmin): ?>
                    <span class="badge bg-warning text-dark ms-2" style="font-size:10px;">ADMIN</span>
                <?php endif; ?>
            </a>

            <div class="navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <?php if($isAdmin): ?>
                        <!-- NAV ADMIN -->
                        <li class="nav-item">
                            <a class="nav-link <?= $page == 'dashboard' ? 'active' : '' ?>" href="index.php?page=dashboard">
                                <i class="bi bi-grid"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($page, 'aluno') !== false ? 'active' : '' ?>" href="index.php?page=alunos">
                                <i class="bi bi-people"></i> Alunos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($page, 'treino') !== false ? 'active' : '' ?>" href="index.php?page=treinos">
                                <i class="bi bi-activity"></i> Treinos
                            </a>
                        </li>
                    <?php else: ?>
                        <!-- NAV ALUNO -->
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php?page=treinos&aluno=<?= $_SESSION['aluno_codigo'] ?>">
                                <i class="bi bi-activity"></i> Meus Treinos
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Usuário logado + Sair -->
                    <li class="nav-item ms-3 d-flex align-items-center">
                        <span class="text-secondary me-3">
                            <i class="bi bi-<?= $isAdmin ? 'shield-check' : 'person-circle' ?>"></i>
                            <?= htmlspecialchars($_SESSION['aluno_nome']) ?>
                        </span>
                        <a class="btn btn-outline-danger btn-sm" href="DAO/logout_action.php">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5" id="main-container">
        <?php include "telas/{$page}.php"; ?>
    </div>

</body>
</html>
