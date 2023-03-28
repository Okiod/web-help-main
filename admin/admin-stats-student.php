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
	<meta name="description" content="Page de statistique d'élève." />
    <!-- CSS -->
	<link rel="stylesheet" href="/assets/ind.css">
    <link rel="stylesheet" href="/assets/admin-style.css">
    <link rel="stylesheet" href="/assets/addstud.css">
    <link rel="stylesheet" href="/assets/footer.css">
    <link rel="stylesheet" href="/assets/affpilote.css">
    <!-- FONT FAMILY LINK -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish&display=swap">
    <!-- ICON -->
    <link rel="shortcut icon" href="/assets/img/logo.ico" alt="IconImg"/>
	<title>Admin - Stats élève</title>
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



    <section class="sec-head">
        <h1 class="textStats">Stats de l'élève sélèctionné.</h1>
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
                    WHERE id_login = '" . $_GET['id'] . "';";
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $recipes = $recipesStatement->fetchAll();
                    foreach ($recipes as $recipe) {
                        echo "<img src='/assets/img/stats.png' alt='imgSuppStudDefault'>";
                        echo "<h2>Mail : " . $recipe['login'] . "</h2>" ;
                        echo "<p>ID du login : " . $recipe['id_login'] . "</p>";
                        echo "<p>Nom : " .  $recipe['first_student'] . ", Prénom : " . $recipe['last_student'] . "</p>";
                        echo "<p>Promotion : " . $recipe['name_promo'] . "</p>";
                        echo "<p>Centre : " . $recipe['name_centre'] . "</p>";
                    }
                    $sqlREQ ="SELECT id_login, f.id_student, id_intership, apply_intership , COUNT(id_login) as d FROM (SELECT * FROM `wish_list`) as f
                    INNER JOIN student ON student.id_student = f.id_student
                    WHERE apply_intership = 0 AND id_login = '" . $_GET['id'] . "'
                    GROUP BY id_login;";
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $recipes = $recipesStatement->fetchAll();
                    if($recipesStatement->rowCount() != 0){
                        foreach ($recipes as $recipe) {
                            echo "<p>Stage(s) en wish-list : " . $recipe['d'] . "</p>"; 
                        }
                    }
                    else{
                        echo"<p>L'élève n'a ajouté de stage dans sa wish-list.</p>";
                    }
                    $sqlREQ ="SELECT id_login, f.id_student, id_intership, apply_intership , COUNT(id_login) as d FROM (SELECT * FROM `wish_list`) as f
                    INNER JOIN student ON student.id_student = f.id_student
                    WHERE apply_intership = 1 AND id_login = '" . $_GET['id'] . "'
                    GROUP BY id_login;";
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $recipes = $recipesStatement->fetchAll();
                    if($recipesStatement->rowCount() != 0){
                        foreach ($recipes as $recipe) {
                            echo "<p>L'élève a postulé : " . $recipe['d'] . " fois.</p>";
                            ////////////////CLASS UP ////////////////
                            echo "<h1 class='up'>Les stages en question : </h1>";
                            $sqlREQ = "SELECT * FROM (SELECT id_login, intership_term, date_offer, num_place, subject_intership, id_comp FROM (SELECT id_login, f.id_student, id_intership, apply_intership  FROM (SELECT * FROM `wish_list`) as f
                            INNER JOIN student ON student.id_student = f.id_student
                            WHERE apply_intership = 1 AND id_login = '" . $_GET['id'] . "') as k
                            INNER JOIN internship ON internship.id_intership = k.id_intership) as g
                            INNER JOIN companie ON companie.id_comp = g.id_comp;";
                            $recipesStatement = $conn->prepare($sqlREQ);
                            $recipesStatement->execute();
                            $recipes = $recipesStatement->fetchAll();
                            foreach ($recipes as $recipe) {
                                echo "<div class='show-intersh'>";
                                echo "<h3 class='bismillah'>Entreprise : " . $recipe['name_comp'] . "</h3>";
                                echo "<p>Titre du stage : <span>" . $recipe['subject_intership'] . "</span></p>";
                                echo "<p>Domaine d'activité : " . $recipe['activity_area'] . "</p>";
                                echo "<p>E-mail : " . $recipe['email_comp'] . "</p>";
                                echo "<p>Nombre de place : " . $recipe['num_place'] . "</p>";
                                echo "<p>Période du stage : " . $recipe['intership_term'] . "</p>";
                                echo "<p>Date début stage : " . $recipe['date_offer'] . "</p>"; 
                                echo "<a href=''><img src='/assets/img/next.png' alt=''></a>";
                                echo "</div>";
                                /////////////// AJOUTER UN HREF + IMAGE POUR REDIRECTION VIEW ENTREPRISE
                            }
                        }
                    }
                    else{
                        echo"<p>L'élève n'a pas postulé.</p>";
                    }
                    
                    
                    $conn=null;
                }
                catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";} 
            ?>
        </div>
        
        
    </section>
    

    <!-- FOOTER -->
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