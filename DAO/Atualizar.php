<?php
    namespace Projeto\DAO;
    require_once('Conexao.php');
    use Projeto\DAO\Conexao;

    class Atualizar{
        function atualizarAluno(Conexao $conexao, $codigo, $nome, $dtNascimento, $peso, $altura, $objetivo, $codigoPlano){
            try{
                $conn         = $conexao->conectar();
                $nome         = mysqli_real_escape_string($conn, $nome);
                $objetivo     = mysqli_real_escape_string($conn, $objetivo);
                $dtNascimento = mysqli_real_escape_string($conn, $dtNascimento);
                $sql          = "UPDATE aluno 
                                 SET nome = '$nome', dtNascimento = '$dtNascimento', peso = $peso, altura = $altura, 
                                     objetivo = '$objetivo', codigoPlano = $codigoPlano 
                                 WHERE codigo = $codigo";
                return mysqli_query($conn, $sql);
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
                return false;
            }
        }//fim do atualizarAluno
    }//fim da classe
?>
