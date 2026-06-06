<?php
	session_start(); // Démarrer la session
	
	// Récupération des données directement depuis GET
	$nom = $_GET["nom"];
	$prenom = $_GET["prenom"];
	$date_naissance = $_GET["date_naissance"];
	$num_etudiant = $_GET["num_etudiant"];
	$email = $_GET["email"];
	$telephone = $_GET["telephone"];
	$filiere = $_GET["filiere"];
	$annee = $_GET["annee"];
	$presence = $_GET["presence"];
	$accompagnants = $_GET["accompagnants"];
	$acc1_nom = $_GET["acc1_nom"];
	$acc1_prenom = $_GET["acc1_prenom"];
	$acc2_nom = $_GET["acc2_nom"];
	$acc2_prenom = $_GET["acc2_prenom"];
	
	// Afficher les données saisies par l'utilisateur
	echo "<h1>Les informations saisies par l'utilisateur</h1>";
	
	echo "<p>Nom : " . $nom . "</p>";
	echo "<p>Prénom : " . $prenom . "</p>";
	echo "<p>Date de naissance : " . $date_naissance . "</p>";
	echo "<p>Numéro étudiant : " . $num_etudiant . "</p>";
	echo "<p>Email : " . $email . "</p>";
	echo "<p>Téléphone : " . $telephone . "</p>";
	echo "<p>Filière : " . $filiere . "</p>";
	echo "<p>Année : " . $annee . "</p>";
	echo "<p>Présence : " . $presence . "</p>";
	echo "<p>Nombre d'accompagnants : " . $accompagnants . "</p>";
	
	if ($accompagnants >= 1) {
    	echo "<h3>Accompagnant 1</h3>";
    	echo "<p>Nom : " . $acc1_nom . "</p>";
    	echo "<p>Prénom : " . $acc1_prenom . "</p>";
	}
	
	if ($accompagnants == 2) {
    	echo "<h3>Accompagnant 2</h3>";
    	echo "<p>Nom : " . $acc2_nom . "</p>";
    	echo "<p>Prénom : " . $acc2_prenom . "</p>";
	}
	
	// Afficher la session pour debug
	echo "<h3>Session</h3>";
	echo "<pre>";
	print_r($_SESSION);
	echo "</pre>";
	
	
	
	try 
	{
    // connexion BDD
    $servername = "127.0.0.1";
    $dbname = "remise_diplome";  
    $username = "etudiant";
    $userpassword = "TpRez0";

    $lienBDD = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $userpassword);
    $lienBDD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Accès BDD réalisé<br>";

    // Vérifier le nombre maximum de places
    $requeteCount = $lienBDD->query("SELECT COUNT(*) FROM etudiants");
    $nbPlaces = $requeteCount->fetchColumn();

    if ($nbPlaces >= 253) {
        die("Le nombre maximum de places est atteint, contacter l'administrateur : ");
    }

    // préparation requête
    $requeteSQL = $lienBDD->prepare(
        "INSERT INTO etudiants
        (nom, prenom, date_naissance, num_etudiant, email, telephone, filiere, annee, presence, nb_accompagnants,
         acc1_nom, acc1_prenom, acc2_nom, acc2_prenom)
        VALUES
        (:nom, :prenom, :date_naissance, :num_etudiant, :email, :telephone, :filiere, :annee, :presence, :nb_accompagnants,
         :acc1_nom, :acc1_prenom, :acc2_nom, :acc2_prenom)"
    );

    // liaison des paramètres
    $requeteSQL->bindParam(":nom", $nom);
    $requeteSQL->bindParam(":prenom", $prenom);
    $requeteSQL->bindParam(":date_naissance", $date_naissance);
    $requeteSQL->bindParam(":num_etudiant", $num_etudiant);
    $requeteSQL->bindParam(":email", $email);
    $requeteSQL->bindParam(":telephone", $telephone);
    $requeteSQL->bindParam(":filiere", $filiere);
    $requeteSQL->bindParam(":annee", $annee);
    $requeteSQL->bindParam(":presence", $presence);
    $requeteSQL->bindParam(":nb_accompagnants", $accompagnants);
    $requeteSQL->bindParam(":acc1_nom", $acc1_nom);
    $requeteSQL->bindParam(":acc1_prenom", $acc1_prenom);
    $requeteSQL->bindParam(":acc2_nom", $acc2_nom);
    $requeteSQL->bindParam(":acc2_prenom", $acc2_prenom);

    // exécution
    $requeteSQL->execute();
    echo "Données insérées avec succès !<br>";
}
catch (PDOException $error)
{
    die('Erreur : ' . $error->getMessage());
}
finally 
{
    $lienBDD = null;
    echo "Fin du traitement<br>";
}
?>
