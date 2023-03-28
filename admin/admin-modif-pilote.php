<?php
    // VERIFICATION 
    session_start();

    if(!isset($_SESSION['ID'])){
        header("Location: //./connexion.php"); // redirige l'utilisateur
    }
    $server = 'localhost';
	$username = 'root';
	$password = 'Stylo89$';
	$database = 'projet';
    try{
        $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
        //On définit le mode d'erreur de PDO sur Exception	
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sqlREQ = "SELECT * FROM `authentication` WHERE id_login = '" . $_SESSION['ID'] . "';";
		$recipesStatement = $conn->prepare($sqlREQ);
		$recipesStatement->execute();
        $recipes = $recipesStatement->fetchAll();
		foreach ($recipes as $recipe) {
			$idadmin = $recipe['admin'];
		}
        if($idadmin!=1){
            header("Location: //./connexion.php"); // redirige l'utilisateur
            $conn=null;
        }
        $conn=null;
    }
    catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Page modifier un pilote." />
    <!-- CSS -->
	<link rel="stylesheet" href="/assets/ind.css">
    <link rel="stylesheet" href="/assets/admin-style.css">
    <link rel="stylesheet" href="/assets/addstud.css">
    <link rel="stylesheet" href="/assets/footer.css">
    <!-- FONT FAMILY LINK -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish&display=swap">
    <!-- ICON -->
    <link rel="shortcut icon" href="/assets/img/logo.ico" alt="IconImg"/>
	<title>Admin - Modifier un pilote</title>
</head>
<body>
<header>
        <!-- HAUT DE PAGE (NAV + LOGO) -->
        <nav class="navbar">
            <a href="./admin.php"><img src="/assets/img/logo.png" alt="LogoPic" class="logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="./admin.php">Accueil</a></li>
                    <li><a href="./admin-companie.html">Entreprise</a></li>
                    <li><a href="#">Stage</a></li>
                    <li><a class="main" href="./admin-pilote.php">Pilote</a></li>
                    <li><a href="./admin-student2.php">Etudiant</a></li>
                    <li><a href="#">Candidature</a></li>
                </ul>
            </div>
            <img src="/assets/img/Hamburger_icon.svg.png" alt="menu burger"
            class="menu-burger">
        </nav>
    </header>   
    <!-- SEPARATION -->
    <div class="green-rect"></div>

    <!-- INFO DE L'ADMIN -->
    <section class="aff-login-admin">
        <?php
            echo "<img class='admin-pic' src='/users/" . $_SESSION['ID'] . "/admin-user-icon.jpg' alt='pic3'>";
            $server = 'localhost';
			$username = 'root';
			$password = 'Stylo89$';
			$database = 'projet';
            try{
                $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                //On définit le mode d'erreur de PDO sur Exception	
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sqlREQ = "SELECT * FROM `authentication` WHERE id_login = '" . $_SESSION['ID'] . "';";
				$recipesStatement = $conn->prepare($sqlREQ);
				$recipesStatement->execute();
                $recipes = $recipesStatement->fetchAll();
				foreach ($recipes as $recipe) {
					echo "<p>" . $recipe['login'] . "</p>" ;
				}
                $conn=null;
            }
            catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
        ?>
        <a href="./deconnexion.php"><img name="deco" class="deco" src="/assets/img/deconn.png" alt="deco"></a>
    </section>

    <!-- SEPARATION -->
    <div class="green-rect2"></div>
    <section class="mlp">
        <div class="r">
        <form method='POST'>
            <h2>Modifier un pilote.</h2>
            <label>Nom : </label><input type='text' name='last_pilote'  ><br>
            <label>Prénom : </label><input type='text' name='firstname_pilote'  ><br>
            <label>Centre : </label><input type='text' name='centre'><br>
            <label>Email : </label><input type='text' name='login'  ><br>
            <label>Mot de passe : </label><input type='text' name='passw'  ><br>
            <label>Ajouter une promotion : </label><input type='text' name='promo-add'><br>
            <label>Supprimer une promotion : </label><input type='text' name='promo-supp'  ><br>
            <?php
                $server = 'localhost';
                $username = 'root';
                $password = 'Stylo89$';
                $database = 'projet';
                try{
                    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                    //On définit le mode d'erreur de PDO sur Exception	
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    if(isset($_POST['submit'])){
                        if(strlen($_POST['last_pilote'])>0){
                            $sqlREQ = "UPDATE pilote SET lastname_pilote = '" . $_POST['last_pilote'] . "'
                            WHERE id_login = '" . $_GET['id'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                        }
                        if(strlen($_POST['firstname_pilote'])>0){
                            $sqlREQ = "UPDATE pilote SET firstname_pilote = '" . $_POST['firstname_pilote'] . "'
                            WHERE id_login = '" . $_GET['id'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                        }
                        if(strlen($_POST['login'])>0){
                            $sqlREQ = "SELECT * FROM authentication WHERE login = '" . $_POST['login'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                            if($recipesStatement->rowCount() != 0){
                                echo "<p class='textError'>Email déjà utilisé.</p>";
                            }
                            else {
                                $sqlREQ = "UPDATE authentication SET login = '" . $_POST['login'] . "'
                                WHERE id_login = '" . $_GET['id'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
                                $recipesStatement->execute();
                            }
                        }
                        if(strlen($_POST['passw'])>0){
                            if(strlen($_POST['passw'])>6){
                                $sqlREQ = "UPDATE authentication SET passw = '" . $_POST['passw'] . "'
                                WHERE id_login = '" . $_GET['id'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
                                $recipesStatement->execute();
                            }
                            else{
                                echo "<p class='textError'>Mot de passe trop court (6 mini).</p>";
                            }
                        }
                        if(strlen($_POST['centre'])>0){
                            $sqlREQ = "SELECT * FROM centre WHERE name_centre = '" . $_POST['centre'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                            if($recipesStatement->rowCount() != 1){
                                echo "<p class='textError'>Vérifier le centre.</p>";
                            }
                            else{
                                $recipes = $recipesStatement->fetchAll();
								foreach ($recipes as $recipe) {
									$var_idcentre = $recipe['id_centre'];
								}	
                                $sqlREQ = "UPDATE pilote SET id_centre = '" . $var_idcentre . "'
                                WHERE id_login = '" . $_GET['id'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
							    $recipesStatement->execute();
                            }
                        }
                        // SUPP PROMOTION
                        if(strlen($_POST['promo-supp'])>0){
                            $sqlREQ = "SELECT * FROM promotion WHERE name_promo = '" . $_POST['promo-supp'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                            if($recipesStatement->rowCount() != 1){
                                echo "<p class='textError'>Vérifier la promo.</p>";
                            }
                            else{
                                $recipes = $recipesStatement->fetchAll();
								foreach ($recipes as $recipe) {
									$var_idpromo = $recipe['id_promo'];
								}
                                $sqlREQ = "SELECT id_pilote, id_login, firstname_pilote, lastname_pilote, login, name_centre FROM (SELECT id_pilote, pilote.id_login, firstname_pilote, lastname_pilote, id_centre, login FROM `pilote`
                                INNER JOIN authentication ON authentication.id_login = pilote.id_login) as h
                                INNER JOIN centre ON centre.id_centre = h.id_centre
                                WHERE id_login = '" . $_GET['id'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
                                $recipesStatement->execute();
                                $recipes = $recipesStatement->fetchAll();
                                foreach ($recipes as $recipe) {
                                    $var_idpilote = $recipe['id_pilote'];
                                }
                                $sqlREQ = "SELECT * FROM manage 
                                WHERE id_promo = '" . $var_idpromo . "'
                                AND id_pilote = '" . $var_idpilote . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
							    $recipesStatement->execute();
                                if($recipesStatement->rowCount() != 0){
                                    $sqlREQ = "DELETE FROM manage 
                                    WHERE id_pilote = '" . $var_idpilote . "' AND id_promo = '" . $var_idpromo . "';";
                                    $recipesStatement = $conn->prepare($sqlREQ);
							        $recipesStatement->execute();
                                }
                                else{
                                    echo "<p class='textError'>Promotion jamais ajoutée.</p>";
                                }
                            }
                        }
                        // ADD PROMOTION
                        if(strlen($_POST['promo-add'])>0){
                            $sqlREQ = "SELECT * FROM promotion WHERE name_promo = '" . $_POST['promo-add'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                            if($recipesStatement->rowCount() != 1){
                                echo "<p class='textError'>Vérifier la promo.</p>";
                            }
                            else{
                                $recipes = $recipesStatement->fetchAll();
								foreach ($recipes as $recipe) {
									$var_idpromo = $recipe['id_promo'];
								}
                                $sqlREQ = "SELECT id_pilote, id_login, firstname_pilote, lastname_pilote, login, name_centre FROM (SELECT id_pilote, pilote.id_login, firstname_pilote, lastname_pilote, id_centre, login FROM `pilote`
                                INNER JOIN authentication ON authentication.id_login = pilote.id_login) as h
                                INNER JOIN centre ON centre.id_centre = h.id_centre
                                WHERE id_login = '" . $_GET['id'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
                                $recipesStatement->execute();
                                $recipes = $recipesStatement->fetchAll();
                                foreach ($recipes as $recipe) {
                                    $var_idpilote = $recipe['id_pilote'];
                                }
                                $sqlREQ = "SELECT * FROM manage 
                                WHERE id_promo = '" . $var_idpromo . "'
                                AND id_pilote = '" . $var_idpilote . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
							    $recipesStatement->execute();
                                if($recipesStatement->rowCount() != 1){
                                    $sqlREQ = "INSERT INTO manage (id_pilote, id_promo)
                                    VALUES ('" . $var_idpilote . "','" . $var_idpromo . "');";
                                    $recipesStatement = $conn->prepare($sqlREQ);
							        $recipesStatement->execute();
                                }
                                else{
                                    echo "<p class='textError'>Promotion déjà ajoutée.</p>";
                                }
                            }
                        }

                    }
                    $conn=null;
                }
                catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
            ?>
            <input class="btn-modifier" type='submit' name='submit' value='Modifier'>
        </form>
        </div>
        <div class="l">
            <div class="mise">
            <?php
                $server = 'localhost';
                $username = 'root';
                $password = 'Stylo89$';
                $database = 'projet';
                try{
                    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                    //On définit le mode d'erreur de PDO sur Exception	
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sqlREQ = "SELECT id_pilote, id_login, firstname_pilote, lastname_pilote, login, name_centre FROM (SELECT id_pilote, pilote.id_login, firstname_pilote, lastname_pilote, id_centre, login FROM `pilote`
                    INNER JOIN authentication ON authentication.id_login = pilote.id_login) as h
                    INNER JOIN centre ON centre.id_centre = h.id_centre
                    WHERE id_login = '" . $_GET['id'] . "';";
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $recipes = $recipesStatement->fetchAll();
                    foreach ($recipes as $recipe) {
                        echo "<img src='/assets/img/cv.png' alt='imgSuppStudDefault'>";
                        echo "<h2>Mail : " . $recipe['login'] . "</h2>" ;
                        echo "<p>ID du login : " . $recipe['id_login'] . "</p>";
                        echo "<p>Nom : " . $recipe['lastname_pilote'] . ", Prénom : " . $recipe['firstname_pilote'] . "</p>";
                        echo "<p>Centre : " . $recipe['name_centre'] . "</p>";
                        $var_idpilote = $recipe['id_pilote'];
                    }
                    $sqlREQ="SELECT id_pilote, name_promo FROM `manage` 
                    INNER JOIN promotion ON manage.id_promo = promotion.id_promo
                    WHERE id_pilote = '" . $var_idpilote . "';";
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $recipes = $recipesStatement->fetchAll();
                    $var_promo ="Promotion(s) :";
                    foreach ($recipes as $recipe) {
                        $var_promo .= " - " . $recipe['name_promo'];
                    }
                    echo "<p>" . $var_promo . "</p>";
                    $conn=null;
                }
                catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
            ?>
            </div>
        </div>
    </section>
    <footer>
            <li>
                <a href="#" class="hyperlinkFooter">
                    Mentions légales
                </a>
            </li>
            <label class="barreFooter">
                |
            </label>
            <li>
                <a href="#" class="hyperlinkFooter">
                    Politique de confidentialité
                </a>
            </li>
            <label class="barreFooter">
                |
            </label>
            <li>
                <a href="#" class="hyperlinkFooter">
                    © Copyright 2023
                </a>
            </li>
        </footer>
    <script>
        const menuHamburger = document.querySelector(".menu-burger")
        const navLinks = document.querySelector(".nav-links")
        menuHamburger.addEventListener('click',()=>{
        navLinks.classList.toggle('mobile-menu')
        })
    </script>
</body>
</html>