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
        $sqlREQ = "SELECT * FROM `pilote` WHERE id_login = '" . $_SESSION['ID'] . "';";
		$recipesStatement = $conn->prepare($sqlREQ);
		$recipesStatement->execute();
        $recipes = $recipesStatement->fetchAll();
		if($recipesStatement->rowCount() != 1){
            $conn=null;
            header("Location: ./connexion.php"); // redirige l'utilisateur
        }
        $conn=null;
    }
    catch(PDOException $e){
        header("Location: ./connexion.php"); // redirige l'utilisateur;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Page pilote pour supprimer un stage." />
    <!-- CSS -->
	<link rel="stylesheet" href="/assets/ind.css">
    <link rel="stylesheet" href="/assets/admin-style.css">
    <link rel="stylesheet" href="/assets/addstud.css">
    <link rel="stylesheet" href="/assets/footer.css">
    <!-- FONT FAMILY LINK -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish&display=swap">
    <!-- ICON -->
    <link rel="shortcut icon" href="/assets/img/logo.ico" alt="IconImg"/>
	<title>Pilote - Supprimer stage</title>
</head>
<body>
<header>
        <!-- HAUT DE PAGE (NAV + LOGO) -->
        <nav class="navbar">
            <a href="./pilote.php"><img src="/assets/img/logo.png" alt="LogoPic" class="logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="./pilote.php">Accueil</a></li>
                    <li><a href="">Entreprise</a></li>
                    <li><a class="main"href="./pilote-intership.php">Stage</a></li>
                    <li><a href="./pilote-student.php">Etudiant</a></li>
                </ul>
            </div>
            <img src="/assets/img/Hamburger_icon.svg.png" alt="menu burger"
            class="menu-burger">
        </nav>
    </header>   
    <!-- SEPARATION -->
    <div class="green-rect"></div>

    <!-- INFO DU TUTEUR -->
    <section class="aff-login-admin">
        <?php
            echo "<img class='admin-pic' src='/users/" . $_SESSION['ID'] . "/admin-user-icon.jpg' alt='profil.pic'>";
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
    <div class="h">
        <h1 class="textError">Vous êtes sur le point de supprimer un stage.</h1>
    </div>
   <div class="contener">
    <?php
                $server = 'localhost';
                $username = 'root';
                $password = 'Stylo89$';
                $database = 'projet';
                try{
                    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                    //On définit le mode d'erreur de PDO sur Exception	
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sqlREQ = "SELECT * FROM (SELECT id_intership, g,d,intership_term, date_offer, num_place, subject_intership, name_comp, email_comp, activity_area FROM (SELECT internship.id_intership, g, d, intership_term, date_offer, num_place, subject_intership, id_comp FROM (SELECT id_intership, GROUP_CONCAT( `city_name` SEPARATOR ', ' ) as g, GROUP_CONCAT( `city_postal` SEPARATOR ', ' ) as d 
                    FROM `is_located`
                    INNER JOIN locality ON locality.id_locality = is_located.id_locality
                    GROUP BY id_intership) as c
                    INNER JOIN internship ON internship.id_intership = c.id_intership) as w
                    INNER JOIN companie ON companie.id_comp = w.id_comp) as n
                    INNER JOIN (SELECT id_intership,  GROUP_CONCAT( `name_promo` SEPARATOR ', ' ) as h FROM target
                    INNER JOIN promotion 
                    ON promotion.id_promo = target.id_promo
                    GROUP BY id_intership) as q 
                    ON q.id_intership = n.id_intership
                    WHERE q.id_intership = '" . $_GET['idinter'] . "';" ;
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $recipes = $recipesStatement->fetchAll();
                    foreach ($recipes as $recipe) {
                        echo "<div class='con'>";
                        echo "<h2>" . $recipe['name_comp'] . "</h2>";
                        echo "<p>Domaine : " . $recipe['activity_area'] . "</p>";
                        echo "<p>Sujet du stage : " . $recipe['subject_intership'] . "</p>";
                        echo "<p>E-mail : " . $recipe['email_comp'] . "</p>";
                        echo "<p>Durée du stage : " . $recipe['intership_term'] . "</p>";
                        echo "<p>Lieux : " . $recipe['g'] . "</p>";
                        echo "<p>Code postale : " . $recipe['d'] . "</p>";
                        echo "<p>Promotion(s) ciblée(s) : " . $recipe['h'] . "</p>";
                        echo "</div>";
                    }
                    $conn=null;
                }
                catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
            ?>
   </div>
   <form class='pp' method="POST">
   <?php
            if (!empty($_POST['act'])) {
                $server = 'localhost';
                $username = 'root';
                $password = 'Stylo89$';
                $database = 'projet';
                try{
                    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                    //On définit le mode d'erreur de PDO sur Exception	
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sqlREQ = "DELETE FROM is_located WHERE id_intership = '" . $_POST['idinter'] . "';
                    UPDATE intership SET intership_term = NULL, date_offer = NULL, num_place = NULL, statut = NULL, description = NULL, subject_intership = NULL
                    WHERE id_intership = WHERE id_intership = '" . $_POST['idinter'] . "';";
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $conn=null;
                    echo "<p class='textVal'>Stage supprimé avec succès.</p>";
                }
                catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
            }
        ?>
        
        <input name="act" type="submit" value="Supprimer le stage">
    </form>
                
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