<?php 

    session_id() == '' ? session_start() : null;

    if(isset($_GET['deco']) && $_GET['deco'] == 'deco'){
        
        session_destroy();
        header('Location: index.php');
        
    }

?>

<header>

    <nav>
        <ul>

            <li><a href="index.php">Accueil</a></li>
            
        </ul>
    </nav>

    <nav>
        <ul>
            <?php if(isset($_SESSION['user']) && $_SESSION['user'] == 'admin'): ?>
              
                <!-- <li><a href="admin.php">Admin</a></li> -->

                <li><a href="profil.php">Profil</a></li>

                <li>

                    <form method="get" id="deco_form">

                        <button type="submit" name="deco" value="deco">Déconnexion</button>

                    </form>

                <li>

            <?php elseif(isset($_SESSION['user'])): ?>

                <li><a href="profil.php">Profil</a></li>

                <li>

                    <form method="get" id="deco_form">

                        <button type="submit" name="deco" value="deco">Déconnexion</button>

                    </form>
                    
                </li>
                        
            <?php else: ?>

                <li><a href="connexion.php">Connexion</a></li>

                <li><a href="inscription.php">Inscription</a></li>

            <?php endif ?>
            
        </ul>
    </nav>
</header>
