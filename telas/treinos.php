<?php
    require_once __DIR__ . '/../DAO/Conexao.php';
    require_once __DIR__ . '/../DAO/Consultar.php';
    require_once __DIR__ . '/../DAO/Excluir.php';

    use Projeto\DAO\Conexao;
    use Projeto\DAO\Consultar;
    use Projeto\DAO\Excluir;

    $conexao   = new Conexao();
    $consultar = new Consultar();
    $mensagem  = "";

    //Excluir treino
    if(isset($_GET['action']) && $_GET['action'] == 'delete_treino' && isset($_GET['codigo'])){
        $codigoTreino = (int)$_GET['codigo'];
        $excluir      = new Excluir();
        if($excluir->excluirTreino($conexao, $codigoTreino)){
            $mensagem = "<div class='alert alert-success'>Treino excluído com sucesso!</div>";
        }else{
            $mensagem = "<div class='alert alert-danger'>Erro ao excluir treino.</div>";
        }//fim if
    }//fim if

    $codigoAluno = isset($_GET['aluno']) ? (int)$_GET['aluno'] : 0;
    $alunos      = $consultar->consultarAlunosLista($conexao);
    $treinos     = [];

    if($codigoAluno > 0){
        $treinos = $consultar->consultarTreinosAluno($conexao, $codigoAluno);
        foreach($treinos as &$t){
            $t['exercicios'] = $consultar->consultarExerciciosTreino($conexao, $t['codigo']);
        }//fim foreach
    }//fim if
?>

<?= $mensagem ?>

<div class="row align-items-center mb-4">
    <div class="col-md-5">
        <h2 class="text-light">Treino do Dia</h2>
        <p class="text-secondary">Selecione um aluno para ver sua ficha.</p>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="treinos">
            <select name="aluno" class="form-select bg-dark text-light border-secondary" onchange="this.form.submit()">
                <option value="">Selecione um Aluno...</option>
                <?php foreach($alunos as $a): ?>
                    <option value="<?= $a['codigo'] ?>" class="bg-dark text-light" <?= $a['codigo'] == $codigoAluno ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <div class="col-md-3 text-md-end">
        <a href="index.php?page=treino_form<?= $codigoAluno > 0 ? '&aluno=' . $codigoAluno : '' ?>" class="btn btn-outline-success fw-bold w-100">
            <i class="bi bi-plus-circle"></i> Novo Treino
        </a>
    </div>
</div>

<div id="treinos-container">
    <?php if($codigoAluno == 0): ?>
        <div class="text-center text-secondary p-5 mt-4" style="border: 2px dashed #6c757d; border-radius: 12px;">
            <i class="bi bi-search display-1"></i>
            <h4 class="mt-3 text-light">Nenhum aluno selecionado</h4>
            <p>Por favor, selecione um aluno na lista acima.</p>
        </div>
    <?php elseif(empty($treinos)): ?>
        <div class="alert alert-warning mt-4 text-center">
            <i class="bi bi-exclamation-triangle"></i> Este aluno ainda não possui uma ficha de treinamento cadastrada.
        </div>
    <?php else: ?>

        <?php foreach($treinos as $t): ?>
            <div class="card bg-dark text-light border-secondary mt-4 shadow-sm">
                <div class="card-header bg-transparent border-bottom border-secondary py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-success">Ficha: <?= htmlspecialchars($t['objetivo']) ?></h4>
                        <span class="badge bg-secondary text-white"><i class="bi bi-clock"></i> <?= htmlspecialchars($t['duracao']) ?></span>
                    </div>
                    <div class="text-secondary small mt-2">
                        <i class="bi bi-calendar-check"></i> Frequência: <?= htmlspecialchars($t['frequencia']) ?> &nbsp;&nbsp;|&nbsp;&nbsp;
                        <i class="bi bi-person-badge"></i> Instrutor: <?= htmlspecialchars($t['instrutor_nome'] ?? 'Não definido') ?>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="index.php?page=treino_detalhes&treino=<?= $t['codigo'] ?>" class="text-warning text-decoration-none">
                            <i class="bi bi-gear"></i> Editar Exercícios
                        </a>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="index.php?page=treinos&aluno=<?= $codigoAluno ?>&action=delete_treino&codigo=<?= $t['codigo'] ?>" class="text-danger text-decoration-none" onclick="return confirm('Deseja excluir este treino?')">
                            <i class="bi bi-trash"></i> Excluir Treino
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(!empty($t['exercicios'])): ?>
                        <?php foreach($t['exercicios'] as $ex): ?>
                            <div class="bg-secondary p-3 mb-3 rounded border-start border-success border-4 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fs-5 fw-bold mb-1 text-white"><?= htmlspecialchars($ex['exercicio_nome']) ?></div>
                                        <div class="text-light small"><?= htmlspecialchars($ex['descricao'] ?? 'Sem observações') ?></div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success text-dark me-2"><?= $ex['series'] ?> Séries</span>
                                        <span class="badge bg-success text-dark"><?= empty($ex['repeticoes']) ? 'Falha' : $ex['repeticoes'] . ' Reps' ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-secondary my-4">Nenhum exercício vinculado a este treino ainda.
                            <a href="index.php?page=treino_detalhes&treino=<?= $t['codigo'] ?>" class="text-success">Adicionar Exercícios</a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>
</div>
