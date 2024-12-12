<?php
include 'db.php'; // Conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status']; // Recebe o status selecionado
    $data_entrega_input = $_POST['data_entrega']; // Recebe a data no formato dd/mm/aaaa

    // Converte a data para o formato Y-m-d (para o banco de dados)
    $data_entrega = date('Y-m-d', strtotime(str_replace('/', '-', $data_entrega_input)));

    // Prepara e executa a inserção de dados no banco (usando prepared statements para segurança)
    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, status, data_entrega) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $titulo, $descricao, $status, $data_entrega);

    if ($stmt->execute()) {
        // Redireciona de volta para a página principal após o sucesso
        header('Location: index.php');
        exit;
    } else {
        echo "Erro ao adicionar a tarefa: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Tarefa</title>
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

            // Validação do formulário antes de submeter
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
        <h2>Adicionar Nova Tarefa</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Pendente">Pendente</option>
                    <option value="Em Andamento">Em Andamento</option>
                    <option value="Concluído">Concluído</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="data_entrega" class="form-label">Data de Entrega</label>
                <input type="text" class="form-control" id="data_entrega" name="data_entrega" required placeholder="dd/mm/aaaa">
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Tarefa</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
