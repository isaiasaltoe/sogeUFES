<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sogeUFES - Criar grupo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href="criarGrupo.css" rel="stylesheet">
    <link href="header.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div>
            <div class="titulo">
                <h1>sogeUFES</h1>
                <a href="http://localhost/sogeufes/">
                    <img src="photos/5402751 2.png" alt="livro" style="width:2.5vw">
                </a>
            </div>
            <h3>Sistema de Organização <br>de Grupo de Estudos da UFES</h3>
        </div>
        <?php
            session_start();

            require_once 'Sessao.php';

            if(isset($_GET['logout'])){
                encerrarSessao();
            }
        ?>

        <div class ="nome">
            <h5> <?php echo $_SESSION['nomeAluno']?></h5>
            <a href="https://localhost/sogeufes/meusGrupos.php"><img src="photos\account_circle.png" alt="icone2"></a>
            <a href="?logout=1"><img src="photos\logout.png" alt="logout"></a>
        </div>
    </header>

    <div class="container_criarGrupo">
        <div class="conteudo"> 
            <div class="texto">
                <h2>Criar grupo</h2>   
            </div>
            <form action="DadosGrupo.php" method="post" class="formulario">
                <div class="livroaberto">
                    <input type="text" id="idDisciplina" name="disciplina" placeholder="Disciplina"  required>
                </div>
                <div class="Agenda">
                    <div class="relogio">
                        <input type="time" id="idHorario" name="horario" placeholder="Horário" required style="color:#757575">
                    </div>
                    <div class="data">
                        <input type="date" id="Dia" name="dia" placeholder="Data"     style="color:#757575">
                    </div>
                </div>
              
                <div class="local">
                    <input type="text" id="Lugar" name="predio" placeholder="Prédio" required >
                </div>
                <div class = "sala">
                     <input type ="text" id="Sala" name="sala" placeholder="Sala" required>
                </div>
                
                <div class="descricao">
                    <input type="text" id="Descricao" name="descricao" placeholder="Descrição" required>
                </div>

                <button type="submit">
                    <p>Criar grupo</p>
                </button>
            </form>
        </div>
    </div>
</body>
</html>



