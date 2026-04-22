<?php
namespace Projeto\DAO;
require_once('Conexao.php');
use Projeto\DAO\Conexao;

class Consultar {
    public function consultarAlunos(Conexao $conexao) {
        $conn = $conexao->conectar();
        $sql = "SELECT a.*, p.nome as plano_nome 
                FROM aluno a 
                LEFT JOIN plano p ON a.codigoPlano = p.codigo 
                ORDER BY a.codigo DESC";
        $result = mysqli_query($conn, $sql);
        $dados = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dados[] = $row;
            }
        }
        return $dados;
    }

    public function consultarAlunosLista(Conexao $conexao) {
        $conn = $conexao->conectar();
        $sql = "SELECT * FROM aluno ORDER BY nome";
        $result = mysqli_query($conn, $sql);
        $dados = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dados[] = $row;
            }
        }
        return $dados;
    }

    public function consultarAluno(Conexao $conexao, int $codigo) {
        $conn = $conexao->conectar();
        $sql = "SELECT * FROM aluno WHERE codigo=$codigo";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function consultarPlanos(Conexao $conexao) {
        $conn = $conexao->conectar();
        $sql = "SELECT * FROM plano ORDER BY nome";
        $result = mysqli_query($conn, $sql);
        $dados = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dados[] = $row;
            }
        }
        return $dados;
    }

    public function consultarTreinosAluno(Conexao $conexao, int $codigoAluno) {
        $conn = $conexao->conectar();
        $sql = "SELECT t.*, i.nome as instrutor_nome 
                FROM treinamento t 
                LEFT JOIN instrutor i ON t.codigoInstrutor = i.codigo 
                WHERE t.codigoAluno = $codigoAluno 
                ORDER BY t.codigo DESC";
        $result = mysqli_query($conn, $sql);
        $dados = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dados[] = $row;
            }
        }
        return $dados;
    }

    public function consultarExerciciosTreino(Conexao $conexao, int $codigoTreinamento) {
        $conn = $conexao->conectar();
        $sql = "SELECT e.*, ex.nome as exercicio_nome, ex.descricao
                FROM treinamento_exercicios e
                JOIN exercicio ex ON e.codigoExercicio = ex.codigo
                WHERE e.codigoTreinamento = $codigoTreinamento";
        $result = mysqli_query($conn, $sql);
        $dados = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dados[] = $row;
            }
        }
        return $dados;
    }

    public function consultarInstrutores(Conexao $conexao) {
        $conn = $conexao->conectar();
        $sql = "SELECT * FROM instrutor ORDER BY nome";
        $result = mysqli_query($conn, $sql);
        $dados = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dados[] = $row;
            }
        }
        return $dados;
    }

    public function consultarTreino(Conexao $conexao, int $codigoTreino) {
        $conn = $conexao->conectar();
        $sql = "SELECT t.*, a.nome as aluno_nome, i.nome as instrutor_nome 
                FROM treinamento t 
                JOIN aluno a ON t.codigoAluno = a.codigo
                LEFT JOIN instrutor i ON t.codigoInstrutor = i.codigo
                WHERE t.codigo = $codigoTreino";
        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function consultarExercicios(Conexao $conexao) {
        $conn = $conexao->conectar();
        $sql = "SELECT * FROM exercicio ORDER BY nome";
        $result = mysqli_query($conn, $sql);
        $dados = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dados[] = $row;
            }
        }
        return $dados;
    }
}
?>
