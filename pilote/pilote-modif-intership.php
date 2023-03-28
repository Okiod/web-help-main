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
	<meta name="description" content="Page pour modifier un stage." />
    <!-- CSS -->
	<link rel="stylesheet" href="/assets/ind.css">
    <link rel="stylesheet" href="/assets/admin-style.css">
    <link rel="stylesheet" href="/assets/addstud.css">
    <link rel="stylesheet" href="/assets/footer.css">
    <!-- FONT FAMILY LINK -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish&display=swap">
    <!-- ICON -->
    <link rel="shortcut icon" href="/assets/img/logo.ico" alt="IconImg"/>
	<title>Pilote - Modifier stage</title>
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
    <section class="mlp">
        <div class="r">
        <form method='POST'>
            <h2>Modifier un stage.</h2>
            <label>Siret de l'entreprise : </label><input type='text' name='siret'  ><br>
            <label>Sujet du stage : </label><input type='text' name='subject'  ><br>
            <label>Nombre de place : </label><input type='text' name='place'  ><br>
            <label>Durée du stage : </label><input type='text' name='term'  ><br>
            <label>Ajouter promotion : </label><input type='text' name='add-promo'  ><br>
            <label>Supprimer promotion : </label><input type='text' name='supp-promo'  ><br>
            <label>Ajouter compétence : </label><input type='text' name='add-skill'  ><br>
            <label>Supprimer compétence : </label><input type='text' name='supp-skill'  ><br>
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
                        if(strlen($_POST['siret'])>0){
                            if(strlen($_POST['siret'])!=9){
                                echo "<p class='textError'>Siret incomplet.</p>";
                            }
                            else {
                                $sqlREQ = "SELECT * FROM companie 
                                WHERE siret = '" . $_POST['siret'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
                                $recipesStatement->execute();
                                if($recipesStatement->rowCount() == 1){
                                    $recipes = $recipesStatement->fetchAll();
                                    foreach ($recipes as $recipe) {
                                        $var_idcomp = $recipe['id_comp'];
                                    }
                                    $sqlREQ ="UPDATE internship SET id_comp = $var_idcomp
                                    WHERE id_intership = '" . $_GET['idinter'] . "';";
                                    $recipesStatement = $conn->prepare($sqlREQ);
                                    $recipesStatement->execute();
                                }
                                else{
                                    echo "<p class='textError'>Siret non enregistré.</p>";
                                }
                            }
                        }
                        if(strlen($_POST['subject'])>0){
                            $sqlREQ ="UPDATE internship SET subject_intership = '" . $_POST['subject'] . "' 
                            WHERE id_intership = '" . $_GET['idinter'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
                            $recipesStatement->execute();
                        }
                        if(strlen($_POST['place'])>0){
                            if(is_numeric($_POST['place'])){
                                $sqlREQ ="UPDATE internship SET num_place = '" . $_POST['place'] . "' 
                                WHERE id_intership = '" . $_GET['idinter'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
                                $recipesStatement->execute();
                            }
                            else{
                                echo "<p class='textError'>Veuillez entrer un entier pour le nombre de place.</p>";
                            }
                        }
                        if(strlen($_POST['term'])>0){
                            $sqlREQ ="UPDATE internship SET intership_term = '" . $_POST['term'] . "' 
                            WHERE id_intership = '" . $_GET['idinter'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
                            $recipesStatement->execute();
                        }

                        // SUPP PROMO
                        if(strlen($_POST['supp-promo'])>0){
                            $sqlREQ = "SELECT * FROM promotion WHERE name_promo = '" . $_POST['supp-promo'] . "';";
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
                                $sqlREQ = "SELECT * FROM target 
                                WHERE id_promo = '" . $var_idpromo . "'
                                AND id_intership = '" . $_GET['idinter'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
							    $recipesStatement->execute();
                                if($recipesStatement->rowCount() != 0){
                                    $sqlREQ = "DELETE FROM target 
                                    WHERE id_intership = '" . $_GET['idinter'] . "' AND id_promo = '" . $var_idpromo . "';";
                                    $recipesStatement = $conn->prepare($sqlREQ);
							        $recipesStatement->execute();
                                }
                                else{
                                    echo "<p class='textError'>Promotion jamais ciblé.</p>";
                                }
                            }
                        }
                        // ADD PROMOTION
                        if(strlen($_POST['add-promo'])>0){
                            $sqlREQ = "SELECT * FROM promotion WHERE name_promo = '" . $_POST['add-promo'] . "';";
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
                                $sqlREQ = "SELECT * FROM target 
                                WHERE id_promo = '" . $var_idpromo . "'
                                AND id_intership = '" . $_GET['idinter'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
							    $recipesStatement->execute();
                                if($recipesStatement->rowCount() != 1){
                                    $sqlREQ = "INSERT INTO target (id_intership, id_promo)
                                    VALUES ('" . $_GET['idinter'] . "','" . $var_idpromo . "');";
                                    $recipesStatement = $conn->prepare($sqlREQ);
							        $recipesStatement->execute();
                                }
                                else{
                                    echo "<p class='textError'>Promotion déjà ajoutée.</p>";
                                }
                            }
                        }
                        // SUPP SKILL
                        if(strlen($_POST['supp-skill'])>0){
                            $sqlREQ = "SELECT * FROM skills WHERE skill = '" . $_POST['supp-skill'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                            if($recipesStatement->rowCount() != 1){
                                echo "<p class='textError'>Vérifier la compétence.</p>";
                            }
                            else{
                                $recipes = $recipesStatement->fetchAll();
								foreach ($recipes as $recipe) {
									$var_idskill= $recipe['id_skill'];
								}
                                $sqlREQ = "SELECT * FROM research 
                                WHERE id_skill = '" . $var_idskill . "'
                                AND id_intership = '" . $_GET['idinter'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
							    $recipesStatement->execute();
                                if($recipesStatement->rowCount() != 0){
                                    $sqlREQ = "DELETE FROM research 
                                    WHERE id_intership = '" . $_GET['idinter'] . "' AND id_skill = '" . $var_idskill . "';";
                                    $recipesStatement = $conn->prepare($sqlREQ);
							        $recipesStatement->execute();
                                }
                                else{
                                    echo "<p class='textError'>Compétence jamais ciblé.</p>";
                                }
                            }
                        }
                        // ADD SKILL
                        if(strlen($_POST['add-skill'])>0){
                            $sqlREQ = "SELECT * FROM skills WHERE skill = '" . $_POST['add-skill'] . "';";
                            $recipesStatement = $conn->prepare($sqlREQ);
							$recipesStatement->execute();
                            if($recipesStatement->rowCount() != 1){
                                echo "<p class='textError'>Vérifier la compétence.</p>";
                            }
                            else{
                                $recipes = $recipesStatement->fetchAll();
								foreach ($recipes as $recipe) {
									$var_idskill = $recipe['id_skill'];
								}
                                $sqlREQ = "SELECT * FROM research 
                                WHERE id_skill = '" . $var_idskill . "'
                                AND id_intership = '" . $_GET['idinter'] . "';";
                                $recipesStatement = $conn->prepare($sqlREQ);
							    $recipesStatement->execute();
                                if($recipesStatement->rowCount() != 1){
                                    $sqlREQ = "INSERT INTO research (id_intership, id_skill)
                                    VALUES ('" . $_GET['idinter'] . "','" . $var_idskill . "');";
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
                    $sqlREQ = "SELECT * FROM (SELECT id_intership,   GROUP_CONCAT( `skill` SEPARATOR ', ' ) as b FROM `research`
                    INNER JOIN skills ON skills.id_skill = research.id_skill
                    GROUP BY id_intership) as x 
                    INNER JOIN (SELECT q.id_intership,  g, d, intership_term, date_offer, num_place, subject_intership, name_comp, email_comp, activity_area, h, siret FROM (SELECT siret, id_intership, g,d,intership_term, date_offer, num_place, subject_intership, name_comp, email_comp, activity_area FROM (SELECT internship.id_intership, g, d, intership_term, date_offer, num_place, subject_intership, id_comp FROM (SELECT id_intership, GROUP_CONCAT( `city_name` SEPARATOR ', ' ) as g, GROUP_CONCAT( `city_postal` SEPARATOR ', ' ) as d 
                    FROM `is_located`
                    INNER JOIN locality ON locality.id_locality = is_located.id_locality
                    GROUP BY id_intership) as c
                    INNER JOIN internship ON internship.id_intership = c.id_intership) as w
                    INNER JOIN companie ON companie.id_comp = w.id_comp) as n
                    INNER JOIN (SELECT id_intership,  GROUP_CONCAT( `name_promo` SEPARATOR ', ' ) as h FROM target
                    INNER JOIN promotion 
                    ON promotion.id_promo = target.id_promo
                    GROUP BY id_intership) as q 
                    ON q.id_intership = n.id_intership) as l 
                    ON x.id_intership = l.id_intership
                    WHERE x.id_intership = '" . $_GET['idinter'] . "';";
                    $recipesStatement = $conn->prepare($sqlREQ);
                    $recipesStatement->execute();
                    $recipes = $recipesStatement->fetchAll();
                    foreach ($recipes as $recipe) {
                        echo "<img src='/assets/img/cv.png' alt='imgSuppStudDefault'>";
                        echo "<h2>SIRET : " . $recipe['siret'] . "</h2>" ;
                        echo "<p>Entreprise : " . $recipe['name_comp'] . "</p>";
                        echo "<p>Sujet du stage : " .  $recipe['subject_intership'] . "</p>";
                        echo "<p>Durée du stage : " .  $recipe['intership_term'] . "</p>";
                        echo "<p>Nombre de place : " . $recipe['num_place'] . "</p>";
                        echo "<p>Promotion(s) : " . $recipe['h'] . "</p>";
                        echo "<p>Skill(s) : " . $recipe['b'] . "</p>";
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