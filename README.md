# Projet WEB
## Lou-Anne, Valentin, Romain, Louis D., Nathan


### Nav
![image](https://user-images.githubusercontent.com/116378179/228184767-da7a4522-0b81-4d49-a665-ec61920367d8.png)
Sur toutes nos pages de notre site, dans un header, nous allons introduire un 'nav' afin de pouvoir naviguer à travers les différentes pages accessibles.

En cas de petite fenêtre nous avons opté pour un MENU-BURGER qui va permettre de 'cacher' le nav et de pouvoir l'afficher à tout moment en appyant sur cette icon :

![image](https://user-images.githubusercontent.com/116378179/228185560-9de31194-6318-40fb-9a32-23d161a592d6.png)

Ce qui nous donne :

![image](https://user-images.githubusercontent.com/116378179/228185636-735b7b01-4745-432b-9bce-cff1ebb03775.png)

### Footer
![image](https://user-images.githubusercontent.com/116378179/228185892-8d58ca8f-a558-4a91-802c-b6f61057a058.png)

Pour le footer nous nous sommes inspirés d'un site déjà existant.

### Authentification
Pour la partie authentification, nous avons fait au plus simple avec un email et un mot-de-passe à remplir, côté PHP, nous vérifions dans la base de donnée l'existance de la personne, si elle existe elle est comparée dans la BDD puis on la redirige en fonction de ses accéssibilités (Admin - Pilote - Etudiant).
Nous allons ensuite récuperer l'**`ID_LOGIN`** de cette personne grâce à un **`$_SESSION`**.
Ce **`$_SESSION`** va nous permettre d'afficher sur chaque page une photo de profil ainsi que l'adresse e-mail utilisé pour naviguer dans le site.
![image](https://user-images.githubusercontent.com/116378179/228188942-16def11a-3102-4de0-a72f-2ebb4060914d.png)

D'ailleurs nous avons mit un bouton qui permet de se déconnecter à tout moment : 
![image](https://user-images.githubusercontent.com/116378179/228190740-16dab6fd-05a0-457d-9ce3-245c83e55251.png)
Ce bouton va permettre de supprimer la superglobal **`$_SESSION`** par le script suivant :
```php
<?php
    session_start();
    $_SESSION = array();//Ecrase tableau de session 
    session_unset(); //Detruit toutes les variables de la session en cours
    session_destroy();//Destruit la session en cours
    header("Location: //./connexion.php"); // redirige l'utilisateur
?>
```

Toujours concernant cette superglobal, avant chaque entré sur une page internet de notre site, nous allons vérifier l'accessibilité de la personne par le script suivant, qui vient comparer si notre **`$_SESSION['ID']`** est bien dans la base de donnée, et vérifie les droits d'accès.
```php
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
```

## CRUD
De manière général, chaque gestion présentée sera présenté par une page d'accueil, en général elle affichera par exemple pour la gestion des étudiants, tous les étudiants de la base de données par 25, il y a également la possibilité d'aller afficher les 25 suivants en ainsi de suite :
![image](https://user-images.githubusercontent.com/116378179/228194553-79bc8329-a967-4b41-af79-9daebe667ba6.png)

La navigation à travers la table se fera par les bouton suivants :
![image](https://user-images.githubusercontent.com/116378179/228194713-c3068c03-9549-4130-bea8-1e9c180b13d4.png)

### Ajout
Pour la création d'un élève nous allons juste modifier les tables **`student`** et **`authentification`** grâce à la page qui se présente comme cela :
![image](https://user-images.githubusercontent.com/116378179/228195174-f82792ee-fe78-4a22-8465-3ca0a8f74782.png)

Bien évidement que nous allons vérifier les informations inscrits dans les **`TEXT-BOX`**, par exemple pour la **`PROMOTION`**, nous allons voir si la promotion est inscrite dans la table **`promotion`**, et nous allons récupérer son **`ID`** afin de l'inscrire dans la table correspondante.

### Afficher / Rechercher
La recherche d'une gestion se fera de la même manière en général, grâce à une barre de recherche qui viendra comparer (grâce à une superglobal **`$_GET`**) dans la base de données : 
![image](https://user-images.githubusercontent.com/116378179/228196460-d30a25c1-f4d9-4836-b3ac-4e0b0ce2f419.png)

La barre de recherche va nous permettre d'aller changer l'URI, par exemple gâce aux **`$_GET['s-bar']`** :
```html
admin-search-student.php?s-bar=Arras
```
Ici nous chercherons les informations qui concernerons le centre d'**`Arras`**

Côté SQL cela donne la requête suivante :
```php
SELECT id_student, first_student, last_student, name_promo, name_centre, d.id_login, login, d.id_login FROM (SELECT id_student, first_student, last_student, name_promo, name_centre, id_login  FROM (SELECT id_student, first_student, last_student, student.id_centre, id_promo, name_centre, id_login 
                        FROM `student`
                        INNER JOIN centre ON centre.id_centre = student.id_centre) as h
                        INNER JOIN promotion ON promotion.id_promo = h.id_promo
                        WHERE first_student IS NOT NULL) as d
                        INNER JOIN authentication ON authentication.id_login = d.id_login
                        
                        WHERE last_student LIKE '" . $_GET['s-bar'] . "' OR first_student LIKE '" . $_GET['s-bar']  . "' OR name_promo LIKE '%" . $_GET['s-bar']  . "%' OR name_centre LIKE '%" . $_GET['s-bar']  . "%' OR login LIKE '" . $_GET['s-bar']  . "' 
                        ORDER BY id_login;
```

### Supprimer
Pour supprimer nous allons juste supprimer les informations non essentielles, tout en gardant les **`ID`** de chaque table afin d'avoir une tracabilité juridique.
Ce qui nous donne visuellement :
![image](https://user-images.githubusercontent.com/116378179/228197975-cb5cc41e-4caf-48bb-9037-868ea448d514.png)

### Modifier 
Plus ou moins pareil que l'ajout d'un étudiant, sauf que nous aurons accès aux informations de l'étudiant sur le côté de la fenêtre :

![image](https://user-images.githubusercontent.com/116378179/228198272-909dd6c6-045a-4664-8da3-972c7e446df7.png)

### Statistiques
Pour les statistiques de l'étudiants, nous avons voulu mettre en avant les stages dans la **`WISH-LIST`** et les stages où un étudiant a postulé, et les mettre en avant sur la page, ce qui nous donne :
![image](https://user-images.githubusercontent.com/116378179/228198942-83306ec7-a528-4850-b1f7-5f753f1d02cb.png)
