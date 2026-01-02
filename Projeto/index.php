<?php 
    require 'conexao.php';
    try {    
        $sql = "SELECT id, tarefa, descricao, status, data_criacao FROM tarefas ORDER BY data_criacao ASC";
        $exec = $pdo->prepare($sql);
        $exec->execute();
        $tarefas = $exec->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $erro = $e->getMessage();
        $tarefas = [];
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #121212;
            --card-bg: #1e1e1e;
            --text-primary: #e0e0e0;
            --text-secondary: #b0b0b0;
            --accent-color: #BB86FC; /* Roxo suave */
            --danger-color: #CF6679; /* Vermelho suave */
            --success-color: #03DAC6; /* Verde água */
            --border-color: #333;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
        }

        h1 {
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--accent-color);
        }

        /* Estilização do Botão Adicionar */
        .btn-add {
            display: inline-block;
            background-color: var(--accent-color);
            color: #000;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 25px;
            transition: opacity 0.2s;
        }

        .btn-add:hover {
            opacity: 0.8;
        }

        /* Tabela Estilizada como Cards */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px; /* Espaço entre as linhas */
        }

        th {
            text-align: left;
            padding: 15px;
            color: var(--text-secondary);
            font-weight: 400;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        td {
            background-color: var(--card-bg);
            padding: 15px;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        /* Arredondar cantos das linhas */
        td:first-child { border-left: 1px solid var(--border-color); border-radius: 12px 0 0 12px; }
        td:last-child { border-right: 1px solid var(--border-color); border-radius: 0 12px 12px 0; }

        tr:hover td {
            background-color: #252525;
        }

        /* Tags de Status */
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #333;
            color: var(--text-secondary);
        }

        .status-done {
            background: rgba(3, 218, 198, 0.1);
            color: var(--success-color);
        }

        /* Links de Ação */
        .actions a {
            text-decoration: none;
            font-size: 0.9rem;
            margin-left: 10px;
            font-weight: 600;
        }

        .edit-link { color: var(--accent-color); }
        .delete-link { color: var(--danger-color); }

        .date-col { color: var(--text-secondary); font-size: 0.85rem; }

    </style>
</head>
<body>
    <div class="container">
        <h1>Minhas Tarefas</h1>

        <a href="add_tarefa.php" class="btn-add">+ Nova Tarefa</a>
        
        <table>
            <thead>
                <tr>
                    <th>Tarefa</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Criada em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($tarefas) > 0): ?>
                    <?php foreach($tarefas as $tarefa):?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($tarefa['tarefa']) ?></td>
                        <td style="color: var(--text-secondary);"><?= htmlspecialchars($tarefa['descricao']) ?></td>
                        <td>
                           <span class="status-badge <?= ($tarefa['status'] == 'Feito') ? 'status-done' : '' ?>">
                                        <?= htmlspecialchars($tarefa['status']) ?>
                            </span>
                        </td>
                        <td class="date-col"><?= date('d/m/Y', strtotime($tarefa['data_criacao'])) ?></td>
                        <td class="actions">
                            <a href="editar.php?id=<?= $tarefa['id'] ?>" class="edit-link">Editar</a>
                            <a href="excluir.php?id=<?= $tarefa['id'] ?>" class="delete-link" onclick="return confirm('Tem certeza?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-secondary);">Nenhuma tarefa encontrada.</td>
                    </tr>
                <?php endif?>
            </tbody>
        </table>
    </div>
</body>
</html>