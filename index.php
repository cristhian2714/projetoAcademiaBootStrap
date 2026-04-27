<?php
    session_start();
    $page          = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
    $allowed_pages = ['dashboard', 'alunos', 'aluno_form', 'treinos', 'treino_form', 'treino_detalhes'];

    if(!in_array($page, $allowed_pages)){
        $page = 'dashboard';
    }//fim if
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym System Pro</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS (Apenas CSS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
</head>
<body class="bg-dark text-light" style="font-family: 'Inter', sans-serif;">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top py-3 border-bottom border-secondary">
        <div class="container">
            <a class="navbar-brand text-success fw-bold" href="index.php?page=dashboard">
                <i class="bi bi-lightning-charge-fill"></i> GYM SYSTEM
            </a>
            
            <div class="navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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
