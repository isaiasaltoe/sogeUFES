<?php
session_start();
require_once 'conectaBD.php';

function iniciarSessao($codMatricula, $senhaAluno) {
    global $pdo;

    try {
        $sql = "SELECT codMatricula, senhaAluno, nomeAluno, emailAluno 
                FROM aluno 
                WHERE codMatricula = :codMatricula AND senhaAluno = :senhaAluno";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':codMatricula' => $codMatricula,
            ':senhaAluno' => $senhaAluno
        ]);

        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Definir as variáveis de sessão
            $_SESSION['codMatricula'] = $result['codMatricula'];
            $_SESSION['nomeAluno'] = $result['nomeAluno'];
            $_SESSION['emailAluno'] = $result['emailAluno'];

            // Redirecionar para a página inicial
            header("Location: index.html");
            exit();
        } else {
            // Falha na autenticação
            session_destroy();
            header("Location: login.html?msgErro=Credenciais inválidas.");
            exit();
        }
    } catch (PDOException $e) {
        die("Erro ao autenticar: " . $e->getMessage());
    }
}

// Verifica se houve envio de formulário e inicia a sessão
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['codMatricula']) && isset($_POST['senhaAluno'])) {
    iniciarSessao($_POST['codMatricula'], $_POST['senhaAluno']);
} else {
    header("Location: login.html?msgErro=Você não tem permissão para acessar esta página.");
    exit();
}
?>
