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

 
        $stmtLugar = $pdo->prepare("SELECT idLugar FROM lugar WHERE salaLugar = :sala AND predioLugar = :predio");
        $stmtLugar->execute([
            ':sala' => $salaLugar,
            ':predio' => $predioLugar
        ]);
        $idLugar = $stmtLugar->fetchColumn();

        if (!$idLugar) {
          
            $stmtLugar = $pdo->prepare("INSERT INTO lugar (salaLugar, predioLugar) VALUES (:sala, :predio) RETURNING idLugar");
            $stmtLugar->execute([
                ':sala' => $salaLugar,
                ':predio' => $predioLugar
            ]);
            $idLugar = $stmtLugar->fetchColumn();
        }

    
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
            
        $stmtVerificaGrupo = $pdo->prepare("
        SELECT agenda.idGrupoEstudo
        FROM agenda
        INNER JOIN grupoEstudo ON agenda.idGrupoEstudo = grupoEstudo.idGrupoEstudo
        WHERE agenda.idLugar = :lugar 
          AND agenda.idHorario = :horario
    ");
    $stmtVerificaGrupo->execute([
        ':lugar' => $idLugar,
        ':horario' => $idHorario
    ]);
    $grupoExistente = $stmtVerificaGrupo->fetchColumn();

    $dataAtual = date('Y-m-d');
    if ($horarioData < $dataAtual) {
        echo "Erro: Erro ao escolher dia ";
        $pdo->rollBack();
        header("Location: criarGrupo.php?msgErro=falha");
        exit();
    }   


    if ($grupoExistente) {
        echo "Erro: Já existe um grupo de estudo agendado para este horário e local.";
        $pdo->rollBack();
        exit();
    }   

        $sqlAgenda = "INSERT INTO agenda (idLugar, idHorario, idGrupoEstudo) 
                      VALUES (:lugar, :horario, :grupo)";
        $stmtAgenda = $pdo->prepare($sqlAgenda);
        $stmtAgenda->execute([
            ':lugar' => $idLugar,
            ':horario' => $idHorario,
            ':grupo' => $idGrupoEstudo
        ]);

        
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

        header("Location: grupo.php?id=" . urlencode($idGrupoEstudo));
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao criar grupo: " . $e->getMessage();
    }
} else {
    echo "Método de requisição inválido.";
}
?>
