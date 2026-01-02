<?php 
    require 'conexao.php';

    $mensagem = '';
    $tipoMensagem = '';
    $tarefa = null;

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        try{
            $sql = "SELECT * FROM tarefas WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$tarefa){
                die("Tarefa não encontrada.");
            }
        }catch(PDOException $e){
            $mensagem = "Erro: " . $e->getMessage();
            $tipoMensagem = "erro";
        }
    }

   if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = $_POST['id'];
        $input_tarefa = $_POST['tarefa']; 
        $descricao = $_POST['descricao'];
        $status = $_POST['status'];
        $data_criacao = $_POST['data_criacao'];

        try {
            $sql = "UPDATE tarefas SET tarefa = :tarefa, descricao = :descricao, status = :status, data_criacao = :data_criacao WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':tarefa', $input_tarefa);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':data_criacao', $data_criacao);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            $mensagem = "Tarefa atualizada com sucesso!";
            $tipoMensagem = "sucesso";

            // Atualiza os dados na tela
            $tarefa['tarefa'] = $input_tarefa;
            $tarefa['descricao'] = $descricao;
            $tarefa['status'] = $status;
            $tarefa['data_criacao'] = $data_criacao;

        } catch(PDOException $e){
            $mensagem = "Erro: " . $e->getMessage();
            $tipoMensagem = "erro";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #121212;
            --card-bg: #1e1e1e;
            --text-primary: #e0e0e0;
            --accent-color: #BB86FC;
            --input-bg: #2c2c2c;
            --border-color: #333;
            --success-bg: rgba(3, 218, 198, 0.1);
            --success-text: #03DAC6;
            --error-bg: rgba(207, 102, 121, 0.1);
            --error-text: #CF6679;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background-color: var(--card-bg);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 450px;
            border: 1px solid var(--border-color);
        }

        h1 { margin-top: 0; font-size: 1.5rem; color: var(--accent-color); text-align: center; }

        form { display: flex; flex-direction: column; gap: 15px; }

        label { font-size: 0.85rem; font-weight: 600; color: #888; margin-bottom: -10px; }

        input, select {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            color: white;
            font-size: 1rem;
            outline: none;
        }

        input:focus, select:focus { border-color: var(--accent-color); }

        button[type="submit"] {
            background-color: var(--accent-color);
            color: #000;
            font-weight: 700;
            cursor: pointer;
            border: none;
            padding: 14px;
            border-radius: 8px;
            margin-top: 10px;
            transition: 0.2s;
        }

        button[type="submit"]:hover { opacity: 0.9; }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
        }
        .sucesso { background: var(--success-bg); color: var(--success-text); border: 1px solid var(--success-text); }
        .erro { background: var(--error-bg); color: var(--error-text); border: 1px solid var(--error-text); }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #888;
            text-decoration: none;
            font-size: 0.85rem;
        }
        .btn-back:hover { color: var(--accent-color); }
    </style>
</head>
<body>

    <div class="card">
        <h1>Editar Tarefa</h1>

        <?php if($mensagem): ?>
            <div class="alert <?= $tipoMensagem ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form action="editar.php?id=<?= $tarefa['id'] ?>" method="post">
            <input type="hidden" name="id" value="<?= $tarefa['id'] ?>">

            <label for="tarefa">Tarefa</label>
            <input type="text" name="tarefa" value="<?= $tarefa['tarefa'] ?>" required>

            <label for="descricao">Descrição</label>
            <input type="text" name="descricao" value="<?= $tarefa['descricao'] ?>" required>

            <label for="status">Status Atual</label>
            <select name="status">
                <option value="Pendente" <?= $tarefa['status'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="Feito" <?= $tarefa['status'] == 'Feito' ? 'selected' : '' ?>>Feito</option>
            </select>

            <label for="data_criacao">Data de Registro</label>
            <input type="date" name="data_criacao" value="<?= $tarefa['data_criacao'] ?>">

            <button type="submit">Salvar Alterações</button>
        </form>

        <a href="index.php" class="btn-back">← Voltar para a lista</a>
    </div>

</body>
</html>