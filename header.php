<!-- Inclure session_start dans un fichier header puis include sur toutes les autrs pages évite les oublis d'ouverture de session -->
<!-- <?php
session_start();
?> -->
<header>   
            <div>
            <img src="logo.jpg" alt="Logo de notre réseau social"/>
            <h2>MapAway</h2>
</div>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <!-- Utiliser la variable superglobale $_SESSION permet de personnaliser la page en fonction de l'utilsateur connecté, URL dédié -->
                <a href="wall.php?user_id=<?php echo $_SESSION['connected_id']?>">Mur</a>
                <a href="feed.php?user_id=<?php echo $_SESSION['connected_id']?>">Flux</a>
                <a href="tags.php?tag_id=1">Mots-clés</a>
                <a href="postTags.php">Publier</a>
                
            </nav>
            <nav id="user">
                <a href="#">Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id']?>">Paramètres</a></li>
                    <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id']?>">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id']?>">Mes abonnements</a></li>
                    <li><a href="logout.php?user_id=<?php echo $_SESSION['connected_id']?>">Déconnexion</a></li>
                </ul>
            </nav>
        </header>