<?php
/*
 * ============================================================
 *  login_aluno.php — Tela de Login
 * ============================================================
 *  Responsabilidade: exibir o formulário de login e mostrar
 *  mensagens de erro vindas da sessão.
 *
 *  Fluxo:
 *    1. Usuário acessa esta página
 *    2. Preenche e-mail e senha → envia o formulário (POST)
 *    3. O formulário envia os dados para DAO/login_action.php
 *    4. login_action.php valida e redireciona de volta com
 *       mensagem de erro (se houver) via $_SESSION
 * ============================================================
 */

// session_start() DEVE ser chamado antes de qualquer saída HTML
// para que possamos ler/escrever variáveis de sessão
session_start();

// Se o aluno JÁ estiver logado, não precisa ver o login — vai direto pro sistema
if (isset($_SESSION['aluno_logado']) && $_SESSION['aluno_logado'] === true) {
    header('Location: index.php'); // redireciona para o sistema
    exit;                          // para a execução do PHP aqui
}//fim if

// Lê o erro de login (se houver) da sessão
// O operador ?? retorna '' caso a chave não exista, evitando Notice
$erro = $_SESSION['login_erro'] ?? '';

// Remove o erro da sessão para não aparecer na próxima vez que carregar
unset($_SESSION['login_erro']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Gym System Pro</title>
    <meta name="description" content="Acesse sua conta de aluno no Gym System Pro.">

    <!-- Bootstrap 5 CSS — framework de estilização responsiva -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons — ícones para os campos do formulário -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts — tipografia profissional -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* Única customização necessária: fonte global */
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-dark min-vh-100 d-flex align-items-center justify-content-center">
<!--
    bg-dark        → fundo escuro
    min-vh-100     → ocupa pelo menos 100% da altura da tela
    d-flex         → ativa o Flexbox
    align-items-center     → centraliza verticalmente
    justify-content-center → centraliza horizontalmente
-->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">
        <!--
            col-12   → 100% da largura em telas pequenas
            col-md-7 → 7/12 em telas médias
            col-lg-5 → 5/12 em telas grandes (card mais estreito)
        -->

            <!-- ══ CARD DO LOGIN ══ -->
            <div class="card bg-secondary bg-opacity-10 border border-success border-opacity-25 shadow-lg rounded-4">
                <div class="card-body p-4 p-md-5">

                    <!-- ── Cabeçalho / Logo ── -->
                    <div class="text-center mb-4">
                        <!-- Ícone da academia (Bootstrap Icons) -->
                        <div class="bg-success rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:64px; height:64px;">
                            <i class="bi bi-lightning-charge-fill text-dark fs-2"></i>
                        </div>

                        <h1 class="h3 fw-bold text-white mb-1">
                            GYM <span class="text-success">SYSTEM</span>
                        </h1>
                        <p class="text-secondary small mb-0">Área do Aluno</p>
                    </div>
                    <!-- fim cabeçalho -->

                    <!-- ── Alerta de Erro ── -->
                    <?php if (!empty($erro)): ?>
                        <!--
                            Este bloco só é renderizado se houver mensagem de erro.
                            alert-danger → estilo vermelho do Bootstrap
                        -->
                        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert" id="alertaErro">
                            <i class="bi bi-exclamation-circle-fill flex-shrink-0"></i>
                            <span><?= htmlspecialchars($erro) ?></span>
                            <!--
                                htmlspecialchars() converte < > & em entidades HTML
                                para evitar ataques XSS (Cross-Site Scripting)
                            -->
                        </div>
                    <?php endif; ?>

                    <!-- ── Formulário de Login ── -->
                    <!--
                        method="POST" → envia os dados de forma segura (não aparece na URL)
                        action       → para onde o formulário é enviado
                    -->
                    <form method="POST" action="DAO/login_action.php" id="formLogin" novalidate>

                        <!-- Campo E-mail -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-light">
                                <i class="bi bi-envelope me-1"></i> E-mail
                            </label>
                            <input
                                type="email"
                                class="form-control bg-dark text-light border-secondary"
                                id="email"
                                name="email"
                                placeholder="seu@email.com"
                                required
                                autocomplete="email"
                            >
                            <!--
                                type="email"  → navegador valida formato de e-mail
                                name="email"  → chave que chega em $_POST['email']
                                required      → campo obrigatório (validação HTML5)
                                autocomplete  → navegador pode sugerir e-mails salvos
                            -->
                        </div>

                        <!-- Campo Senha -->
                        <div class="mb-4">
                            <label for="senha" class="form-label text-light">
                                <i class="bi bi-lock me-1"></i> Senha
                            </label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    class="form-control bg-dark text-light border-secondary"
                                    id="senha"
                                    name="senha"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="current-password"
                                >
                                <!-- Botão mostrar/ocultar senha -->
                                <button
                                    class="btn btn-outline-secondary"
                                    type="button"
                                    id="btnMostrarSenha"
                                    title="Mostrar/ocultar senha"
                                >
                                    <i class="bi bi-eye" id="iconeSenha"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Botão de Envio -->
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2" id="btnEntrar">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            <span id="textoBotao">Entrar</span>
                        </button>
                        <!--
                            w-100  → largura 100% (ocupa toda a linha)
                            fw-bold → texto em negrito
                            py-2   → padding vertical para o botão ficar maior
                        -->

                    </form>
                    <!-- fim formulário -->

                    <!-- ── Link para Cadastro ── -->
                    <hr class="border-secondary my-4">
                    <p class="text-center text-secondary small mb-0">
                        Não tem uma conta?
                        <a href="cadastro_aluno.php" class="text-success fw-semibold text-decoration-none">
                            Criar conta
                        </a>
                    </p>

                </div><!-- fim card-body -->
            </div><!-- fim card -->

        </div><!-- fim col -->
    </div><!-- fim row -->
</div><!-- fim container -->

<!-- Bootstrap 5 JS (necessário para componentes interativos) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ── Toggle mostrar/ocultar senha ──
    const btnMostrar = document.getElementById('btnMostrarSenha');
    const campoSenha = document.getElementById('senha');
    const icone      = document.getElementById('iconeSenha');

    btnMostrar.addEventListener('click', function () {
        // Alterna entre 'password' (oculto) e 'text' (visível)
        const visivel = campoSenha.type === 'password';
        campoSenha.type = visivel ? 'text' : 'password';
        // Alterna o ícone do olho
        icone.classList.toggle('bi-eye');
        icone.classList.toggle('bi-eye-slash');
    });

    // ── Feedback visual no botão durante o envio ──
    document.getElementById('formLogin').addEventListener('submit', function () {
        const btn   = document.getElementById('btnEntrar');
        const texto = document.getElementById('textoBotao');
        btn.disabled  = true;
        texto.textContent = 'Entrando...';
    });

    // ── Auto-fechar alerta de erro após 5 segundos ──
    const alerta = document.getElementById('alertaErro');
    if (alerta) {
        setTimeout(() => {
            // Bootstrap Alert: fecha o alerta com animação
            const bsAlert = new bootstrap.Alert(alerta);
            bsAlert.close();
        }, 5000);
    }
</script>

</body>
</html>
