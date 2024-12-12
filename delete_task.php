<?php
include 'db.php'; // Conexão com o banco de dados

// Verifica se o parâmetro 'id' foi passado pela URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Comando SQL para excluir a tarefa com base no ID
    $sql = "DELETE FROM tarefas WHERE id = $id";

    // Executa a query
    if ($conn->query($sql) === TRUE) {
        // Redireciona para a página principal após exclusão
        header('Location: index.php');
        exit;
    } else {
        // Exibe mensagem de erro caso algo dê errado
        echo "Erro ao excluir a tarefa: " . $conn->error;
    }
} else {
    // Caso o parâmetro 'id' não tenha sido fornecido
    echo "ID da tarefa não especificado.";
}
?>
