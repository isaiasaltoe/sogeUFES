<?php
 
require_once 'Sessao.php';
verificarSessao();
require_once 'conectaBD.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['codMatricula'])) {
        echo "Erro: Usuário não autenticado.";
        exit();
    }

    $disciplinaNome = trim($_POST['disciplina'] ?? NULL);
    $horarioData = $_POST['dia'] ?? NULL;
    $salaLugar = trim($_POST['sala'] ?? NULL);
    $horarioHora = $_POST['horario'];
    $predioLugar = trim($_POST['predio']);
    $codMatricula = $_SESSION['codMatricula'];
    $descricao = trim($_POST['descricao']);
     var_dump($_POST);
    if (!isset($pdo)) {
        echo "Erro: Falha na conexão com o banco de dados.";
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Verifica se a disciplina já existe
        $stmtDisciplina = $pdo->prepare("SELECT idDisciplina FROM disciplina WHERE nomeDisciplina = :nome");
        $stmtDisciplina->execute([':nome' => $disciplinaNome]);
        $idDisciplina = $stmtDisciplina->fetchColumn();

       // var_dump($idDisciplina);
        if (!$idDisciplina) {
        
            $stmtDisciplina = $pdo->prepare("INSERT INTO disciplina (nomeDisciplina) VALUES (:nome) RETURNING idDisciplina");
            $stmtDisciplina->execute([':nome' => $disciplinaNome]);
            $idDisciplina = $stmtDisciplina->fetchColumn();
            var_dump($idDisciplina);
        }

       
        $sqlGrupo = "INSERT INTO grupoEstudo (idDisciplina, descricao, qtdVagas, aluno_idCriadorGrupo)
                     VALUES (:disciplina, :descricao, 5, :codMatricula) RETURNING idGrupoEstudo";
        $stmtGrupo = $pdo->prepare($sqlGrupo);
        $stmtGrupo->execute([
            ':disciplina' => $idDisciplina,
            ':descricao' => $descricao,
            ':codMatricula' => $codMatricula
        ]);
        $idGrupoEstudo = $stmtGrupo->fetchColumn();

        // Verifica se o lugar já existe
        $stmtLugar = $pdo->prepare("SELECT idLugar FROM lugar WHERE salaLugar = :sala AND predioLugar = :predio");
        $stmtLugar->execute([
            ':sala' => $salaLugar,
            ':predio' => $predioLugar
        ]);
        $idLugar = $stmtLugar->fetchColumn();

        if (!$idLugar) {
            // Insere novo lugar caso não exista
            $stmtLugar = $pdo->prepare("INSERT INTO lugar (salaLugar, predioLugar) VALUES (:sala, :predio) RETURNING idLugar");
            $stmtLugar->execute([
                ':sala' => $salaLugar,
                ':predio' => $predioLugar
            ]);
            $idLugar = $stmtLugar->fetchColumn();
        }

        // Verifica se o horário já existe
        $stmtHorario = $pdo->prepare("SELECT idHorario FROM horario WHERE dataHorario = :data AND horaInicio = :hora");
        $stmtHorario->execute([
            ':data' => $horarioData,
            ':hora' => $horarioHora
        ]);
        $idHorario = $stmtHorario->fetchColumn();

        if (!$idHorario) {
            
            $stmtHorario = $pdo->prepare("INSERT INTO horario (dataHorario, horaInicio) VALUES (:data, :hora) RETURNING idHorario");
            $stmtHorario->execute([
                ':data' => $horarioData,
                ':hora' => $horarioHora
            ]);
            $idHorario = $stmtHorario->fetchColumn();
        }

        // Insere na agenda
        $sqlAgenda = "INSERT INTO agenda (idLugar, idHorario, idGrupoEstudo) 
                      VALUES (:lugar, :horario, :grupo)";
        $stmtAgenda = $pdo->prepare($sqlAgenda);
        $stmtAgenda->execute([
            ':lugar' => $idLugar,
            ':horario' => $idHorario,
            ':grupo' => $idGrupoEstudo
        ]);

        // Insere a participação do criador do grupo
        $data = date('Y-m-d H:i:s');
        $sqlParticipacao = "INSERT INTO participacao (dataEntrada, situacao, codMatricula, idGrupoEstudo)
                            VALUES (:dataEntrada, 'ativo', :codMatricula, :idGrupoEstudo)";
        $stmtParticipacao = $pdo->prepare($sqlParticipacao);
        $stmtParticipacao->execute([
            ':dataEntrada' => $data,
            ':codMatricula' => $codMatricula,
            ':idGrupoEstudo' => $idGrupoEstudo
        ]);

        $pdo->commit();

        header("Location: criarsala.html?mat=" . urlencode($codMatricula));
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao criar grupo: " . $e->getMessage();
    }
} else {
    echo "Método de requisição inválido.";
}
?>
