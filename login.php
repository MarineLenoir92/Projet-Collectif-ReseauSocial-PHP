<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poiret+One&display=swap" rel="stylesheet">
    </head>
    <body> 
        <video id="backgroundvideo" autoplay loop muted poster="videologin.mp4">
            <source src="videologin.mp4" type="video/mp4">
        </video>
            
                    <?php
                    /**
                     * TRAITEMENT DU FORMULAIRE
                     */
                    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                    // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                    // "isset" permet de savoir si une variable est déclarée et différente de NULL: si le champs email est rempli
                    $enCoursDeTraitement = isset($_POST['email']);
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.

                        // Etape 2: récupérer ce qu'il y a dans le formulaire :
                        $emailAVerifier = $_POST['email'];
                        $passwdAVerifier = $_POST['password'];


                        //Etape 3 : Ouvrir une connexion avec la base de donnée.
                        //Paramètres de new mysqli(host, username, password, nom de la db)
                        $mysqli = new mysqli("localhost", "root", "root", "socialnetwork");
                        //Etape 4 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $emailAVerifier = $mysqli->real_escape_string($emailAVerifier);
                        $passwdAVerifier = $mysqli->real_escape_string($passwdAVerifier);
                        // on crypte le mot de passe pour éviter d'exposer notre utilisatrice en cas d'intrusion dans nos systèmes
                        $passwdAVerifier = md5($passwdAVerifier);
                        // NB: md5 est pédagogique mais n'est pas recommandée pour une vraies sécurité
                        //Etape 5 : construction de la requete
                        $lInstructionSql = "SELECT * "
                                . "FROM users "
                                . "WHERE "
                                . "email LIKE '" . $emailAVerifier . "'"
                                ;
                        // Etape 6: Vérification de l'utilisateur
                        //"query" permet de donner un ordre d'instruction à la base de données "query($variable contenant instructions SQL )
                        $res = $mysqli->query($lInstructionSql);
                        //"fetch_assoc() permet de créer un tableau associatif des données récupérées en base de données sous la forme d'une paire Clé:Valeur 
                        $user = $res->fetch_assoc();
                        //si l'email ou mot de passe enregistrés dans la table "users" diffère des données saisies = connexion échoué//
                        if ( $user["email"] != $emailAVerifier OR $user["password"] != $passwdAVerifier)
                        {
                            //affichage du pop-up avec message erreur//
                            echo "<div id='popupbox'>
                            <button type='button' onclick='window.history.back()'>
                                <span class='close'>Close</span>
                            </button>
                            <h1 id=popuptitle>Oups !</h1>
                            <p id='popupmessage'>Email ou Mot de Passe erronés</p>
                            <a href='mailto: away@outlook.fr' id='popupmail'>Identifiants oubliés ?</a>
                            </div>";
                        } else
                        {
                            //si login correct, renvoie vers page d'accueil news.php//
                            header("location: news.php");
                            // Etape 7 : Se souvenir que l'utilisateur s'est connecté pour la suite
                            // documentation: https://www.php.net/manual/fr/session.examples.basic.php
                            // l'id du user connecté est stocké dans "$SESSION['connected_id']
                            $_SESSION['connected_id']=$user['id'];
                            
                        }
                    }
                    ?> 
                    </div>  
                <div id="login-box">
                    <h2 class="login-title">Login</h2>                  
                    <form action="login.php" method="post">
                        <input type='hidden'name='login' value='login'>
                            <div class="user-box">
                                <dt><label for='email'>E-Mail</label></dt>
                                <dd><input type='email'name='email'></dd>
                            </div>
                            <div class="user-box">
                                <dt><label for='motpasse'>Mot de passe</label></dt>
                                <dd><input type='password'name='motpasse'></dd>
                            </div>
                        <input type='submit' id="submit" value="Se Connecter">
                    </form>
                    <p id="nocount">
                        Pas de compte?
                        <a href='registration.php' id="register" >Inscrivez-vous</a>
                    </p>
                </article>
            </main>
    </body>
</html>
