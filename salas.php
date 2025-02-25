

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sogeUFES</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href="salas.css" rel="stylesheet">
    <link href="header.css" rel="stylesheet">

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
        <div>
            <a href="http://localhost/sogeufes/login.html"><img src="photos\account_circle.png" alt="icone"></a>
        </div>
    </header>

    <div class="container">
        <div class="branco">
            <h2>Grupos com vagas disponíveis</h2>
            <p>Escolha um grupo de estudo</p>
            <div class="salas">  
            <?php 
            
                require_once 'conectaBD.php';
               
                $sql =  "SELECT TO_CHAR(ho.dataHorario, 'DD/MM/YYYY') AS dataHorario, ge.idgrupoestudo, di.nomedisciplina,ho.horaInicio,lu.nomeLugar,lu.descricaoLugar,ge.qtdvagas, (ho.horaInicio + INTERVAL '2 HOURS') AS horaTermino FROM grupoEstudo AS ge 
		                 JOIN horario AS ho ON  ho.idhorario = ge.idhorario
		                 JOIN lugar AS lu ON  lu.idlugar = ge.idlugar
	                     JOIN disciplina AS di ON  di.iddisciplina = ge.iddisciplina
	                 WHERE qtdvagas >= (
                         SELECT COUNT(pa.codmatricula)
		  	                FROM participacao AS pa JOIN grupoEstudo AS ge  ON ge.idGrupoEstudo = pa.idGrupoEstudo
			                  WHERE status = 'confirmado')";
                 
                 $query = $pdo->prepare($sql); 
                 $query->execute();
                  $grupos = $query->fetchAll(PDO::FETCH_ASSOC);
                  
                  
                  foreach ($grupos as $grupo):
                   
                   
                    echo '<div action =  class="sala" id ='.$grupo['idgrupoestudo'].'>
                            <h3>' . $grupo['nomedisciplina'] . '</h3>
                            <p>' . $grupo['descricaolugar'] . ', ' . $grupo['nomelugar'] . '</p>
                            <p>' . $grupo['qtdvagas'] . ' vagas totais</p>
                            <p>' . $grupo['horainicio'] . ' - ' . $grupo['horatermino'] . '</p>
                            <p>'.$grupo['datahorario'].'</p>
                    </div>';
                 
               endforeach

            ?> 
      
            </div>
        </div>
    </div>    
</body>
</html>