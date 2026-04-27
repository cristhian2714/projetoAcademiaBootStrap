<?php
    $codigoAluno = isset($_GET['aluno']) ? (int)$_GET['aluno'] : 0;

    $alunos     = $consultar->consultarAlunosLista($conexao);
    $instrutores= $consultar->consultarInstrutores($conexao);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-light">Cadastrar Novo Treino</h2>
    <a href="index.php?page=treinos<?= $codigoAluno > 0 ? '&aluno=' . $codigoAluno : '' ?>" class="btn btn-outline-secondary">Voltar</a>
</div>

<div class="card bg-dark border-secondary">
    <div class="card-body">
        <form method="POST" action="DAO/treino_action.php?default_action=save_treino">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label text-light">Aluno <span class="text-danger">*</span></label>
                    <select name="codigoAluno" class="form-select bg-dark text-light border-secondary" required>
                        <option value="">Selecione...</option>
                        <?php foreach($alunos as $a): ?>
                            <option value="<?= $a['codigo'] ?>" class="bg-dark text-light" <?= $a['codigo'] == $codigoAluno ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label text-light">Objetivo do Treino <span class="text-danger">*</span></label>
                    <input type="text" name="objetivo" class="form-control bg-dark text-light border-secondary" placeholder="Ex: Hipertrofia A, Adaptação, etc." required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-light">Frequência <span class="text-danger">*</span></label>
                    <input type="text" name="frequencia" class="form-control bg-dark text-light border-secondary" placeholder="Ex: 3x na semana" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-light">Duração <span class="text-danger">*</span></label>
                    <input type="text" name="duracao" class="form-control bg-dark text-light border-secondary" placeholder="Ex: 60 minutos" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-light">Instrutor <span class="text-danger">*</span></label>
                    <select name="codigoInstrutor" class="form-select bg-dark text-light border-secondary" required>
                        <option value="">Selecione...</option>
                        <?php foreach($instrutores as $i): ?>
                            <option value="<?= $i['codigo'] ?>" class="bg-dark text-light">
                                <?= htmlspecialchars($i['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <div class="alert alert-secondary text-center">
                        Após salvar os dados do treino, você será redirecionado para a tela de inclusão de exercícios.
                    </div>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success fw-bold text-dark w-100">
                        <i class="bi bi-arrow-right-circle"></i> Continuar e Adicionar Exercícios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
