<?php
    /**
     * Script de migração - adiciona campos de login na tabela aluno.
     * Seguro para rodar múltiplas vezes (verifica se colunas já existem).
     */
    require_once __DIR__ . '/Conexao.php';
    use Projeto\DAO\Conexao;

    $conexao = new Conexao();
    $conn    = $conexao->conectar();

    if(!$conn){
        die("Erro ao conectar no banco de dados.");
    }

    echo "<h3>Migração - Campos de Login</h3><hr>";

    // Verificar se coluna email já existe
    $result = mysqli_query($conn, "SHOW COLUMNS FROM aluno LIKE 'email'");
    if(mysqli_num_rows($result) == 0){
        $sql = "ALTER TABLE aluno ADD COLUMN email VARCHAR(150) NOT NULL DEFAULT '' AFTER objetivo";
        if(mysqli_query($conn, $sql)){
            echo "✅ Coluna 'email' adicionada com sucesso.<br>";
        }else{
            echo "❌ Erro ao adicionar 'email': " . mysqli_error($conn) . "<br>";
        }
    }else{
        echo "ℹ️ Coluna 'email' já existe.<br>";
    }

    // Verificar se coluna senha já existe
    $result = mysqli_query($conn, "SHOW COLUMNS FROM aluno LIKE 'senha'");
    if(mysqli_num_rows($result) == 0){
        $sql = "ALTER TABLE aluno ADD COLUMN senha VARCHAR(255) NOT NULL DEFAULT '' AFTER email";
        if(mysqli_query($conn, $sql)){
            echo "✅ Coluna 'senha' adicionada com sucesso.<br>";
        }else{
            echo "❌ Erro ao adicionar 'senha': " . mysqli_error($conn) . "<br>";
        }
    }else{
        echo "ℹ️ Coluna 'senha' já existe.<br>";
    }

    // Verificar UNIQUE index no email
    $result = mysqli_query($conn, "SHOW INDEX FROM aluno WHERE Column_name = 'email' AND Non_unique = 0");
    if(mysqli_num_rows($result) == 0){
        if(mysqli_query($conn, "ALTER TABLE aluno ADD UNIQUE INDEX unique_email (email)")){
            echo "✅ Índice UNIQUE criado no email.<br>";
        }else{
            echo "⚠️ Aviso UNIQUE: " . mysqli_error($conn) . "<br>";
        }
    }else{
        echo "ℹ️ Índice UNIQUE no email já existe.<br>";
    }

    echo "<hr><strong style='color:lime;'>✅ Banco de dados pronto!</strong><br><br>";
    echo "<a href='../login_aluno.php'>🔑 Ir para Login</a> | ";
    echo "<a href='../cadastro_aluno.php'>📝 Ir para Cadastro</a>";

    mysqli_close($conn);
?>
