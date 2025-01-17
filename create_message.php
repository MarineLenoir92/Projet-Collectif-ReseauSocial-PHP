<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - crreate_message</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
        <link href="https://fonts.googleapis.com/css2?family=Poiret+One&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php include("header.php"); ?>

        <div id="wrapper" >

            <aside>
                <h2>Présentation</h2>
                <p>Sur cette page on peut poster un message en se faisant 
                    passer pour quelqu'un d'autre</p>
            </aside>
            <main>
                <article>
                    <h2>Poster un message</h2>
                    <?php
                    /**
                     * BD
                     */
                    $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                    /**
                     * Récupération de la liste des auteurs
                     */
                    $listAuteurs = [];
                    $laQuestionEnSql = "SELECT * FROM users";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    while ($user = $lesInformations->fetch_assoc())
                    {
                        $listAuteurs[$user['id']] = $user['alias'];
                    }


                    /**
                     * TRAITEMENT DU FORMULAIRE
                     */
                    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                    // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                    $enCoursDeTraitement = isset($_POST['auteur']);
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                        // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                        echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ???
                        $authorId = $_POST['auteur'];
                        $postContent = $_POST['message'];


                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO posts (id, user_id, content, created) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW());"
                                ;
                       // echo $lInstructionSql;
                        // Etape 5 : execution 
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            echo "Message posté en tant que :" . $listAuteurs[$authorId];
                        }
                    }
                    ?>                     
                    <?php 
                    $IdOfLastPost = "";
                    $listTags = [];
                    $tagsInfo = "SELECT * FROM tags";
                    $tagsResponse = $mysqli->query($tagsInfo);
                    while ($tags = $tagsResponse->fetch_assoc())
                    {
                        $listTags[$tags['id']] = $tags['label'];
                    }
                    


                    /**
                     * TRAITEMENT DU FORMULAIRE
                     */
                    // Etape 1 : vérifier 2si on est en train d'afficher ou de traiter le formulaire
                    // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                    // En cas de bug vérifier la ligne suivante
                    $enCoursDeTraitement = isset($_POST['post_tags']); 
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                        // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                        echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ???
                        $tagsId = $_POST['tag'];
                        $tagsContent = $_POST['label'];


                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $tagsId = intval($mysqli->real_escape_string($tagsId));
                        $tagsContent = $mysqli->real_escape_string($tagsContent);
                        //Etape 4 : construction de la requete
                        // Continuer à partir de la ligne suivante
                        $tagsInstructionSql = "INSERT INTO posts_tags (id, post_id, tag_id)"
                                . "VALUES (NULL, "
                                . $IdOfLastPost . ", "
                                . "'" . $tagsId . "',);"
                                ;

                      
                       // echo $lInstructionSql;
                        // Etape 5 : execution 
                        $oki = $mysqli->query($tagsInstructionSql);
                        if ( ! $oki)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            echo "Message posté en tant que :" . $listTags[$tagsId];
                        }
                    }
                    ?>                     
                    <form action="usurpedpost.php" method="post">
                        <input type='hidden' name='???' value='achanger'>
                        <dl>
                            <dt><label for='auteur'>Auteur</label></dt>
                            <dd><select name='auteur'>
                                    <?php
                                    foreach ($listAuteurs as $id => $alias)
                                        echo "<option value='$id'>$alias</option>";
                                    ?>
                                </select></dd>
                            
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <dl>
                            <dt><label for='tags'>Tag à choisir</label></dt>
                            <dd><select name='tags'>
                                    <?php
                                    foreach ($listTags as $id => $label)
                                        echo "<option value='$id'>$label</option>";
                                    ?>
                                </select></dd>
                            
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <input type='submit'>
                    </form>               
                </article>
            </main>
        </div>
    </body>
</html>
