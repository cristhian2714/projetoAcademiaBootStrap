<?php
namespace Projeto\DAO;
require_once('Conexao.php');
use Projeto\DAO\Conexao;

class Excluir {
    public function excluirAluno(Conexao $conexao, $codigo) {
        try {
            $conn = $conexao->conectar();
            $sql = "DELETE FROM aluno WHERE codigo = $codigo";
            return mysqli_query($conn, $sql);
        } catch (\Exception $erro) {
            echo "Algo deu errado <br><br>".$erro->getMessage();
            return false;
        }
    }

    public function excluirTreino(Conexao $conexao, $codigo) {
        try {
            $conn = $conexao->conectar();
            $sql = "DELETE FROM treinamento WHERE codigo = $codigo";
            return mysqli_query($conn, $sql);
        } catch (\Exception $erro) {
            echo "Algo deu errado <br><br>".$erro->getMessage();
            return false;
        }
    }

    public function excluirExercicioTreino(Conexao $conexao, $codigo) {
        try {
            $conn = $conexao->conectar();
            $sql = "DELETE FROM treinamento_exercicios WHERE codigo = $codigo";
            return mysqli_query($conn, $sql);
        } catch (\Exception $erro) {
            echo "Algo deu errado <br><br>".$erro->getMessage();
            return false;
        }
    }
}
?>
