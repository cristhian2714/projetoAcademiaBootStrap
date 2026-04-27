<?php
    namespace Projeto\DAO;
    require_once('Conexao.php');
    use Projeto\DAO\Conexao;

    class Consultar{
        function consultarAlunos(Conexao $conexao){
            try{
                $conn   = $conexao->conectar();
                $sql    = "SELECT a.*, p.nome as plano_nome 
                           FROM aluno a 
                           LEFT JOIN plano p ON a.codigoPlano = p.codigo 
                           ORDER BY a.codigo DESC";
                $result = mysqli_query($conn, $sql);
                $dados  = [];
                while($row = mysqli_fetch_assoc($result)){
                    $dados[] = $row;
                }//fim while
                return $dados;
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
            }
        }//fim do consultarAlunos

        function consultarAlunosLista(Conexao $conexao){
            try{
                $conn   = $conexao->conectar();
                $sql    = "SELECT * FROM aluno ORDER BY nome";
                $result = mysqli_query($conn, $sql);
                $dados  = [];
                while($row = mysqli_fetch_assoc($result)){
                    $dados[] = $row;
                }//fim while
                return $dados;
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
            }
        }//fim do consultarAlunosLista

        function consultarAluno(Conexao $conexao, int $codigo){
            try{
                $conn   = $conexao->conectar();
                $sql    = "SELECT * FROM aluno WHERE codigo=$codigo";
                $result = mysqli_query($conn, $sql);
                while($dados = mysqli_fetch_assoc($result)){
                    if($dados['codigo'] == $codigo){
                        return $dados;
                    }
                }//fim while
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
            }
            return null;
        }//fim do consultarAluno

        function consultarPlanos(Conexao $conexao){
            try{
                $conn   = $conexao->conectar();
                $sql    = "SELECT * FROM plano ORDER BY nome";
                $result = mysqli_query($conn, $sql);
                $dados  = [];
                while($row = mysqli_fetch_assoc($result)){
                    $dados[] = $row;
                }//fim while
                return $dados;
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
            }
        }//fim do consultarPlanos

        function consultarTreinosAluno(Conexao $conexao, int $codigoAluno){
            try{
                $conn   = $conexao->conectar();
                $sql    = "SELECT t.*, i.nome as instrutor_nome 
                           FROM treinamento t 
                           LEFT JOIN instrutor i ON t.codigoInstrutor = i.codigo 
                           WHERE t.codigoAluno = $codigoAluno 
                           ORDER BY t.codigo DESC";
                $result = mysqli_query($conn, $sql);
                $dados  = [];
                while($row = mysqli_fetch_assoc($result)){
                    $dados[] = $row;
                }//fim while
                return $dados;
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
            }
        }//fim do consultarTreinosAluno

        function consultarExerciciosTreino(Conexao $conexao, int $codigoTreinamento){
            try{
                $conn   = $conexao->conectar();
                $sql    = "SELECT e.*, ex.nome as exercicio_nome, ex.descricao
                           FROM treinamento_exercicios e
                           JOIN exercicio ex ON e.codigoExercicio = ex.codigo
                           WHERE e.codigoTreinamento = $codigoTreinamento";
                $result = mysqli_query($conn, $sql);
                $dados  = [];
                while($row = mysqli_fetch_assoc($result)){
                    $dados[] = $row;
                }//fim while
                return $dados;
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
            }
        }//fim do consultarExerciciosTreino

        function consultarInstrutores(Conexao $conexao){
            try{
                $conn   = $conexao->conectar();
                $sql    = "SELECT * FROM instrutor ORDER BY nome";
                $result = mysqli_query($conn, $sql);
                $dados  = [];
                while($row = mysqli_fetch_assoc($result)){
                    $dados[] = $row;
                }//fim while
                return $dados;
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
            }
        }//fim do consultarInstrutores

        function consultarTreino(Conexao $conexao, int $codigoTreino){
            try{
                $conn   = $conexao->conectar();
                $sql    = "SELECT t.*, a.nome as aluno_nome, i.nome as instrutor_nome 
                           FROM treinamento t 
                           JOIN aluno a ON t.codigoAluno = a.codigo
                           LEFT JOIN instrutor i ON t.codigoInstrutor = i.codigo
                           WHERE t.codigo = $codigoTreino";
                $result = mysqli_query($conn, $sql);
                while($dados = mysqli_fetch_assoc($result)){
                    if($dados['codigo'] == $codigoTreino){
                        return $dados;
                    }
                }//fim while
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
            }
            return null;
        }//fim do consultarTreino

        function consultarExercicios(Conexao $conexao){
            try{
                $conn   = $conexao->conectar();
                $sql    = "SELECT * FROM exercicio ORDER BY nome";
                $result = mysqli_query($conn, $sql);
                $dados  = [];
                while($row = mysqli_fetch_assoc($result)){
                    $dados[] = $row;
                }//fim while
                return $dados;
            }catch(Exception $erro){
                echo "Algo deu errado <br><br>$erro";
            }
        }//fim do consultarExercicios
    }//fim da classe
?>
