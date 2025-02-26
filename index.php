
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sogeUFES</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href ="homepage.css" rel="stylesheet" >
    <link href ="header.css" rel="stylesheet" >

</head>
<body>
    <header class = "header">
        <div>
            <div class ="titulo">
                <h1>sogeUFES</h1> 
                <img src="photos\5402751 2.png" alt="livro" style ="width:2.5vw">
            </div>
            <h3>Sistema de Organização <br>de Grupo de Estudos da UFES </h3>
        </div>
        <div>
            <a href="https://localhost/sogeufes/login.html"><img src="photos\account_circle.png" alt="icone"></a>
        </div>
        
    </header> 
    <div class ="container">
        <?php
            session_start();
            if (!isset($_SESSION['codMatricula'])) {
                header("?msgErro=Faça login primeiro.");
              exit();
            }else{
                $nomeAluno = $_SESSION['nomeAluno'] ?? $_GET['nome'] ?? 'Usuário';
                $emailAluno = $_SESSION['emailAluno'] ?? $_GET['email'] ?? 'E-mail não disponível';

            }
            
          
        ?>

        <div class="branco">
            <div class="imagem"></div>
            <div class="conteudo">
                <h1>Como funciona o sogeUFES</h1>
                <h2>Simplicidade e eficiência</h2>
                <p>
                O aplicativo de Grupos de Estudo da UFES foi criado para facilitar a formação de grupos acadêmicos, promovendo colaboração e aprendizado entre os estudantes. Com ele, é possível criar salas de estudo para disciplinas ou projetos, escolhendo entre as salas do prédio que já foram dedicadas exclusivamente a este projeto. Além disso, os estudantes podem encontrar facilmente salas de estudo já criadas por outros, organizadas por curso, disciplina ou interesse. Cada sala inclui informações importantes, como horário, disciplina, número de participantes e recursos disponíveis, proporcionando uma organização simples e eficiente. O aplicativo garante que os estudantes sempre tenham um espaço apropriado para estudar, colaborar e trocar conhecimentos, otimizando o uso das salas já alocadas para esse fim.
                </p>
                
                <div class="botoes">
                <a href="https://localhost/sogeufes/salas.php?mat=<?php echo urlencode($_SESSION['codMatricula']); ?>">
                        <button>Procurar grupos</button>
                    </a>
                    <button>Baixar aplicativo mobile</button>
                    <a href="https://localhost/sogeufes/criarGrupo.html?mat=<?php echo urlencode($_SESSION['codMatricula']); ?>">
                        <button>Criar grupo</button>
                    </a>
                    
                </div>
            </div>
            </div>
        </div>
    </div>    
</body>
        
</html>