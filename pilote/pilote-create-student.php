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
        $sqlREQ = "SELECT * FROM `pilote` WHERE id_login = '" . $_SESSION['ID'] . "';";
		$recipesStatement = $conn->prepare($sqlREQ);
		$recipesStatement->execute();
        $recipes = $recipesStatement->fetchAll();
		if($recipesStatement->rowCount() != 1){
            $conn=null;
            header("Location: //./connexion.php"); // redirige l'utilisateur
        }
        $conn=null;
    }
    catch(PDOException $e){
        header("Location: //./connexion.php"); // redirige l'utilisateur;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Page créer un élève." />
    <!-- CSS -->
	<link rel="stylesheet" href="/assets/ind.css">
    <link rel="stylesheet" href="/assets/admin-style.css">
    <link rel="stylesheet" href="/assets/addstud.css">
    <link rel="stylesheet" href="/assets/footer.css">
    <!-- FONT FAMILY LINK -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish&display=swap">
    <!-- ICON -->
    <link rel="shortcut icon" href="/assets/img/logo.ico" alt="IconImg"/>
	<title>Pilote - Créer un élève</title>
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
                    <li><a href="./pilote-intership.php">Stage</a></li>
                    <li><a class="main" href="./pilote-student.php">Etudiant</a></li>
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

    <section class="mnms">
        <div class="left">
        <form method='POST'>
            <label>Nom:</label><input type='text' name='last_student'  required><br>
            <label>Prénom : </label><input type='text' name='first_student'  required><br>
            <label>Centre : </label><input type='text' name='centre'  required><br>
            <label>Email : </label><input type='text' name='login'  required><br>
            <label>Mot de passe : </label><input type='text' name='passw'  required><br>
            <label>Promotion : </label><input type='text' name='promo'  required><br>
            <?php 
            // ///////////////////////////////////////// PHP ///////////////////////////////////////// //
				$server = 'localhost';
				$username = 'root';
				$password = 'Stylo89$';
				$database = 'projet';
				// On se coco à la BDD
				if(isset($_POST['submit'])){
					try{
						$conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
						//On définit le mode d'erreur de PDO sur Exception	
						$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						if(!empty($_POST)){	
							if(isset($_POST['submit'])){
								// VERIFE CENTRE
								$centre = $_POST['centre'];
                                $sqlREQ = "SELECT * FROM centre WHERE name_centre = '" . $centre . "';";
								$recipesStatement = $conn->prepare($sqlREQ);
								$recipesStatement->execute();
                                if($recipesStatement->rowCount() != 1){
                                    echo "<p class='textError'>Attention, vérifier le centre.</p>";
                                }
                                else{
                                    $recipes = $recipesStatement->fetchAll();
									foreach ($recipes as $recipe) {
										$id_centre = $recipe['id_centre'];
									}
                                    // VERIFE PROMOTION
                                    $promo = $_POST['promo'];
                                    $sqlREQ = "SELECT * FROM promotion WHERE name_promo = '" . $promo . "';";
								    $recipesStatement = $conn->prepare($sqlREQ);
								    $recipesStatement->execute();
                                    if($recipesStatement->rowCount() != 1){
                                        echo "<p class='textError'>Attention, vérifier la promotion.</p>";
                                    }
                                    else{
                                        $recipes = $recipesStatement->fetchAll();
                                        foreach ($recipes as $recipe) {
                                            $id_promo = $recipe['id_promo'];
                                        }
                                        // VERIFIE UNIQUE EMAIL
                                        $login = $_POST['login'];
                                        $sqlREQ = "SELECT * FROM `authentication` WHERE login = '" . $login . "';";
								        $recipesStatement = $conn->prepare($sqlREQ);
								        $recipesStatement->execute();
                                        if($recipesStatement->rowCount() != 0){
                                            echo "<p class='textError'>Attention, le mail est déjà utilisé.</p>";
                                        }
                                        else{
                                            // VERIFE LONGUEUR MDP
                                            $mdp = $_POST['passw'];
                                            if(strlen($mdp)<5){
                                                echo "<p class='textError'>Attention, le mot de passe doit contenir au minimum 6 caractères.</p>";
                                            }
                                            else{
                                                $sqlREQ = "INSERT INTO `authentication` (login, passw, admin) VALUES ('" . $login . "','" . $mdp . "','0');";
								                $recipesStatement = $conn->prepare($sqlREQ);
								                $recipesStatement->execute();
                                                $sqlREQ = "SELECT * FROM `authentication` WHERE login = '" . $login . "';";
                                                $recipesStatement = $conn->prepare($sqlREQ);
								                $recipesStatement->execute();
                                                $recipes = $recipesStatement->fetchAll();
                                                foreach ($recipes as $recipe) {
                                                    $id_login = $recipe['id_login'];
                                                }
                                                $sqlREQ = "INSERT INTO `student` (first_student, last_student, id_centre, id_promo, id_login)
                                                           VALUES ('" . $_POST['first_student'] . "','" . $_POST['last_student'] . "', '" 
                                                           . $id_centre . "','" . $id_promo . "','" . $id_login . "');";
								                $recipesStatement = $conn->prepare($sqlREQ);
								                $recipesStatement->execute();
                                                echo "<p class='textValide'>Elève ajouté.</p>";
                                            }
                                        }
                                    }
                                }
							}
						}
                        $conn=null;
                    }
					catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
                }
                // ///////////////////////////////////////// PHP ///////////////////////////////////////// //
			?>
            <input class="btn" type='submit' name='submit' value='Ajouter'>
        </form>
        </div>
        <div class="right">
            <form method='POST'>
                <img class="avatar" src="/assets/img/avatar.png" alt="">
                <label for="fileUpload">Fichier avatar : </label>
                <input type="file" name="photo" id="fileUpload">
                <p><strong>Note:</strong> Seuls le format .png est autorisé jusqu'à une taille maximale de 5 Mo.</p>

                <!-- CV PDF -->
                <img class="avatar" src="/assets/img/cv.png" alt="">
                <label>Fichier PDF CV : </label>
                <input type="file" name="pdf_file">

                <!-- LM PDF -->
                <label>Fichier PDF LM : </label><input type="file" name="fichierLM">
            </form>
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

