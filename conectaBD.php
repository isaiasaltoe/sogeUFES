<?php 
    //endereco
    //nome do BD
    //usuario
    //senha
    $endereco = 'localhost';
<<<<<<< HEAD
    $banco = 'soge';
    $usuario = 'postgres';
    $senha = 'Neguim03';
=======
    $banco = 'SOGE';
    $usuario = 'postgres';
    $senha = 'adler';
>>>>>>> 1eccffa8a79d75a074bcc7b54e9ac885b5e52698



    try{
     //sgbd:host;port;DBname
     //usuario
     //senha
     //errmode 
    $pdo  = new PDO("pgsql:host=$endereco;port=5432;dbname=$banco ", $usuario, $senha, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); 
    //echo "Conectado no banco de dados!";


    }catch(PDOException $e){
     echo "Falha ao conectar ao banco de dados. <br/>";
     die($e->getMessage());    
    }

?>