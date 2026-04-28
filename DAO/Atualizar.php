<?php
    namespace Projeto\DAO;
    require_once('Conexao.php');
    use Projeto\DAO\Conexao;

    class Atualizar{
        function atualizarAluno(Conexao $conexao, $codigo, $nome, $dtNascimento, $peso, $altura, $objetivo, $email, $codigoPlano, $senha = ''){
            try{
                $conn         = $conexao->conectar();
                $nome         = mysqli_real_escape_string($conn, $nome);
                $objetivo     = mysqli_real_escape_string($conn, $objetivo);
                $dtNascimento = mysqli_real_escape_string($conn, $dtNascimento);
                $email        = mysqli_real_escape_string($conn, $email);

                if(!empty($senha)){
                    // Atualiza com nova senha
                    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                    $sql = "UPDATE aluno 
                            SET nome = '$nome', dtNascimento = '$dtNascimento', peso = $peso, altura = $altura, 
                                objetivo = '$objetivo', email = '$email', senha = '$senhaHash', codigoPlano = $codigoPlano 
                            WHERE codigo = $codigo";
                }else{
                    // Mantém a senha atual
                    $sql = "UPDATE aluno 
                            SET nome = '$nome', dtNascimento = '$dtNascimento', peso = $peso, altura = $altura, 
                                objetivo = '$objetivo', email = '$email', codigoPlano = $codigoPlano 
                            WHERE codigo = $codigo";
                }//fim if
                return mysqli_query($conn, $sql);
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
                return false;
            }
        }//fim do atualizarAluno
    }//fim da classe
?>
