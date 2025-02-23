<?php 
require_once 'conectaBD.php';

session_start();

if(!isset($_SESSION['matricula'])){
    header('Location: login.html');
    exit();
}

$codMatricula = $_SESSION['matricula'];

if($_SERVER["REQUEST_METHOD"] == "POST"){

    try{
        // Captura de dados do formulário
        $disciplinaNome = trim($_POST['disciplina']);
        $horario = trim($_POST['horario']);
        $local = trim($_POST['local']);
        $data = trim($_POST['data']);
        $descricao = trim($_POST['descricao']);
        $qtdVagas = 5; 
        
        // Buscar o ID da disciplina a partir do nome
        $sqlDisciplina = "SELECT idDisciplina FROM disciplina WHERE nomeDisciplina = :disciplinaNome";
        $stmtDisciplina = $pdo->prepare($sqlDisciplina);
        $stmtDisciplina->bindParam(':disciplinaNome', $disciplinaNome);
        $stmtDisciplina->execute();
        $disciplina = $stmtDisciplina->fetch(PDO::FETCH_ASSOC);

        if ($disciplina) {
            $idDisciplina = $disciplina['idDisciplina'];
        } else {
            echo "Disciplina não encontrada.";
            exit();
        }

        // Buscar o ID do horário
        $sqlHorario = "SELECT idHorario FROM horario WHERE dataHorario = :data";
        $stmtHorario = $pdo->prepare($sqlHorario);
        $stmtHorario->bindParam(':data', $data);
        $stmtHorario->execute();
        $horario = $stmtHorario->fetch(PDO::FETCH_ASSOC);

        if ($horario) {
            $idHorario = $horario['idHorario'];
        } else {
            echo "Horário não encontrado.";
            exit();
        }

        // Buscar o ID do local
        $sqlLocal = "SELECT idLugar FROM lugar WHERE nomeLugar = :local";
        $stmtLocal = $pdo->prepare($sqlLocal);
        $stmtLocal->bindParam(':local', $local);
        $stmtLocal->execute();
        $lugar = $stmtLocal->fetch(PDO::FETCH_ASSOC);

        if ($lugar) {
            $idLugar = $lugar['idLugar'];
        } else {
            echo "Local não encontrado.";
            exit();
        }

        // Inserir o grupo de estudo com os IDs encontrados
        $sql = "INSERT INTO grupoEstudo (idDisciplina, idHorario, idLugar, descricao, qtdvagas, codMatricula)
                VALUES (:idDisciplina, :idHorario, :idLugar, :descricao, :qtdvagas, :matricula)";

        // Preparar o statement
        $stmt = $pdo->prepare($sql);

        // Bind dos parâmetros
        $stmt->bindParam(':idDisciplina', $idDisciplina);
        $stmt->bindParam(':idHorario', $idHorario);
        $stmt->bindParam(':idLugar', $idLugar);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':qtdvagas', $qtdVagas);
        $stmt->bindParam(':matricula', $codMatricula);

        // Executar a query
        if ($stmt->execute()) {
            echo "Grupo criado com sucesso!";
        } else {
            echo "Erro ao criar o grupo.";
        }
        
    } catch(PDOException $e) {
        echo "Erro ao realizar operação: " . $e->getMessage();
    }
}
?>
