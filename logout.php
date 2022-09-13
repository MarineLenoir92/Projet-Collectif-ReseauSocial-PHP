<?php
session_start();
session_destroy();
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Flux</title>         
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include("header.php"); ?>
    <div id="wrapper">
        <main>
            <article>
                <h2>Vous avez été déconncté</h2>
            </article>
        </main>
    </div>
    </body>
</html>
