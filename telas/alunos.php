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

    if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['codigo'])){
        $codigo  = (int)$_GET['codigo'];
        $excluir = new Excluir();
        if($excluir->excluirAluno($conexao, $codigo)){
            $mensagem = "<div class='alert alert-success'>Aluno excluído com sucesso!</div>";
        }else{
            $mensagem = "<div class='alert alert-danger'>Erro ao excluir aluno.</div>";
        }//fim if
    }//fim if

    $alunos = $consultar->consultarAlunos($conexao);
?>

<?= $mensagem ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-light">Gestão de Alunos</h2>
    <a href="index.php?page=aluno_form" class="btn btn-success fw-bold text-dark">
        <i class="bi bi-person-plus"></i> Novo Aluno
    </a>
</div>

<div class="card bg-dark border-secondary">
    <div class="card-body p-0 table-responsive border-secondary">
        <table class="table table-dark table-hover mb-0 align-middle">
            <thead class="table-dark" style="border-bottom: 2px solid #198754;">
                <tr>
                    <th class="text-success">#</th>
                    <th class="text-success">Nome</th>
                    <th class="text-success">Data de Nascimento</th>
                    <th class="text-success">Peso (kg)</th>
                    <th class="text-success">Altura (m)</th>
                    <th class="text-success">Objetivo</th>
                    <th class="text-success">Plano</th>
                    <th class="text-end text-success">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($alunos)): ?>
                    <tr><td colspan="8" class="text-center py-4 text-secondary">Nenhum aluno cadastrado.</td></tr>
                <?php else: ?>
                    <?php foreach($alunos as $a): ?>
                        <tr>
                            <td><?= $a['codigo'] ?></td>
                            <td class="fw-bold text-white"><?= htmlspecialchars($a['nome']) ?></td>
                            <td><?= $a['dtNascimento'] ?></td>
                            <td><?= $a['peso'] ?></td>
                            <td><?= $a['altura'] ?></td>
                            <td><?= htmlspecialchars($a['objetivo']) ?></td>
                            <td><span class="badge bg-secondary text-white"><?= $a['plano_nome'] ? htmlspecialchars($a['plano_nome']) : 'Sem plano' ?></span></td>
                            <td class="text-end">
                                <a href="index.php?page=aluno_form&codigo=<?= $a['codigo'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?page=alunos&action=delete&codigo=<?= $a['codigo'] ?>" class="btn btn-sm btn-outline-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
