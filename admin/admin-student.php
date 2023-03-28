<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="./css/template.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Information</title>
  </head>
  <h1>Ajout d'un étudiant</h1>
  <body>
  <?php

try {
  $db = new PDO('mysql:host=localhost;dbname=projet;charset=utf8', 'root');
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}

// Formulaire pour ajouter une nouvelle ligne à la base de données
echo "<form method='POST'>";
echo "<label>Nom:</label><input type='text' name='last_student'><br>";
echo "<label>Prénom:</label><input type='text' name='first_student'><br>";
echo "<label>cv:</label><input type='text' name='cv'><br>";
echo "<label>lm:</label><input type='text' name='lm'><br>";
echo "<label>id_centre:</label><input type='text' name='id_centre'><br>";
echo "<label>login:</label><input type='text' name='login'><br>";
echo "<label>password:</label><input type='text' name='passw'><br>";
echo "<label>admin:</label><input type='text' name='admin'><br>";
echo "<label>photo:</label><input type='text' name='link_picture'><br>";
echo "<label>id_promo:</label><input type='text' name='id_promo'><br>";
echo "<input type='submit' name='submit' value='Ajouter'>";
echo "</form>";
// Si le formulaire est soumis, ajouter une nouvelle ligne à la base de données
if (isset($_POST['submit'])) {
    $nom = $_POST['last_student'];
    $prénom = $_POST['first_student'];
    $cv=$_POST['cv'];
    $lm=$_POST['lm'];
    $promo=$_POST['id_promo'];
    $login=$_POST['login'];
    $psw=$_POST['passw'];
    $adm=$_POST['admin'];
    $lp=$_POST['link_picture'];
    $centre=$_POST['id_centre'];
  $count=0;
    $sql = "SELECT * FROM authentication";
    $result = $db->query($sql);
    foreach($result as $row) {
        if(strpos($row["login"], $login) !== false) {
            $count++;
        }
    }
    echo "Le login ".$login." apparaît " . $count . " fois dans la table.";
    if ($count==0){
      echo" Le login est donc disponible.";
    }
    else{
       echo" Le login n'est donc pas disponible.";
      }
      $recipesStatement = $db->prepare($sql);
      $recipesStatement->execute();
      $recipes = $recipesStatement->fetchAll();
                        foreach ($recipes as $recipe) {
                            $var_idlog = $recipe['id_login']+1;
                            $var_admin = $recipe['admin'];
                        }

  $query = "INSERT Into authentication (id_login, login, passw, admin, link_picture)  values ('".$var_idlog."','".$login."','".$psw ."','".$adm ."','".$lp ."');";
  $stmt = $db->prepare($query);
  $stmt->execute();
  $query2="INSERT INTO student  ( first_student, last_student, cv, lm, id_promo, id_centre, id_login ) VALUES ('".$prénom ."', '". $nom ."','".$cv."','".$lm."','".$promo."','".$centre ."','".$var_idlog."');";
  $stmt=$db->prepare($query2);
  $stmt->execute();}

// Récupération des données de la table
$query = "SELECT * FROM student inner join authentication on student.id_login=authentication.id_login inner join centre on student.id_centre= centre.id_centre;";
$stmt = $db->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Affichage des données sous forme de grille
echo "<table>";
echo "<tr><th>Prénom</th><th>Nom</th><th>cv</th><th>lm</th></tr>";
foreach ($data as $row) {
  echo"<form>";
  echo "<tr><td>" . $row['id_login'] ."</td><td>     " . $row['first_student'] . "     </td><td>" . $row['last_student'] . "     </td><td>" . $row['cv']."     </td><td>" . $row['lm']."     </td><td>" .$row['login']."     </td><td>" .$row['passw']."</td><td>".$row['name_centre']."</td></tr>";
echo "</from>";
}
echo "</table>";


?>

<button  onclick="changeColor()" >mode sombre</button>  
<button  onclick="changeColor2()">mode claire</button>




<br>
<footer><button> <a href="http:./étudiant/prjv1phpdel.php">Cliquez ici pour supprimer un étudiant</button>
        <button> <a href="http:./étudiant/prjv1phpsee.php">Cliquez ici pour voir les étudiants</button>
        <button> <a href="http:./étudiant/prjv1phpup.php">Cliquez ici pour modifier un étudiant</button>


</footer>          

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <script>
    function changeColor2() {                                       /*script mode sombre*/
        document.body.style.backgroundColor = "#ffffff";
        document.body.style.color="#141414"; /* Blanc */
        document.querySelector("pix").color="#BE453C";}
</script>
<script>
    function changeColor() {                                        /*script mode clair*/
        document.body.style.backgroundColor = "#141414";
        document.body.style.color="#ffffff" /* Noir */
        document.querySelector("pix").color="#ffffff";
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
  
  </body>
</html>