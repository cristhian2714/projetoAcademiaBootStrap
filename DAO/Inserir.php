<?php
    namespace Projeto\DAO;
    require_once('Conexao.php');
    use Projeto\DAO\Conexao;

    class Inserir{
        function inserirAluno(Conexao $conexao, $nome, $dtNascimento, $peso, $altura, $objetivo, $email, $senha, $codigoPlano){
            try{
                $conn         = $conexao->conectar();
                $nome         = mysqli_real_escape_string($conn, $nome);
                $objetivo     = mysqli_real_escape_string($conn, $objetivo);
                $dtNascimento = mysqli_real_escape_string($conn, $dtNascimento);
                $email        = mysqli_real_escape_string($conn, $email);
                $senhaHash    = password_hash($senha, PASSWORD_DEFAULT);
                $sql          = "INSERT INTO aluno (nome, dtNascimento, peso, altura, objetivo, email, senha, codigoPlano) 
                                 VALUES ('$nome', '$dtNascimento', $peso, $altura, '$objetivo', '$email', '$senhaHash', $codigoPlano)";
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
                $sql        = "INSERT INTO treino (codigoAluno, codigoInstrutor, objetivo, frequencia, duracao) 
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

        function inserirExercicioTreino(Conexao $conexao, $codigoTreino, $codigoExercicio, $series, $repeticoes){
            try{
                $conn = $conexao->conectar();
                $sql  = "INSERT INTO treinoExercicio (codigoTreino, codigoExercicio, series, repeticoes) 
                         VALUES ($codigoTreino, $codigoExercicio, $series, $repeticoes)";
                return mysqli_query($conn, $sql);
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
                return false;
            }
        }//fim do inserirExercicioTreino
    }//fim da classe
?>
