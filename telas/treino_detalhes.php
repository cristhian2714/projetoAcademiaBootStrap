<?php
    require_once __DIR__ . '/../DAO/Conexao.php';
    require_once __DIR__ . '/../DAO/Consultar.php';
    require_once __DIR__ . '/../DAO/Inserir.php';
    require_once __DIR__ . '/../DAO/Excluir.php';

    use Projeto\DAO\Conexao;
    use Projeto\DAO\Consultar;
    use Projeto\DAO\Inserir;
    use Projeto\DAO\Excluir;

    $conexao   = new Conexao();
    $consultar = new Consultar();
    $mensagem  = "";

    $codigoTreino = isset($_GET['treino']) ? (int)$_GET['treino'] : 0;

    if($codigoTreino == 0){
        echo "<div class='alert alert-danger'>Treino não encontrado.</div>";
        exit;
    }//fim if

    //Adicionar exercício
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codigoExercicio'])){
        $codigoExercicio = (int)$_POST['codigoExercicio'];
        $series          = (int)$_POST['series'];
        $repeticoes      = (int)$_POST['repeticoes'];

        $inserir = new Inserir();
        if($inserir->inserirExercicioTreino($conexao, $codigoTreino, $codigoExercicio, $series, $repeticoes)){
            $mensagem = "<div class='alert alert-success'>Exercício adicionado com sucesso!</div>";
        }else{
            $mensagem = "<div class='alert alert-danger'>Erro ao adicionar exercício.</div>";
        }//fim if
    }//fim if

    //Remover exercício
    if(isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['codigo'])){
        $codigoExercicioTreino = (int)$_GET['codigo'];
        $excluir               = new Excluir();
        if($excluir->excluirExercicioTreino($conexao, $codigoExercicioTreino)){
            $mensagem = "<div class='alert alert-success'>Exercício removido da ficha!</div>";
        }else{
            $mensagem = "<div class='alert alert-danger'>Erro ao remover exercício.</div>";
        }//fim if
    }//fim if

    $treino               = $consultar->consultarTreino($conexao, $codigoTreino);
    $exerciciosDrop       = $consultar->consultarExercicios($conexao);
    $exerciciosVinculados = $consultar->consultarExerciciosTreino($conexao, $codigoTreino);

    if(!$treino){
        echo "<div class='alert alert-danger'>Treino não encontrado.</div>";
        exit;
    }//fim if
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="text-light">Editar Ficha: <?= htmlspecialchars($treino['objetivo']) ?></h2>
        <p class="text-secondary mb-0">Aluno(a): <?= htmlspecialchars($treino['aluno_nome']) ?></p>
    </div>
    <a href="index.php?page=treinos&aluno=<?= $treino['codigoAluno'] ?>" class="btn btn-outline-secondary">Concluir e Voltar</a>
</div>

<?= $mensagem ?>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card bg-dark border-secondary">
            <div class="card-header border-secondary text-success fw-bold">
                <i class="bi bi-plus-circle"></i> Adicionar Exercício
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=treino_detalhes&treino=<?= $codigoTreino ?>">
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
                            <a href="index.php?page=treino_detalhes&treino=<?= $codigoTreino ?>&action=remove&codigo=<?= $ev['codigo'] ?>"
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
