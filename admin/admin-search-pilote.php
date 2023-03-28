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
	<meta name="description" content="Page d'affichage des pilotes." />
    <!-- CSS -->
	<link rel="stylesheet" href="/assets/ind.css">
    <link rel="stylesheet" href="/assets/admin-style.css">
    <link rel="stylesheet" href="/assets/addstud.css">
    <link rel="stylesheet" href="/assets/footer.css">
    <!-- FONT FAMILY LINK -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish&display=swap">
    <!-- ICON -->
    <link rel="shortcut icon" href="/assets/img/logo.ico" alt="IconImg"/>
	<title>Admin - Afficher pilotes</title>
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

    <div class="a">
        <a href="./admin-create-pilote.php">Créer un pilote</a>
    </div>

   <form class="xdxd" method="get">
        <input class="search" type="text" name="s-bar" placeholder="Rechercher">
        <input class="butt" type="submit">
   </form>

    <section class="tableau">
        <table>
        <thead>
            <th class="k-hissen">ID_LOGIN</th>
            <th class="l-hissen">Nom</th>
            <th class="l-hissen">Prenom</th>
            <th>E-mail</th>
            <th>Centre</th>
            <th>Promotion(s)</th>
            <th>Modifier</th>
            <th>Supprimer</th>
        </thead>
            <?php
                $server = 'localhost';
                $username = 'root';
                $password = 'Stylo89$';
                $database = 'projet';
                try{
                    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
                    //On définit le mode d'erreur de PDO sur Exception	
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    if(!isset($_GET['s-bar']) || $_GET['s-bar']==NULL){
                        $sqlREQ = "SELECT * FROM (SELECT id_login, firstname_pilote, lastname_pilote, login, name_centre, GROUP_CONCAT( `name_promo` SEPARATOR ' | ' ) as st FROM (SELECT id_login, manage.id_pilote, id_promo, firstname_pilote, lastname_pilote, login, name_centre FROM manage INNER JOIN (SELECT id_login, id_pilote, firstname_pilote, lastname_pilote, login, name_centre FROM (SELECT pilote.id_login, id_pilote, firstname_pilote, lastname_pilote, id_centre, login FROM pilote
                        INNER JOIN authentication ON authentication.id_login = pilote.id_login
                        WHERE login IS NOT NULL) as d
                        INNER JOIN centre ON centre.id_centre = d.id_centre) as x ON x.id_pilote = manage.id_pilote) as w
                        INNER JOIN promotion ON promotion.id_promo = w.id_promo
                        GROUP BY id_login) as g
                        ORDER BY RAND()
                        LIMIT 25;";
                    }
                    else {
                        $sqlREQ = "SELECT * FROM (SELECT id_login, firstname_pilote, lastname_pilote, login, name_centre , GROUP_CONCAT( `name_promo` SEPARATOR ' | ' ) AS st FROM (SELECT id_login, manage.id_pilote, id_promo, firstname_pilote, lastname_pilote, login, name_centre 																													   FROM manage INNER JOIN (SELECT id_login, id_pilote, firstname_pilote, lastname_pilote, login, name_centre 
                        FROM (SELECT pilote.id_login, id_pilote, firstname_pilote, 																																															lastname_pilote, id_centre, login 																																 										  FROM pilote
                              INNER JOIN authentication ON authentication.id_login = 																																																				pilote.id_login
                              WHERE login IS NOT NULL) as d
                        INNER JOIN centre ON centre.id_centre = d.id_centre) as x ON 																																															x.id_pilote = manage.id_pilote) as w
                        INNER JOIN promotion ON promotion.id_promo = w.id_promo
                        GROUP BY id_login) as f 
                        WHERE st LIKE '%" . $_GET['s-bar'] . "%' OR firstname_pilote 
                        LIKE '" . $_GET['s-bar'] . "' OR lastname_pilote LIKE '" . $_GET['s-bar'] . "' OR login LIKE '" . $_GET['s-bar'] . "' OR
                        name_centre LIKE '" . $_GET['s-bar'] . "';";
                    }
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $recipes = $recipesStatement->fetchAll();
                    foreach ($recipes as $recipe) {
                        echo "<tr>";
                        echo "<td class='k-hissen'>" . $recipe['id_login'] . "</td>";
                        echo "<td>" . $recipe['firstname_pilote'] . "</td>";
                        echo "<td>" . $recipe['lastname_pilote'] . "</td>";
                        echo "<td class='l-hissen'>" . $recipe['login'] . "</td>";
                        echo "<td>" . $recipe['name_centre'] . "</td>";
                        echo "<td>" . $recipe['st'] . "</td>";
                        
                        echo "<td><a href='admin-modif-pilote.php?id=" . $recipe['id_login'] . "'><img class='mod-pic' src='/assets/img/mod.png' alt='ModPic'></a></td>";
                        echo "<td><a href='admin-delete-pilote.php?id=" . $recipe['id_login'] . "'><img class='del-pic' src='/assets/img/sup.png' alt='SupPic'></a></td>";
                        echo "</tr>";
                    }
                    $conn=null;
                }
                catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
            ?>
        </table>
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