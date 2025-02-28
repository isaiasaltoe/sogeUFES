<?php 

require_once 'Sessao.php';
verificarSessao();
require_once 'conectaBD.php';

$idgrupoestudo = isset($_GET['id']) ? intval($_GET['id']) : null;
$grupo = null;

if ($idgrupoestudo) {
    $sql = "SELECT ge.*, di.nomeDisciplina, al.nomeAluno AS criador, 
                l.salalugar, l.prediolugar, h.datahorario, h.horainicio 
            FROM grupoEstudo ge
            JOIN disciplina di ON di.idDisciplina = ge.idDisciplina
            JOIN aluno al ON al.codMatricula = ge.aluno_idCriadorGrupo
            JOIN agenda ag ON ag.idgrupoestudo = ge.idgrupoestudo
            JOIN lugar l ON l.idlugar = ag.idlugar
            JOIN horario h ON h.idhorario = ag.idhorario
            WHERE ge.idgrupoestudo = :idgrupoestudo";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idgrupoestudo', $idgrupoestudo, PDO::PARAM_INT);
    $stmt->execute();
    $grupo = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$grupo) {
    die("Grupo não encontrado.");
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Grupo - sogeUFES</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <link href="header.css" rel="stylesheet">
    <link href="editarGrupo.css" rel="stylesheet">
</head>
<body>
<header class="header">
        <div>
            <div class="titulo">
                <h1>sogeUFES</h1>
                <a href="http://localhost/sogeufes/"><img src="photos/5402751 2.png" alt="livro" style ="width:2.5vw"></a>
            </div>
            <h3>Sistema de Organização <br>de Grupo de Estudos da UFES</h3>
        </div>
        
        <?php 
            
          
            if(isset($_GET['logout'])){
                encerrarSessao();
            }
        ?>

        <div class ="nome">
            <h5> <?php echo $_SESSION['nomeAluno']?></h5>
            <a href="https://localhost/sogeufes/login.html"><img src="photos\account_circle.png" alt="icone2"></a>
            <a href="?logout=1"><img src="photos\logout.png" alt="logout"></a>
        </div>

    </header>

    
   <h1> <?php echo  $grupo['nomedisciplina']; ?></h1>

<div class="container_sala">
    <div class="container_esquerda">  
        <input type="text" value="<?php echo $grupo['criador']; ?>"disabled>
        <input type="text" value="<?php echo $grupo['horainicio']; ?>"disabled>
        <input type="text" value="<?php echo $grupo['datahorario']; ?>"disabled>
        <input type="text" value="<?php echo $grupo['prediolugar'] . ', ' . $grupo['salalugar']; ?>" disabled>

    </div>
    <div class="container_direita">
   <textarea disabled style="height: 29.1vh;"><?php echo htmlspecialchars($grupo['descricao']); ?></textarea>

    <input type="text" value="<?php echo $grupo['qtdvagas']; ?>" disabled>
    </div>
</div>
</div>
<div class="botoes">
    <a href="editarGrupo.php?id=<?php echo $grupo['idgrupoestudo']; ?>" class="sala">
        <button type="button" class="botao" style="margin-left: 7.949790794979079vw;">Editar Grupo</button>
    </a>
    <div class ="vermelho">
        <button type="button" class="botao">Excluir Grupo</button>
    </div>
</div>
    </div>      
</body>
</html>