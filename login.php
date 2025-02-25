
<?php
require_once 'conectaBD.php';
// Conectar ao BD (com o PHP)
/*
echo '<pre>';
print_r($_POST);
echo '</pre>';
die();
*/

// Verificar se está chegando dados por POST
if (!empty($_POST)) {
  // Iniciar SESSAO (session_start)
  session_start();
  try {
    // Montar a SQL
    $sql = "SELECT codMatricula, senhaAluno, nomeAluno, emailAluno FROM aluno WHERE codMatricula = :codMatricula AND senhaAluno = :senhaAluno";

    // Preparar a SQL (pdo)
    $stmt = $pdo->prepare($sql);

    // Definir/Organizar os dados p/ SQL
    $dados = array(
      ':codMatricula' => $_POST['codMatricula'],
      ':senhaAluno' => ($_POST['senhaAluno'])
    );

    $stmt->execute($dados);
    //$stmt->execute(array(':email' => $_POST['email'], ':senha' => $_POST['senha']));

    $result = $stmt->fetchAll();

    if ($stmt->rowCount() == 1) { // Se o resultado da consulta SQL trouxer um registro
      // Autenticação foi realizada com sucesso

      $result = $result[0];
      // Definir as variáveis de sessão
      $_SESSION['nomeALuno'] = $result['nomeALuno'];
      $_SESSION['emailAluno'] = $result['emailAluno'];
      $_SESSION['codMatricula'] = $result['codMatricula'];
      $_SESSION['senhaAluno'] = $result['senhaAluno'];

      // Redirecionar p/ página inicial (ambiente logado)
      header("Location: index.html");

    } else { // Signifca que o resultado da consulta SQL não trouxe nenhum registro
      // Falha na autenticaçao
      // Destruir a SESSAO
      session_destroy();
      // Redirecionar p/ página inicial (login)
      header("Location: login.html?msgErro=E-mail e/ou Senha inválido(s).");
    }
  } catch (PDOException $e) {
    die($e->getMessage());
  }
}
else {
  header("Location: login.html?msgErro=Você não tem permissão para acessar esta página..");
}
die();
?>

