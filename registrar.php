<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>Resultado do processamento</h1>
    </header>
    <main>
        <?php 
            require_once 'conectaBD.php';

            if(!empty($_POST)){
                try{
                    $sql = "INSERT INTO aluno
                                (codMatricula, nomeAluno, emailAluno, senhaAluno)
                            VALUES
                                (:codMatricula, :nomeAluno, :emailAluno, :senhaAluno)";
                    $stmt = $pdo->prepare($sql);
                    $dados = array(
                        ':codMatricula' => $_POST["codMatricula"],
                        ':nomeAluno' => $_POST["nomeAluno"],
                        ':emailAluno' => $_POST["emailAluno"],
                        ':senhaAluno' => $_POST["senhaAluno"],
                    );     
                    if ($stmt -> execute($dados)){
                        header("Location: login.html");
                    }
                          
                } catch (PDOException $e) {
                    header("Location: index.html?msgErro=falha ao cadastrar");
                 }

                }
            else{
                header("Location: index.html?msgErro=Requisição inválida");
            }
die();
            
        ?>
    </main>
</body>
</html>