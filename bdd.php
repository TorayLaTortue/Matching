<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        
        $host = "localhost";
        $dbname = "Matching";
        $user = "postgres";
        $password = "Vemer";
        

        // Connexion à la base de données
        try{
        $bdd = new PDO("pgsql:host=$host;port=5432;dbname=$dbname", $user, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
        catch(PDOException $e){// Vérifier la connexion
        die("Échec de la connexion à la base de données : " . $e->getMessage());}

// Vérifier si des données ont été envoyées depuis le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire (ajuster les noms des champs selon votre formulaire)
    $nomPrenom = isset($_POST["user_name"]) ? $_POST["user_name"] : '';
    $dateNaissance = isset($_POST["dateNaissance"]) ? $_POST["dateNaissance"] : '';
    $idConvention = isset($_POST["idConvention"]) ? $_POST["idConvention"] : 0;

    // Utilisation des opérateurs ternaires pour convertir les valeurs des cases à cocher en booléens
    $faitMain = isset($_POST["faitMain"]) ? ($_POST["faitMain"] == "on" ? true : false) : false;
    $achete = isset($_POST["achete"]) ? ($_POST["achete"] == "on" ? true : false) : false;

    $qualite = isset($_POST["Qualité"]) ? $_POST["Qualité"] : '';
    $niveau = isset($_POST["Niveau"]) ? $_POST["Niveau"] : '';

    $sqlMaxId = 'SELECT MAX(idCosplayeur) FROM Cosplayeur';
    $resultMaxId = $bdd->query($sqlMaxId);
    $maxId = $resultMaxId->fetchColumn();
    $newId = $maxId + 1;

    // Requête SQL d'insertion pour la table "Cosplayeur"
    $sqlCosplayeur = 'INSERT INTO "Cosplayeur" (idCosplayeur, NomPrenom, DateNaissance, IdConvention) VALUES (:idCosplayeur, :nomPrenom, :dateNaissance, :idConvention)';

    // Préparer la requête SQL pour "Cosplayeur"
    $stmtCosplayeur = $bdd->prepare($sqlCosplayeur);

    // Liaison des paramètres pour "Cosplayeur"
    $stmtCosplayeur->bindParam(':nomPrenom', $nomPrenom);
    $stmtCosplayeur->bindParam(':dateNaissance', $dateNaissance);
    $stmtCosplayeur->bindParam(':idConvention', $idConvention);
    $stmtCosplayeur->bindParam(':idCosplayeur', $newId);


    // Exécution de la requête pour "Cosplayeur"
    try {
        $stmtCosplayeur->execute();
        echo "Données insérées avec succès dans la table Cosplayeur.";
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion dans la table Cosplayeur : " . $e->getMessage();
    }

    // Requête SQL d'insertion pour la table "TypeCosplay"
    $sqlTypeCosplay = 'INSERT INTO "TypeCosplay" (FaitMain, Achete, Qualite, Niveau, idCosplayeur) VALUES (:faitMain, :achete, :qualite, :niveau, :idCosplayeur)';

    // Préparer la requête SQL pour "TypeCosplay"
    $stmtTypeCosplay = $bdd->prepare($sqlTypeCosplay);

    // Liaison des paramètres pour "TypeCosplay"
    $stmtTypeCosplay->bindParam(':faitMain', $faitMain);
    $stmtTypeCosplay->bindParam(':achete', $achete);
    $stmtTypeCosplay->bindParam(':qualite', $qualite);
    $stmtTypeCosplay->bindParam(':niveau', $niveau);
    $stmtTypeCosplay->bindParam(':idCosplayeur', $newId);


    // Exécution de la requête pour "TypeCosplay"
    try {
        $stmtTypeCosplay->execute();
        echo "Données insérées avec succès dans la table TypeCosplay.";
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion dans la table TypeCosplay : " . $e->getMessage();
    }
} else {
    echo "Aucune donnée n'a été soumise.";
}

// Fermer la connexion à la base de données
$bdd = null;
?>