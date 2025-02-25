<?php
// Configuração do banco de dados
$host = 'localhost';
$banco = 'trabalho';
$usuario = 'postgres';
$senha = '121007';

try {
    $pdo = new PDO("pgsql:host=$host;port=5432;dbname=$banco", $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Função para buscar os dados do grupo
function obterGrupoEstudo() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM grupo_estudo LIMIT 1"); // Exemplo, ajuste conforme necessário
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Criar grupo
if (isset($_POST['criar'])) {
    $sql = "INSERT INTO grupo_estudo (disciplina, criador, horaInicio, horaFim, nomeLocal, descricaoLocal, diaSemana, qtdVagas) 
            VALUES ('Matemática', 'Usuário', '14:00', '16:00', 'Biblioteca', 'Sala de estudo', 'Segunda-feira', 10)";
    $pdo->exec($sql);
    echo "Grupo criado com sucesso!";
}

// Editar grupo
if (isset($_POST['editar'])) {
    $sql = "UPDATE grupo_estudo SET qtdVagas = 8 WHERE id = 1"; // Ajuste conforme necessidade
    $pdo->exec($sql);
    echo "Grupo atualizado!";
}

// Excluir grupo
if (isset($_POST['excluir'])) {
    $sql = "DELETE FROM grupo_estudo WHERE id = 1"; // Ajuste conforme necessidade
    $pdo->exec($sql);
    echo "Grupo excluído!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criação de Sala - sogeUFES</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    
    <link href="header.css" rel="stylesheet">
    <link href="criarsala.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="titulo">
            <h1>sogeUFES</h1>
            <img src="photos/5402751 2.png" alt="livro" style="width:2.5vw">
        </div>
        <h3>Sistema de Organização <br>de Grupo de Estudos da UFES</h3>
    </header>

    
    <h2 class="nomedisciplina">{disciplina.nome}</h2>
    
    <div class="container">  
            <div class="formulario">
                <div class="info-container">  <!-- Quadrado ao redor -->
                    <div class="formulario-esquerda">
                        <div class="info1">Criador do Grupo: <a>{?}</a></div>
                        <div class="info2">Horário: <a>{horario.horaInicio} - {horario.horaFim}</a></div>
                        <div class="info3">Local: <a>{lugar.nomeLocal}</a></div>
                        <div class="info4">Data: <a>{horario.diaSemana}</a></div>
                    </div>
                    <div class="formulario-direita">
                        <div class="info5">Descrição: <a>{lugar.descricaoLocal}</a></div>
                        <div class="info6">Número de Vagas: <a>{grupoEstudo.qtdvagas}</a></div>
                    </div>
                </div>
            </div>
                <div class="botoes">
                    <button type="submit" class="botao">Editar Grupo</button>
                    <button type="button" class="botao">Criar Grupo</button>
                    <button type="button" class="botao">Excluir Grupo</button>
                </div>
            </div>
    
    </div>      
</body>
</html>
