<?php
session_start();
require_once 'conectaBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['codMatricula'])) {
        echo "Erro: Usuário não autenticado.";
        exit();
    }

    $disciplina = $_POST['disciplina'];
    $horario = $_POST['horario'];
    $local = $_POST['local'];
    $data = $_POST['data'];
    $descricao = $_POST['descricao'];
    $codMatricula = $_SESSION['codMatricula'];

    if (!isset($pdo)) {
        echo "Erro: Falha na conexão com o banco de dados.";
        exit();
    }

    try {
        $pdo->beginTransaction();

        $sqlGrupo = "INSERT INTO grupoEstudo (idHorario, idDisciplina, idLugar, descricao, qtdvagas, codMatricula)
                     VALUES (:horario, :disciplina, :local, :descricao, 5, :codMatricula) RETURNING idGrupoEstudo";

        $stmtGrupo = $pdo->prepare($sqlGrupo);
        $stmtGrupo->execute([
            ':horario' => $horario,
            ':disciplina' => $disciplina,
            ':local' => $local,
            ':descricao' => $descricao,
            ':codMatricula' => $codMatricula
        ]);

        $idGrupoEstudo = $stmtGrupo->fetchColumn();

        $sqlParticipacao = "INSERT INTO participacao (dataEntrada, status, codMatricula, idGrupoEstudo)
                            VALUES (:dataEntrada, 'Criador', :codMatricula, :idGrupoEstudo)";

        $stmtParticipacao = $pdo->prepare($sqlParticipacao);
        $stmtParticipacao->execute([
            ':dataEntrada' => $data,
            ':codMatricula' => $codMatricula,
            ':idGrupoEstudo' => $idGrupoEstudo
        ]);

        $pdo->commit();

        header("Location: criarsala.html");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao criar grupo: " . $e->getMessage();
    }
} else {
    echo "Método de requisição inválido.";
}
?>
