<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
    <?php include("header.php"); ?>
        <div id="wrapper" >

            <aside>
                <h2>Présentation</h2>
                <p>Bienvenu sur notre réseau social.</p>
            </aside>
            <main>
                <article>
                    <h2>Connexion</h2>
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
                        //si le mot de passe enregistré dans la table "users" diffère du mot de passe saisi = connexion échoué//
                        if ( ! $user OR $user["password"] != $passwdAVerifier)
                        {
                            echo "La connexion a échouée. ";
                            
                        } else
                        {
                            //on affiche l'alias donné lors de l'inscription et stocké dans la table "users" dans la colonne "alias"
                            echo "Votre connexion est un succès : " . $user['alias'] . ".";
                            // Etape 7 : Se souvenir que l'utilisateur s'est connecté pour la suite
                            // documentation: https://www.php.net/manual/fr/session.examples.basic.php
                            // l'id du user connecté est stocké dans "$SESSION['connected_id']
                            $_SESSION['connected_id']=$user['id'];
                        }
                    }
                    ?>                     
                    <form action="login.php" method="post">
                        <input type='hidden'name='login' value='login'>
                        <dl>
                            <dt><label for='email'>E-Mail</label></dt>
                            <dd><input type='email'name='email'></dd>
                            <dt><label for='motpasse'>Mot de passe</label></dt>
                            <dd><input type='password'name='motpasse'></dd>
                        </dl>
                        <input type='submit'>
                    </form>
                    <p>
                        Pas de compte?
                        <a href='registration.php'>Inscrivez-vous.</a>
                    </p>

                </article>
            </main>
        </div>
    </body>
</html>
