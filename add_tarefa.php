<?php 
    require 'conexao.php';

    $mensagem = "";
    $tipoMensagem = ""; // Para mudar a cor do alerta

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        try{
            $dados = filter_input_array(INPUT_POST, [
                'tarefa' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'descricao' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'status' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                'data_criacao' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
            ]);
            
            $tarefa = $dados['tarefa'];
            $descricao = $dados['descricao'];
            $status = $dados['status'];
            $dataCriacao = $dados['data_criacao'];

            if(empty($tarefa) || empty($descricao)){
                $mensagem = "Os campos Tarefa e Descrição são OBRIGATÓRIOS";
                $tipoMensagem = "erro";
            } else {
                $sql = "INSERT INTO tarefas (tarefa, descricao, status, data_criacao) VALUES (:tarefa, :descricao, :status, :data_criacao)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':tarefa', $tarefa);
                $stmt->bindParam(':descricao', $descricao);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':data_criacao', $dataCriacao);

                if($stmt->execute()){
                    $mensagem = "TAREFA '$tarefa' CADASTRADA COM SUCESSO!";
                    $tipoMensagem = "sucesso";
                }
            }
        } catch(PDOException $e){
            $mensagem = "OCORREU UM ERRO: " . $e->getMessage();
            $tipoMensagem = "erro";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Tarefa</title>
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

        h1 {
            margin-top: 0;
            font-size: 1.5rem;
            color: var(--accent-color);
            text-align: center;
        }

        form { display: flex; flex-direction: column; gap: 15px; }

        label { font-size: 0.9rem; font-weight: 600; color: #888; }

        input, select {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 12px;
            color: white;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus, select:focus { border-color: var(--accent-color); }

        input[type="submit"] {
            background-color: var(--accent-color);
            color: #000;
            font-weight: 700;
            cursor: pointer;
            border: none;
            margin-top: 10px;
            transition: transform 0.1s, opacity 0.2s;
        }

        input[type="submit"]:hover { opacity: 0.9; transform: translateY(-2px); }

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
        <h1>Nova Tarefa</h1>

        <?php if($mensagem): ?>
            <div class="alert <?= $tipoMensagem ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form action="add_tarefa.php" method="post">
            <label for="tarefa">O que precisa ser feito?</label>
            <input type="text" name="tarefa" placeholder="Ex: Estudar PHP" required>

            <label for="descricao">Descrição detalhada</label>
            <input type="text" name="descricao" placeholder="Ex: Ver aula sobre CRUD" required>

            <label for="status">Prioridade/Status</label>
            <select name="status" required>
                <option value="Pendente" selected>Pendente</option>
                <option value="Feito">Feito</option>
            </select>

            <label for="data_criacao">Data limite</label>
            <input type="date" name="data_criacao" value="<?= date('Y-m-d') ?>" required>
            
            <input type="submit" value="Salvar Tarefa">
        </form>

        <a href="index.php" class="btn-back">← Voltar para a lista</a>
    </div>

</body>
</html>