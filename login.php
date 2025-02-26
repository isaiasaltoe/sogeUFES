
<?php
require_once 'conectaBD.php';
require_once 'Sessao.php';



function iniciarSessao($codMatricula, $senhaAluno) {
    global $pdo;

    try {
        $sql = "SELECT codMatricula, senhaAluno, nomeAluno, emailAluno 
                FROM aluno 
                WHERE codMatricula = :codMatricula AND senhaAluno = :senhaAluno";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':codMatricula' => $codMatricula,
            ':senhaAluno' => $senhaAluno,
           
        ]);

        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            novaSessao($result['codmatricula'], $result['nomealuno']);
                
            // Redirecionar para a página inicial
            header("Location: index.php?mat=" . urlencode($result['codMatricula']));
            exit();
            
        } else {
            // Falha na autenticação
           
            header("Location: login.html?msgErro=Credenciais inválidas.");
            exit();
        }
    } catch (PDOException $e) {
        die("Erro ao autenticar: " . $e->getMessage());
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['codMatricula']) && isset($_POST['senhaAluno'])) {
    iniciarSessao($_POST['codMatricula'], $_POST['senhaAluno']);
} else {
    header("Location: login.html?msgErro=Você não tem permissão para acessar esta página.");
    exit();
}
?>
