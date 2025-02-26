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
    return true;
}

function verificarIndex() {
    session_start(); 
    
    if (!isset($_SESSION['codMatricula'])) {
       return false;
    }
    return true;
}



function encerrarSessao() {
    session_start();
    session_unset(); 
    session_destroy(); 
    header("Location: login.php"); 
    exit();
}

?>

