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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Page d'acceuil des admins."/>
    <title>Admin - Accueil</title>
    <!-- FONT FAMILY LINK -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish&display=swap">
    <!-- ICON -->
    <link rel="shortcut icon" href="/assets/img/logo.ico" alt="IconImg"/>
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/ind.css">
    <link rel="stylesheet" href="/assets/admin-style.css">
</head>
<body>
    <header>
        <!-- HAUT DE PAGE (NAV + LOGO) -->
        <nav class="navbar">
            <a href="./admin.php"><img src="/assets/img/logo.png" alt="LogoPic" class="logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a class="main" href="./admin.php">Accueil</a></li>
                    <li><a href="./admin-companie.html">Entreprise</a></li>
                    <li><a href="#">Stage</a></li>
                    <li><a href="./admin-pilote.php">Pilote</a></li>
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
    <section class="top-ad">
        <div class="left">
            <h2 class="ht">5 dèrnières offres de Stage</h2>
            <?php 
                $server = 'localhost';
                $username = 'root';
                $password = 'Stylo89$';
                $database = 'projet';
                try{
                     $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                     //On définit le mode d'erreur de PDO sur Exception	
                     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                     $sqlREQ = "SELECT * 
                     FROM `internship` 
                     INNER JOIN companie 
                     ON internship.id_comp = companie.id_comp
                     WHERE subject_intership IS NOT NULL
                     ORDER BY id_intership DESC
                     LIMIT 5;";
                     $recipesStatement = $conn->prepare($sqlREQ);
                     $recipesStatement->execute();
                     $recipes = $recipesStatement->fetchAll();
                     foreach ($recipes as $recipe) {

                        echo "<div class='ff'>";
                            echo "<h2 class='stage'> ". $recipe['subject_intership'] . "</h2>";
                            echo "<p>" . $recipe['name_comp'] . "</p>";
                            echo "<p>" . $recipe['activity_area'] . "</p>";
                            echo "<p>Nombre d'étudiants :" . $recipe['num_place'] . "</p>";
                            echo "<p>" . $recipe['date_offer'] . "</p>";
                            echo "</div>";
                     }
                     $conn =null;
                }
                catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
                
            ?>
        </div>
        <div class="center">
            <h2 class="ht">Les entreprises avec les meilleurs notes</h2>
            <?php 
                $server = 'localhost';
                $username = 'root';
                $password = 'Stylo89$';
                $database = 'projet';
                try{
                     $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                     //On définit le mode d'erreur de PDO sur Exception	
                     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                     $sqlREQ = "SELECT *, ROUND(AVG(score),1) as jh FROM `score`
                                INNER JOIN companie ON companie.id_comp = score.id_comp
                                WHERE name_comp IS NOT NULL
                                GROUP BY score.id_comp
                                ORDER BY jh DESC
                                LIMIT 6;";
                     $recipesStatement = $conn->prepare($sqlREQ);
                     $recipesStatement->execute();
                     $recipes = $recipesStatement->fetchAll();
                     foreach ($recipes as $recipe) {
                        echo "<div class='ee'>";
                        echo "<h2>" . $recipe['name_comp'] . "</h2>";
                        echo "<p>Email : " . $recipe['email_comp'] . "</p>";
                        echo "<p>Siret : " . $recipe['siret'] . "</p>";
                        echo "<p class = 'bold-p'>Note : " . $recipe['jh'] . "</p>";
                        echo "</div>";
                     }
                     $conn =null;
                }
                catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
                
            ?>
        </div>
        <div class="right">
            <h2 class="ht">Les élèves ayant le plus postulés</h2>
            <?php 
                $server = 'localhost';
                $username = 'root';
                $password = 'Stylo89$';
                $database = 'projet';
                try{
                     $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                     //On définit le mode d'erreur de PDO sur Exception	
                     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                     $sqlREQ = "SELECT * FROM (SELECT id_student, first_student, last_student, id_promo, name_centre, m FROM (SELECT wish_list.id_student, first_student, last_student, id_centre, id_promo , COUNT(apply_intership) as m
               									                                                                              FROM wish_list 
               									                                                                              INNER JOIN student ON wish_list.id_student = student.id_student 
               									                                                                              GROUP BY wish_list.id_student
                                                                                                                              ORDER BY m DESC) as k 
				                              INNER JOIN centre ON centre.id_centre = k.id_centre) as l
                                INNER JOIN promotion ON l.id_promo = promotion.id_promo
                                LIMIT 6";
                     $recipesStatement = $conn->prepare($sqlREQ);
                     $recipesStatement->execute();
                     $recipes = $recipesStatement->fetchAll();
                     foreach ($recipes as $recipe) {
                        echo "<div class='ee'>";
                        echo "<h2>" . $recipe['first_student'] . " "  . $recipe['last_student'] . "</h2>";
                        echo "<p>Centre : " . $recipe['name_centre'] . "</p>";
                        echo "<p>Promotion : " . $recipe['name_promo'] . "</p>";
                        echo "<p class = 'bold-p'>Nombre de candidatures : " . $recipe['m'] . "</p>";
                        echo "</div>";
                     }
                     $conn =null;
                }
                catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
                
            ?>
            
        </div>
    </section>
    <footer>

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
