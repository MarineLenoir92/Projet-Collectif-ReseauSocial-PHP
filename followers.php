<!-- Contrôle de l'accès à la page; si utilisateur n'est pas connecté à son compte = refus accès à la page "Flux" -->
<?php
session_start();
if (!isset($_SESSION['connected_id'])){
    //"header(location: )" permet d'éditer une requête HTTP en brut et de renvoyer vers une autre page en cas de non connexion//
    header("location: error.php");
    //"exit" permet de s'assurer que la suite du code ne sera pas éxecutée si condition définie auparavant est remplie//
    exit;
}
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mes abonnés </title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php include("header.php"); ?>
        <div id="wrapper">          
            <aside>
                <img src = "user.jpg" alt = "Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez la liste des personnes qui
                        suivent les messages de l'utilisatrice
                        n° <?php echo intval($_GET['user_id']) ?></p>

                </section>
            </aside>
            <main class='contacts'>
                <?php
                // Etape 1: récupérer l'id de l'utilisateur
                $userId = intval($_GET['user_id']);
                // Etape 2: se connecter à la base de donnée
                $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "
                    SELECT users.*
                    FROM followers
                    LEFT JOIN users ON users.id=followers.following_user_id
                    WHERE followers.followed_user_id='$userId'
                    GROUP BY users.id
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Etape 4: à vous de jouer
                //@todo: faire la boucle while de parcours des abonnés et mettre les bonnes valeurs ci dessous 
                while ($post = $lesInformations->fetch_assoc())
                {
                ?>
                <article>
                    <img src="userbis.jpg" alt="Portrait de l'utilisatrice"/>
                    <a href="wall.php?user_id=<?php echo $post['id']?>"><h3><?php echo $post['alias'] ?></h3></a>
                    <p>id: <?php echo $post['id'] ?></p>
                </article>
                <?php
            }
            ?>
            </main>
        </div>
    </body>
</html>
