<?php

try {

    // Connexion BDD
    $servername = "127.0.0.1";
    $dbname = "remise_diplome";
    $username = "etudiant";
    $userpassword = "TpRez0";

    $lienBDD = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8",
        $username,
        $userpassword
    );

    $lienBDD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Nombre max de places
    $maxPlaces = 253;

    // Nombre d'étudiants
    $reqEtudiants = $lienBDD->query("SELECT COUNT(*) FROM etudiants");
    $nbEtudiants = $reqEtudiants->fetchColumn();

    // Nombre accompagnants
    $reqAcc = $lienBDD->query("SELECT COALESCE(SUM(nb_accompagnants), 0) FROM etudiants");
    $nbAccompagnants = $reqAcc->fetchColumn();

    // Places occupées
    $placesOccupees = $nbEtudiants + $nbAccompagnants;

    // Places restantes
    $placesRestantes = $maxPlaces - $placesOccupees;

    // Liste étudiants
    $requete = $lienBDD->query("SELECT * FROM etudiants ORDER BY nom ASC");
    $etudiants = $requete->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Erreur : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Dashboard</title>
</head>

<body>

<h1>Dashboard - Remise des Diplômes</h1>

<hr>

<h2>Statistiques</h2>

<p>Places maximum : <?php echo $maxPlaces; ?></p>
<p>Places occupées : <?php echo $placesOccupees; ?></p>
<p>Places restantes : <?php echo $placesRestantes; ?></p>
<p>Nombre daccompagnants : <?php echo $nbAccompagnants; ?></p>

<hr>

<h2>Liste des étudiants inscrits</h2>

<table border="1" cellpadding="10">

<tr>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Email</th>
    <th>Téléphone</th>
    <th>Filière</th>
    <th>Année</th>
    <th>Présence</th>
    <th>Accompagnants</th>
    <th>Détails accompagnants</th>
</tr>

<?php foreach ($etudiants as $etu): ?>

<tr>

    <td><?php echo htmlspecialchars($etu['nom']); ?></td>
    <td><?php echo htmlspecialchars($etu['prenom']); ?></td>
    <td><?php echo htmlspecialchars($etu['email']); ?></td>
    <td><?php echo htmlspecialchars($etu['telephone']); ?></td>
    <td><?php echo htmlspecialchars($etu['filiere']); ?></td>
    <td><?php echo htmlspecialchars($etu['annee']); ?></td>
    <td><?php echo htmlspecialchars($etu['presence']); ?></td>
    <td><?php echo htmlspecialchars($etu['nb_accompagnants']); ?></td>

    <td>
        <?php
        if ($etu['nb_accompagnants'] >= 1) {
            echo htmlspecialchars($etu['acc1_nom']) . " " . htmlspecialchars($etu['acc1_prenom']) . "<br>";
        }

        if ($etu['nb_accompagnants'] == 2) {
            echo htmlspecialchars($etu['acc2_nom']) . " " . htmlspecialchars($etu['acc2_prenom']);
        }
        ?>
    </td>

</tr>

<?php endforeach; ?>

</table>

</body>
</html>
