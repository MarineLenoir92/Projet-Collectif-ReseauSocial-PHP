<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Actualités</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
        <link href="https://fonts.googleapis.com/css2?family=Poiret+One&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300&display=swap" rel="stylesheet">
    </head>
    <body>
    <?php include("header.php"); ?>
        <div id="wrapper">
            <nav>
                <img src="userbis.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages de
                        tous les utilisatrices du site.</p>
                </section>
            </nav>
            <main>
                
                <?php
                // Etape 1: Ouvrir une connexion avec la base de donnée.
                $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                //verification
                if ($mysqli->connect_errno)
                {
                    echo "<article>";
                    echo("Échec de la connexion : " . $mysqli->connect_error);
                    echo("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                    echo "</article>";
                    exit();
                }

                // Etape 2: Poser une question à la base de donnée et récupérer ses informations
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.user_id,
                    posts.photo,
                    users.alias as author_name, 
                    users.photo as profile,
                    count(likes.post_id) as like_number,
                  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                  
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 5
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Vérification
                if ( ! $lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }

                // Etape 3: Parcourir ces données et les ranger bien comme il faut dans du html
                // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
                while ($post = $lesInformations->fetch_assoc())
                {
                    ?>
                    <article>
                        <div id="author">
                            <img src=<?php echo $post['profile'] ?> id="profile" alt="Photo Profile Auteur"/>
                            <a href="wall.php?user_id=<?php echo $post['user_id']?>"><address><?php echo $post['author_name'] ?></address></a>
                        </div>
                        <h3>
                            <time><?php echo $post['created'] ?></time> 
                        </h3>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                            <img src=<?php echo $post['photo']?> id="photopost" alt="Photo illustrant Post" />
                        </div>
                        <footer>
                            <!-- <small>♥<?php echo $post['like_number'] ?></small> -->
                            <form action="news.php" method="POST">
                                <input type="submit" name="like_number" value="♥" id="like"/><?php echo $post['like_number'] ?>
                            </form>
                            <a href="" id="taglist">#<?php echo $post['taglist'] ?></a>
                        </footer>
                    </article>
                    <?php
                    // avec le <?php ci-dessus on retourne en mode php 
                }// cette accolade ferme et termine la boucle while ouverte avant.
                ?>
            </main>
            <aside>
                <img src="userbis.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages de
                        tous les utilisatrices du site.</p>
                </section>
            </aside>
        </div>
    </body>
</html>
