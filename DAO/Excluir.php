<?php
    namespace Projeto\DAO;
    require_once('Conexao.php');
    use Projeto\DAO\Conexao;

    class Excluir{
        function excluirAluno(Conexao $conexao, $codigo){
            try{
                $conn = $conexao->conectar();
                $sql  = "DELETE FROM aluno WHERE codigo = $codigo";
                return mysqli_query($conn, $sql);
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
                return false;
            }
        }//fim do excluirAluno

        function excluirTreino(Conexao $conexao, $codigo){
            try{
                $conn = $conexao->conectar();
                $sql  = "DELETE FROM treinamento WHERE codigo = $codigo";
                return mysqli_query($conn, $sql);
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
                return false;
            }
        }//fim do excluirTreino

        function excluirExercicioTreino(Conexao $conexao, $codigo){
            try{
                $conn = $conexao->conectar();
                $sql  = "DELETE FROM treinamento_exercicios WHERE codigo = $codigo";
                return mysqli_query($conn, $sql);
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
                return false;
            }
        }//fim do excluirExercicioTreino
    }//fim da classe
?>
