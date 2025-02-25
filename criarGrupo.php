<?php
 
 require_once 'Sessao.php';
 verificarSessao();
 
require_once 'conectaBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['codMatricula'])) {
        echo "Erro: Usuário não autenticado.";
        exit();
    }

    $disciplinaNome = $_POST['disciplina'] ?? NULL;
    $horarioData = $_POST['horario'] ?? NULL;
    $localNome = $_POST['local'] ?? NULL;
    $data = $_POST['data'];
    $descricao = $_POST['descricao'];
    $codMatricula = $_SESSION['codMatricula'];

    if (!isset($pdo)) {
        echo "Erro: Falha na conexão com o banco de dados.";
        exit();
    }

    try {
        $pdo->beginTransaction();

        $idDisciplina = NULL;
        if ($disciplinaNome) {
            $stmtDisciplina = $pdo->prepare("SELECT idDisciplina FROM disciplina WHERE nomeDisciplina = :nome");
            $stmtDisciplina->execute([':nome' => $disciplinaNome]);
            $idDisciplina = $stmtDisciplina->fetchColumn();

            if (!$idDisciplina) {
                $stmtInsertDisciplina = $pdo->prepare("INSERT INTO disciplina (nomeDisciplina) VALUES (:nome) RETURNING idDisciplina");
                $stmtInsertDisciplina->execute([':nome' => $disciplinaNome]);
                $idDisciplina = $stmtInsertDisciplina->fetchColumn();
            }
        }

        $idHorario = NULL;
        if ($horarioData) {
            $stmtHorario = $pdo->prepare("SELECT idHorario FROM horario WHERE dataHorario = :data");
            $stmtHorario->execute([':data' => $horarioData]);
            $idHorario = $stmtHorario->fetchColumn();

            if (!$idHorario) {
                $stmtInsertHorario = $pdo->prepare("INSERT INTO horario (dataHorario, horaInicio) VALUES (:data, '00:00') RETURNING idHorario");
                $stmtInsertHorario->execute([':data' => $horarioData]);
                $idHorario = $stmtInsertHorario->fetchColumn();
            }
        }

        $idLugar = NULL;
        if ($localNome) {
            $stmtLugar = $pdo->prepare("SELECT idLugar FROM lugar WHERE nomeLugar = :nome");
            $stmtLugar->execute([':nome' => $localNome]);
            $idLugar = $stmtLugar->fetchColumn();

            if (!$idLugar) {
                $stmtInsertLugar = $pdo->prepare("INSERT INTO lugar (nomeLugar, capacidadeLugar) VALUES (:nome, 10) RETURNING idLugar");
                $stmtInsertLugar->execute([':nome' => $localNome]);
                $idLugar = $stmtInsertLugar->fetchColumn();
            }
        }

        $sqlGrupo = "INSERT INTO grupoEstudo (idHorario, idDisciplina, idLugar, descricao, qtdvagas, codMatricula)
                     VALUES (:horario, :disciplina, :local, :descricao, 5, :codMatricula) RETURNING idGrupoEstudo";

        $stmtGrupo = $pdo->prepare($sqlGrupo);
        $stmtGrupo->execute([
            ':horario' => $idHorario,
            ':disciplina' => $idDisciplina,
            ':local' => $idLugar,
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

        header("Location: criarsala.html?mat=" . urlencode($result['codMatricula']));
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao criar grupo: " . $e->getMessage();
    }
} else {
    echo "Método de requisição inválido.";
}

?>