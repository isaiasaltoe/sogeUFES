<?php

function novaSessao($codMatricula, $nomeAluno) {
    session_start();
    $_SESSION['codMatricula'] = $codMatricula;
    $_SESSION['nomeAluno'] = $nomeAluno;
   
}



function verificarSessao() {
    session_start();
    $codMatricula = $_SESSION['codMatricula'];
    if (!($codMatricula)) {
     
        header("Location: login.php");
        exit();
    }
}

function encerrarSessao() {
    session_start();
    session_unset(); 
    session_destroy(); 
    header("Location: login.php"); 
    exit();
}

?>

