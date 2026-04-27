<?php
    $codigo = isset($_GET['codigo']) ? (int)$_GET['codigo'] : 0;
    $aluno  = null;

    if($codigo > 0){
        $aluno = $consultar->consultarAluno($conexao, $codigo);
    }//fim if

    $planos = $consultar->consultarPlanos($conexao);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-light"><?= $aluno ? 'Editar Aluno' : 'Cadastrar Aluno' ?></h2>
    <a href="index.php?page=alunos" class="btn btn-outline-secondary">Voltar</a>
</div>

<div class="card bg-dark border-secondary">
    <div class="card-body">
        <form method="POST" action="DAO/aluno_action.php">
            <input type="hidden" name="codigo" value="<?= $aluno ? $aluno['codigo'] : '' ?>">

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label text-light">Nome Completo <span class="text-danger">*</span></label>
                    <input type="text" name="nome" class="form-control bg-dark text-light border-secondary" required value="<?= $aluno ? htmlspecialchars($aluno['nome']) : '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-light">Data de Nascimento <span class="text-danger">*</span></label>
                    <input type="date" name="dtNascimento" class="form-control bg-dark text-light border-secondary" required value="<?= $aluno ? $aluno['dtNascimento'] : '' ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label text-light">Peso (kg) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="peso" class="form-control bg-dark text-light border-secondary" required value="<?= $aluno ? $aluno['peso'] : '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-light">Altura (m) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="altura" class="form-control bg-dark text-light border-secondary" required value="<?= $aluno ? $aluno['altura'] : '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-light">Plano <span class="text-danger">*</span></label>
                    <select name="codigoPlano" class="form-select bg-dark text-light border-secondary" required>
                        <option value="">Selecione...</option>
                        <?php foreach($planos as $p): ?>
                            <option value="<?= $p['codigo'] ?>" class="bg-dark text-light" <?= ($aluno && $aluno['codigoPlano'] == $p['codigo']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nome']) ?> - R$ <?= $p['valor'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label text-light">Objetivo <span class="text-danger">*</span></label>
                    <input type="text" name="objetivo" class="form-control bg-dark text-light border-secondary" placeholder="Ex: Hipertrofia, Emagrecimento, Fortalecimento..." required value="<?= $aluno ? htmlspecialchars($aluno['objetivo']) : '' ?>">
                </div>

                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-success fw-bold text-dark w-100">
                        <i class="bi bi-save"></i> <?= $aluno ? 'Salvar Alterações' : 'Cadastrar' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
