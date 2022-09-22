<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - PostTags</title> 
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
                    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                    // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                    $enCoursDeTraitement = isset($_POST['auteur']);
                    if ($enCoursDeTraitement)
                    {
                        
                        // echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        
                        $authorId = $_POST['auteur'];
                        $postContent = $_POST['message'];
                        $tagsId = $_POST['tags'];
                        echo $tagsId;
                        $tagsContent = $_POST['label'];



                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        $tagsId = intval($mysqli->real_escape_string($tagsId));
                        //echo $tagsId;
                        $tagsContent = $mysqli->real_escape_string($tagsContent);


                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO posts (id, user_id, content, created) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW());"
                                ;

                        // $lInstructionSqlTags = " `tags` "
                        //         . "(`id`, `label`) "
                        //         . "VALUES (NULL, "
                        //         . $tagsContent . "');";
                        //         echo $lInstructionSqlTags;

                        

                     
                        // Etape 5 : execution 
                        $okInsertionPost = $mysqli->query($lInstructionSql);
                        
                        


                        if ( ! $okInsertionPost)
                        {
                            echo "Impossible d'ajouter le message: " . $mysqli->error;
                        } else
                        {
                            echo "Message posté en tant que :" . $listAuteurs[$authorId];
                            // print_r($okInsertionPost ->fetch_assoc());
                            $postId = intval($mysqli -> insert_id);
                            echo $postId;
                            
                            $lInstructionSqlPostTags = "INSERT INTO `posts_tags` (`id`, `post_id`, `tag_id`)"
                                . "VALUES (NULL, "
                                . $postId . ", "
                                . $tagsId . ");";
                                echo $lInstructionSqlPostTags;
                            
                            $okInsertionTags = $mysqli->query($lInstructionSqlPostTags);

                        
                    
                        }
                    }
                    ?>                     
                    <form action="postTags.php" method="post">
                        <!-- <input type='hidden' name='???' value='achanger'> -->
                        <dl>
                            <dt><label for='auteur'>Auteur</label></dt>
                            <dd><select name='auteur'>
                                    <?php
                                    foreach ($listAuteurs as $id => $alias)
                                        echo "<option value='$id'>$alias</option>";
                                    ?>
                                </select></dd>
                                <dt><label for='tags'>Tag à choisir</label></dt>
                            <dd><select name='tags'>
                                    <?php
                                    foreach ($listTags as $id => $tagsContent)
                                        echo "<option value='$id'>$tagsContent</option>";
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
