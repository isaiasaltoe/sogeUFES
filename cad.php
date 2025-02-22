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
            $matricula =$_GET["matricula"];
            $password = $_GET["senha"];
            print "a matricula é ".$matricula. "<br>";
            print "a senha é " .$password;
        ?>
    </main>
</body>
</html>