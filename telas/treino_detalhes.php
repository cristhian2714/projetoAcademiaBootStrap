<?php
$codigoTreino = isset($_GET['treino']) ? (int)$_GET['treino'] : 0;

if ($codigoTreino == 0) {
    echo "<div class='alert alert-danger'>Treino não encontrado.</div>";
    exit;
}

// Fetch treino info
$treino = $consultar->consultarTreino($conexao, $codigoTreino);

if (!$treino) {
    echo "<div class='alert alert-danger'>Treino não encontrado.</div>";
    exit;
}

// Fetch general exercícios
$exerciciosDrop = $consultar->consultarExercicios($conexao);

// Fetch already linked exercícios
$exerciciosVinculados = $consultar->consultarExerciciosTreino($conexao, $codigoTreino);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="text-light">Editar Ficha: <?= htmlspecialchars($treino['objetivo']) ?></h2>
        <p class="text-secondary mb-0">Aluno(a): <?= htmlspecialchars($treino['aluno_nome']) ?></p>
    </div>
    <a href="index.php?page=treinos&aluno=<?= $treino['codigoAluno'] ?>" class="btn btn-outline-secondary">Concluir e Voltar</a>
</div>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary text-success fw-bold">
                <i class="bi bi-plus-circle"></i> Adicionar Exercício
            </div>
            <div class="card-body">
                <form method="POST" action="DAO/treino_action.php?default_action=add_exercicio">
                    <input type="hidden" name="codigoTreinamento" value="<?= $codigoTreino ?>">
                    <input type="hidden" name="aluno" value="<?= $treino['codigoAluno'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label text-light">Selecione o Exercício</label>
                        <select name="codigoExercicio" class="form-select bg-dark text-light border-secondary" required>
                            <option value="">Buscar exercício...</option>
                            <?php foreach($exerciciosDrop as $e): ?>
                                <option value="<?= $e['codigo'] ?>" class="bg-dark text-light"><?= htmlspecialchars($e['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label text-light">Séries</label>
                            <input type="number" name="series" class="form-control bg-dark text-light border-secondary" required min="1">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-light">Repetições</label>
                            <input type="number" name="repeticoes" class="form-control bg-dark text-light border-secondary" required min="1">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success fw-bold text-dark w-100">
                        Inserir na Ficha
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-7">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary text-light fw-bold">
                Exercícios na Ficha
            </div>
            <ul class="list-group list-group-flush border-secondary">
                <?php if(empty($exerciciosVinculados)): ?>
                    <li class="list-group-item bg-dark text-secondary text-center py-4 border-secondary">
                        Nenhum exercício adicionado a esta ficha de treino.
                    </li>
                <?php else: ?>
                    <?php foreach($exerciciosVinculados as $ev): ?>
                        <li class="list-group-item bg-dark border-secondary d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-white"><?= htmlspecialchars($ev['exercicio_nome']) ?></strong><br>
                                <span class="badge bg-secondary text-white"><?= $ev['series'] ?> Séries</span>
                                <span class="badge bg-secondary text-white"><?= $ev['repeticoes'] ?> Reps</span>
                            </div>
                            <a href="DAO/treino_action.php?default_action=remove_exercicio&codigo=<?= $ev['codigo'] ?>&treino=<?= $codigoTreino ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Remover exercício da ficha?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
