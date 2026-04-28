<?php
/*
 * cadastro_aluno.php — Tela de Cadastro de Aluno
 * Fluxo: usuário preenche o form → POST para DAO/cadastro_action.php
 *        → sucesso: login automático + redireciona para index.php
 *        → erro: volta aqui com mensagem via $_SESSION
 */

session_start(); // inicia a sessão para ler/gravar variáveis entre páginas

// Se já estiver logado, redireciona direto para o sistema
if (isset($_SESSION['aluno_logado']) && $_SESSION['aluno_logado'] === true) {
    header('Location: index.php');
    exit;
}//fim if

// Lê mensagens de feedback da sessão (definidas em cadastro_action.php)
$erro    = $_SESSION['cadastro_erro']    ?? '';
$sucesso = $_SESSION['cadastro_sucesso'] ?? '';
unset($_SESSION['cadastro_erro'], $_SESSION['cadastro_sucesso']); // limpa após ler

// Busca planos do banco para popular o <select>
require_once __DIR__ . '/DAO/Conexao.php';
require_once __DIR__ . '/DAO/Consultar.php';
use Projeto\DAO\Conexao;
use Projeto\DAO\Consultar;

$conexao   = new Conexao();    // objeto de conexão
$consultar = new Consultar();  // objeto para consultas
$planos    = $consultar->consultarPlanos($conexao); // array com todos os planos
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro — Gym System Pro</title>
    <meta name="description" content="Crie sua conta de aluno no Gym System Pro.">

    <!-- Bootstrap 5 CSS — todo o estilo vem daqui, sem CSS customizado -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons — ícones vetoriais -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>

<body class="bg-dark min-vh-100 d-flex align-items-center justify-content-center py-5">
<!-- bg-dark=fundo escuro | min-vh-100=altura mínima 100% | d-flex+align+justify=centraliza -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <div class="card bg-secondary bg-opacity-10 border border-success border-opacity-25 shadow-lg rounded-4">
                <div class="card-body p-4 p-md-5">

                    <!-- Cabeçalho -->
                    <div class="text-center mb-4">
                        <div class="bg-success rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:60px;height:60px;">
                            <i class="bi bi-lightning-charge-fill text-dark fs-3"></i>
                        </div>
                        <h1 class="h3 fw-bold text-white mb-1">GYM <span class="text-success">SYSTEM</span></h1>
                        <p class="text-secondary small mb-0">Criar Conta de Aluno</p>
                    </div>

                    <!-- Alertas de feedback -->
                    <?php if (!empty($erro)): ?>
                        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span><?= htmlspecialchars($erro) ?></span>
                            <!-- htmlspecialchars() protege contra XSS -->
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($sucesso)): ?>
                        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-check-circle-fill"></i>
                            <span><?= htmlspecialchars($sucesso) ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Formulário -->
                    <!-- method=POST envia dados de forma segura; action define onde processa -->
                    <form method="POST" action="DAO/cadastro_action.php" id="formCadastro" novalidate>

                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label text-light">
                                <i class="bi bi-person me-1"></i> Nome Completo <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control bg-dark text-light border-secondary"
                                   id="nome" name="nome" placeholder="Seu nome completo" required>
                        </div>

                        <!-- Data de Nascimento + Plano lado a lado -->
                        <!-- row g-3 = linha do grid com espaço entre colunas -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="dtNascimento" class="form-label text-light">
                                    <i class="bi bi-calendar3 me-1"></i> Data de Nascimento <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control bg-dark text-light border-secondary"
                                       id="dtNascimento" name="dtNascimento" required>
                            </div>
                            <div class="col-md-6">
                                <label for="codigoPlano" class="form-label text-light">
                                    <i class="bi bi-star me-1"></i> Plano <span class="text-danger">*</span>
                                </label>
                                <select class="form-select bg-dark text-light border-secondary"
                                        id="codigoPlano" name="codigoPlano" required>
                                    <option value="">Selecione...</option>
                                    <?php
                                    // foreach percorre cada plano retornado pelo banco
                                    if ($planos):
                                        foreach ($planos as $p): ?>
                                        <option value="<?= $p['codigo'] ?>">
                                            <?= htmlspecialchars($p['nome']) ?> — R$ <?= number_format($p['valor'], 2, ',', '.') ?>
                                        </option>
                                    <?php endforeach;
                                    endif; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Peso + Altura lado a lado -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="peso" class="form-label text-light">
                                    <i class="bi bi-speedometer me-1"></i> Peso (kg) <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" min="1"
                                       class="form-control bg-dark text-light border-secondary"
                                       id="peso" name="peso" placeholder="Ex: 75.5" required>
                            </div>
                            <div class="col-md-6">
                                <label for="altura" class="form-label text-light">
                                    <i class="bi bi-arrows-vertical me-1"></i> Altura (m) <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" min="0.5" max="2.5"
                                       class="form-control bg-dark text-light border-secondary"
                                       id="altura" name="altura" placeholder="Ex: 1.75" required>
                            </div>
                        </div>

                        <!-- Objetivo -->
                        <div class="mb-3">
                            <label for="objetivo" class="form-label text-light">
                                <i class="bi bi-bullseye me-1"></i> Objetivo <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control bg-dark text-light border-secondary"
                                   id="objetivo" name="objetivo"
                                   placeholder="Ex: Hipertrofia, Emagrecimento..." required>
                        </div>

                        <!-- E-mail -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-light">
                                <i class="bi bi-envelope me-1"></i> E-mail <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control bg-dark text-light border-secondary"
                                   id="email" name="email" placeholder="seu@email.com"
                                   required autocomplete="email">
                            <!-- type=email faz o navegador validar o formato -->
                        </div>

                        <!-- Senha com botão mostrar/ocultar -->
                        <div class="mb-3">
                            <label for="senha" class="form-label text-light">
                                <i class="bi bi-lock me-1"></i> Senha <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <!-- input-group agrupa input + botão numa linha -->
                                <input type="password" class="form-control bg-dark text-light border-secondary"
                                       id="senha" name="senha" placeholder="Mínimo 6 caracteres"
                                       required minlength="6" autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="btnVerSenha">
                                    <i class="bi bi-eye" id="iconeVerSenha"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirmar Senha -->
                        <div class="mb-4">
                            <label for="confirmarSenha" class="form-label text-light">
                                <i class="bi bi-lock-fill me-1"></i> Confirmar Senha <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control bg-dark text-light border-secondary"
                                   id="confirmarSenha" name="confirmar_senha"
                                   placeholder="Repita a senha" required minlength="6">
                            <!-- invalid-feedback é exibido pelo Bootstrap quando campo tem classe is-invalid -->
                            <div class="invalid-feedback">As senhas não coincidem.</div>
                        </div>

                        <!-- Botão Criar Conta -->
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2" id="btnCriar">
                            <i class="bi bi-person-plus-fill me-1"></i>
                            <span id="textoBotao">Criar Conta</span>
                        </button>

                    </form>

                    <!-- Link para Login -->
                    <hr class="border-secondary my-4">
                    <p class="text-center text-secondary small mb-0">
                        Já possui uma conta?
                        <a href="login_aluno.php" class="text-success fw-semibold text-decoration-none">Fazer login</a>
                    </p>

                </div><!-- fim card-body -->
            </div><!-- fim card -->

        </div><!-- fim col -->
    </div><!-- fim row -->
</div><!-- fim container -->

<!-- Bootstrap 5 JS (necessário para componentes interativos) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /*
     * Validação das senhas antes de enviar o formulário.
     * O servidor TAMBÉM valida em cadastro_action.php, mas esta
     * validação no navegador é mais rápida para o usuário.
     */
    document.getElementById('formCadastro').addEventListener('submit', function (e) {
        const senha    = document.getElementById('senha').value;
        const confirma = document.getElementById('confirmarSenha').value;
        const campo    = document.getElementById('confirmarSenha');

        if (senha !== confirma) {
            e.preventDefault();              // impede o envio
            campo.classList.add('is-invalid'); // Bootstrap mostra borda vermelha + mensagem
            return;
        }//fim if

        // Feedback visual: desabilita botão para evitar duplo envio
        document.getElementById('btnCriar').disabled = true;
        document.getElementById('textoBotao').textContent = 'Criando conta...';
    });

    // Remove erro de validação quando usuário edita o campo
    document.getElementById('confirmarSenha').addEventListener('input', function () {
        this.classList.remove('is-invalid');
    });

    // Toggle mostrar/ocultar senha
    document.getElementById('btnVerSenha').addEventListener('click', function () {
        const campo = document.getElementById('senha');
        const icone = document.getElementById('iconeVerSenha');
        campo.type = campo.type === 'password' ? 'text' : 'password';
        icone.classList.toggle('bi-eye');
        icone.classList.toggle('bi-eye-slash');
    });
</script>

</body>
</html>
