<?php
namespace Projeto\DAO;

class Conexao {
    public function conectar() {
        try {
            $conn = mysqli_connect('127.0.0.1', 'root', '', 'academiaDefault');
            if ($conn) {
                mysqli_set_charset($conn, "utf8mb4");
                return $conn;
            }
        } catch (\Exception $erro) {
            echo "Algo deu errado na conexão <br><br>".$erro->getMessage();
        }
        return null;
    }
}
?>
