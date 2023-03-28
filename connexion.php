<!DOCTYPE html>
<html lang="fr">
										<!-- HEAD -->
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Page de connexion." />
        <!-- CSS -->
		<link rel="stylesheet" href="./assets/conn.css">
        <!-- FONT FAMILY LINK -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mulish&display=swap">
        <!-- ICON -->
        <link rel="shortcut icon" href="./assets/img/logo.ico" alt="IconImg"/>
		<title>Boostage - Connexion</title>
	</head>
<body>
    <header>
        <a href="./index.html"><img src="./assets/img/logo.png" alt="logo"></a>
    </header>
    <section>
        <div class="left">
            <img src="./assets/img/picBottom.png" alt="bot">
        </div>
        <div class="right">
            <h1 class="title">Connectez-vous</h1>
            <!-- FORMS -->
			<form class="form" method="post">
				<!-- INPUT EMAIL / PASSWORD / BUTTON -->
				<input name="login" class="inputID" type="email" placeholder="adresse e-mail" 
				onfocus="this.placeholder=''" onblur="this.placeholder='adresse e-mail'" required>
											<!-- -->
				<input name="passw" class="inputPassword" type="password" placeholder="mot de passe" 
				onfocus="this.placeholder=''" onblur="this.placeholder='mot de passe'" required>
                <?php 
					$server = 'localhost';
					$username = 'root';
					$password = 'Stylo89$';
					$database = 'projet';
					// echo '<font color="red">bonjour</font>';
					// On se coco à la BDD
					if(isset($_POST['con'])){
						try{
							$conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
							//On définit le mode d'erreur de PDO sur Exception	
							$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							if(!empty($_POST)){	
								if(isset($_POST['con'])){
									// ON RECUPERE LES DONNEES DE MES TEXT BOX
									$login = $_POST['login']; $passw = $_POST['passw'];
									$sqlREQ = "SELECT * FROM `authentication` WHERE login = '" . $login . "' AND passw = '" . $passw . "';";
									$recipesStatement = $conn->prepare($sqlREQ);
									$recipesStatement->execute();
									if($recipesStatement->rowCount() != 1){
										echo "<p class='textError'>Erreur d'Email ou de mot de passe</p>";
									}
									else{
										// EMAIL ET MPD DANS LA BDD
										// MAINTENANT VOYONS SI IL EST ADMIN
										$recipes = $recipesStatement->fetchAll();
										foreach ($recipes as $recipe) {
											$var_idlog = $recipe['id_login'];
											$var_admin = $recipe['admin'];
										}	
										session_start();
										$_SESSION['ID'] = $recipe['id_login'];
										// ADMIN DETECTE //
										if($var_admin == 1){ 
											$conn=null;
											header('Location: //./admin/admin.php');
											return; }
										// EST-CE UN TUTEUR ?
										else{
											$sqlREQ = "SELECT * FROM `pilote` WHERE id_login = '" . $var_idlog . "';";
											$recipesStatement = $conn->prepare($sqlREQ);
											$recipesStatement->execute();
											if($recipesStatement->rowCount() != 1){
												// EST-CE UN ELEVE ?
												$sqlREQ = "SELECT * FROM `student` WHERE id_login = '" . $var_idlog . "';";
												$recipesStatement = $conn->prepare($sqlREQ);
												$recipesStatement->execute();
												// PERSONNE PRESENTE DANS `authentication` MAIS PAS DANS  `pilote` NI `student` 
												// (CAS THEORIQUEMENT IMPOSSIBLE)
												if($recipesStatement->rowCount() != 1){ return; }
												// ELEVE DETECTE //
												else{ 
													$conn=null;
													//header('Location: //Projet_Anti_Woke_html/eleve.html'); 
												}
											}
											// TUTEUR DETECTE //
											else{ 
												$conn=null;
												header('Location: //./pilote/pilote.php');
											}
										}
									}
								}
							}
						}
						catch(PDOException $e){ echo "<p class='textError'>Erreur lié à la base de données.</p>";}
					}
				?>
                <input name="con" class="boutonConnexion" type="submit" value="Connexion">
                <img class="hidden" src="./assets/img/picBottom.png" alt="picbot">
			</form>
        </div>
    </section>
</body>
</html>