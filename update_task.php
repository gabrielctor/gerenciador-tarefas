<?php
include 'db.php';

// Verifica se o ID foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Seleciona a tarefa a ser editada
    $sql = "SELECT * FROM tarefas WHERE id = $id";
    $result = $conn->query($sql);

    // Verifica se a tarefa existe
    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        echo "Tarefa não encontrada!";
        exit;
    }
} else {
    echo "ID da tarefa não fornecido!";
    exit;
}

// Verifica se o formulário foi enviado (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $data_entrega_input = $_POST['data_entrega']; // Recebe a data no formato dd/mm/aaaa

    // Converte a data para o formato Y-m-d (para o banco de dados)
    $data_entrega = date('Y-m-d', strtotime(str_replace('/', '-', $data_entrega_input)));

    // Atualiza os dados no banco
    $sql = "UPDATE tarefas 
            SET titulo = '$titulo', 
                descricao = '$descricao', 
                status = '$status', 
                data_entrega = '$data_entrega' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit;
    } else {
        echo "Erro ao atualizar a tarefa: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mask-plugin/1.14.15/jquery.mask.min.js"></script>
    <script>
        // Função para validar o formato da data
        function validarData(data) {
            // Expressão regular para validar o formato dd/mm/aaaa
            var regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
            return regex.test(data);
        }

        $(document).ready(function() {
            // Aplica a máscara para o campo de data
            $('#data_entrega').mask('00/00/0000');

            // Validação do formulário
            $('form').submit(function(event) {
                var data = $('#data_entrega').val();
                
                // Verifica se a data está no formato dd/mm/aaaa
                if (!validarData(data)) {
                    alert('Por favor, insira a data no formato dd/mm/aaaa.');
                    event.preventDefault(); // Impede o envio do formulário
                }
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Tarefa</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($task['titulo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?= htmlspecialchars($task['descricao']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Pendente" <?= $task['status'] == 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                    <option value="Em Andamento" <?= $task['status'] == 'Em Andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                    <option value="Concluído" <?= $task['status'] == 'Concluído' ? 'selected' : ''; ?>>Concluído</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="data_entrega" class="form-label">Data de Entrega</label>
                <!-- Campo de texto com máscara de data -->
                <input type="text" class="form-control" id="data_entrega" name="data_entrega" value="<?= date('d/m/Y', strtotime($task['data_entrega'])); ?>" required placeholder="dd/mm/aaaa">
            </div>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
