<?php

function iniciarSessao($codMatricula, $nomeAluno) {
    session_start();
    $_SESSION['codMatricula'] = $codMatricula;
    $_SESSION['nomeAluno'] = $nomeAluno;
}


function verificarSessao() {
    session_start();
    
    if (!isset($_SESSION['codMatricula'])) {
    
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

