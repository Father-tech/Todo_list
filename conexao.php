<?php 
    $host = 'localhost'; //indicando o local onde está o banco de dados
    $dbname = 'todo_list'; //Nome do Banco
    $user = 'root'; //Usuario de conexão
    $password = ''; //Senha padrão

    //1- Devemos usar o try para validação de conexão, pois caso dê errado, podemos retornar uma mensagem com o tipo do erro
    try{
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);

        // Configura o PDO para lançar exceções em caso de erros SQL
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo "Falha de conexão " . $e->getMessage();
    }
?>