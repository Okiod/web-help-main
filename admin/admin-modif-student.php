<?php
    // VERIFICATION 
    session_start();

    if(!isset($_SESSION['ID'])){
        header("Location: ./connexion.php"); // redirige l'utilisateur
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
            header("Location: ./connexion.php"); // redirige l'utilisateur
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
	<meta name="description" content="Page modifier un élève." />
    <!-- CSS -->
	<link rel="stylesheet" href="/assets/ind.css">
    <link rel="stylesheet" href="/assets/admin-style.css">
    <link rel="stylesheet" href="/assets/addstud.css">
    <link rel="stylesheet" href="/assets/footer.css">
    <!-- FONT FAMILY LINK -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish&display=swap">
    <!-- ICON -->
    <link rel="shortcut icon" href="/assets/img/logo.ico" alt="IconImg"/>
	<title>Admin - Modifier un élève</title>
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
                    <li><a href="./admin-pilote.php">Pilote</a></li>
                    <li><a class="main" href="./admin-student2.php">Etudiant</a></li>
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
            <h2>Modifier un étudiant.</h2>
            <label>Nom : </label><input type='text' name='last_student'  ><br>
            <label>Prénom : </label><input type='text' name='first_student'  ><br>
            <label>Centre : </label><input type='text' name='centre'  ><br>
            <label>Email : </label><input type='text' name='login'  ><br>
            <label>Mot de passe : </label><input type='text' name='passw'  ><br>
            <label>Promotion : </label><input type='text' name='promo'  ><br>
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
                        if(strlen($_POST['last_student'])>0){
                            $sqlREQ = "UPDATE student SET last_student = '" . $_POST['last_student'] . "'
                            WHERE id_login = '" . $_GET['id'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                        }
                        if(strlen($_POST['first_student'])>0){
                            $sqlREQ = "UPDATE student SET first_student = '" . $_POST['first_student'] . "'
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
                                $sqlREQ = "UPDATE student SET id_centre = '" . $var_idcentre . "'
                                WHERE id_login = '" . $_GET['id'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
							    $recipesStatement->execute();
                            }
                        }
                        if(strlen($_POST['promo'])>0){
                            $sqlREQ = "SELECT * FROM promotion WHERE name_promo = '" . $_POST['promo'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                            if($recipesStatement->rowCount() != 1){
                                echo "<p class='textError'>Vérifier le nom de la promotion.</p>";
                            }
                            else{
                                $recipes = $recipesStatement->fetchAll();
								foreach ($recipes as $recipe) {
									$var_idpromo = $recipe['id_promo'];
								}	
                                $sqlREQ = "UPDATE student SET id_promo = '" . $var_idpromo . "'
                                WHERE id_login = '" . $_GET['id'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
							    $recipesStatement->execute();
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
                    $sqlREQ = "SELECT id_login, login,first_student, last_student, name_promo, name_centre FROM (SELECT id_login, login,first_student, last_student, id_promo, name_centre  FROM (SELECT authentication.id_login, login, first_student, last_student, id_centre, id_promo 
                    FROM `authentication`
                    INNER JOIN student ON student.id_login = authentication.id_login) as k
                    INNER JOIN centre ON k.id_centre = centre.id_centre) as m
                    INNER JOIN promotion ON promotion.id_promo = m.id_promo
                    WHERE id_login = " . $_GET['id'] . ";";
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $recipes = $recipesStatement->fetchAll();
                    foreach ($recipes as $recipe) {
                        echo "<img src='/assets/img/cv.png' alt='imgSuppStudDefault'>";
                        echo "<h2>Mail : " . $recipe['login'] . "</h2>" ;
                        echo "<p>ID du login : " . $recipe['id_login'] . "</p>";
                        echo "<p>Nom : " .  $recipe['first_student'] . ", Prénom : " . $recipe['last_student'] . "</p>";
                        echo "<p>Promotion : " . $recipe['name_promo'] . "</p>";
                        echo "<p>Centre : " . $recipe['name_centre'] . "</p>";
                    }
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