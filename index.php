

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="memory.css" rel = "stylesheet">
    <title>Document</title>
</head>

<body>
    <?php include 'header.php' ?>

    <main>

        <div id="tables">

        <?php

            include './Classes/Rankings.php';

            $ranking_novice = new Ranking('Novice');

            $ranking_novice->display_ranking();


            $ranking_intermediaire = new Ranking('Intermediaire');

            $ranking_intermediaire->display_ranking();


            $ranking_expert = new Ranking('Expert');

            $ranking_expert->display_ranking();

        ?>

        </div>
        
    </main>

    <?php include 'footer.php' ?>

</body>
</html>