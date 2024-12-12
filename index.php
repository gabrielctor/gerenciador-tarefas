<?php
include 'db.php';

// Consulta as tarefas no banco de dados
$sql = "SELECT * FROM tarefas ORDER BY data_entrega ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center">Gerenciador de Tarefas</h1>

    <!-- Botão para adicionar nova tarefa -->
    <div class="mb-3">
        <a href="add_task.php" class="btn btn-success">Adicionar Tarefa</a>
    </div>

    <!-- Tabela de Tarefas -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Data de Entrega</th>
                <th>Data de Criação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['titulo']) ?></td>
                    <td><?= htmlspecialchars($row['descricao']) ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <?php 
                            $dataEntrega = new DateTime($row['data_entrega']);
                            echo $dataEntrega->format('d/m/Y');
                        ?>
                    </td>
                    <td>
                        <?php 
                            $dataCriacao = new DateTime($row['data_criacao']);
                            echo $dataCriacao->format('d/m/Y');
                        ?>
                    </td>
                    <td>
                        <a href="update_task.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="delete_task.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir esta tarefa?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
