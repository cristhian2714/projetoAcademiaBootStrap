<?php
    namespace Projeto\DAO;
    require_once('Conexao.php');
    use Projeto\DAO\Conexao;

    class Inserir{
        function inserirAluno(Conexao $conexao, $nome, $dtNascimento, $peso, $altura, $objetivo, $codigoPlano){
            try{
                $conn         = $conexao->conectar();
                $nome         = mysqli_real_escape_string($conn, $nome);
                $objetivo     = mysqli_real_escape_string($conn, $objetivo);
                $dtNascimento = mysqli_real_escape_string($conn, $dtNascimento);
                $sql          = "INSERT INTO aluno (nome, dtNascimento, peso, altura, objetivo, codigoPlano) 
                                 VALUES ('$nome', '$dtNascimento', $peso, $altura, '$objetivo', $codigoPlano)";
                return mysqli_query($conn, $sql);
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
                return false;
            }
        }//fim do inserirAluno

        function inserirTreino(Conexao $conexao, $codigoAluno, $codigoInstrutor, $objetivo, $frequencia, $duracao){
            try{
                $conn       = $conexao->conectar();
                $objetivo   = mysqli_real_escape_string($conn, $objetivo);
                $frequencia = mysqli_real_escape_string($conn, $frequencia);
                $duracao    = mysqli_real_escape_string($conn, $duracao);
                $sql        = "INSERT INTO treinamento (codigoAluno, codigoInstrutor, objetivo, frequencia, duracao) 
                               VALUES ($codigoAluno, $codigoInstrutor, '$objetivo', '$frequencia', '$duracao')";
                if(mysqli_query($conn, $sql)){
                    return mysqli_insert_id($conn);
                }
                return false;
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
                return false;
            }
        }//fim do inserirTreino

        function inserirExercicioTreino(Conexao $conexao, $codigoTreinamento, $codigoExercicio, $series, $repeticoes){
            try{
                $conn = $conexao->conectar();
                $sql  = "INSERT INTO treinamento_exercicios (codigoTreinamento, codigoExercicio, series, repeticoes) 
                         VALUES ($codigoTreinamento, $codigoExercicio, $series, $repeticoes)";
                return mysqli_query($conn, $sql);
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
                return false;
            }
        }//fim do inserirExercicioTreino
    }//fim da classe
?>
