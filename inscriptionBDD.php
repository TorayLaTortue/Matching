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

    $dateDeNaissance = isset($_POST["DateDeNaissance"]) ? $_POST["DateDeNaissance"] : '';
    // Initialiser la variable $dateDeNaissance au format attendu par PostgreSQL

    
    $idConvention = isset($_POST["idConvention"]) ? $_POST["idConvention"] : 0;
    
    // Utilisation des opérateurs ternaires pour convertir les valeurs des cases à cocher en booléens
    $main = isset($_POST["Main"]) && $_POST["Main"] == "on" ? true : false;
    $achete = isset($_POST["Acheté"]) && $_POST["Acheté"] == "on" ? true : false;
    
    $qualite = isset($_POST["Qualité"]) ? $_POST["Qualité"] : '';
    $niveau = isset($_POST["Niveau"]) ? $_POST["Niveau"] : '';
    $nomPersonnage = isset($_POST["NomPersonnage"]) ? $_POST["NomPersonnage"] : '';
    $origineCosplay = isset($_POST["OrigineCosplay"]) ? $_POST["OrigineCosplay"] : '';



    $sqlMaxId = 'SELECT MAX("idCosplayeur") FROM "Cosplayeur"';

    $resultMaxId = $bdd->query($sqlMaxId);
    $maxId = $resultMaxId->fetchColumn();
    $newId = $maxId + 1;

    // Requête SQL d'insertion pour la table "Cosplayeur"
    $sqlInsertCosplayeur = 'INSERT INTO "Cosplayeur" ("idCosplayeur", "NomPrenom", "DateDeNaissance", "idConvention") VALUES (:idCosplayeur, :nomPrenom, :dateDeNaissance, :idConvention)';

    // Préparer la requête SQL pour "Cosplayeur"
    $stmtCosplayeur = $bdd->prepare($sqlInsertCosplayeur);

    // Liaison des paramètres pour "Cosplayeur"
    $stmtCosplayeur->bindParam(':nomPrenom', $nomPrenom);
    $stmtCosplayeur->bindParam(':dateDeNaissance', $dateDeNaissance, PDO::PARAM_STR);
    $stmtCosplayeur->bindParam(':idConvention', $idConvention);
    $stmtCosplayeur->bindParam(':idCosplayeur', $newId);


    // Exécution de la requête pour "Cosplayeur"
    try {
        $stmtCosplayeur->execute();
        echo "Données Cosplayeur insérées avec succès dans la table Cosplayeur.";
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion dans la table Cosplayeur : " . $e->getMessage();
    }

    // Requête SQL d'insertion pour la table "TypeCosplay"
    $sqlInsertTypeCosplay = 'INSERT INTO "TypeCosplay" ("Main", "Acheté", "Qualité", "Niveau", "idCosplayeur") VALUES (:main, :achete, :qualite, :niveau, :idCosplayeur)';

    // Préparer la requête SQL pour "TypeCosplay"
    $stmtTypeCosplay = $bdd->prepare($sqlInsertTypeCosplay);

    // Liaison des paramètres pour "TypeCosplay"
    $stmtTypeCosplay->bindParam(':main', $main, PDO::PARAM_BOOL);
    $stmtTypeCosplay->bindParam(':achete', $achete, PDO::PARAM_BOOL);
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

        // Requête SQL d'insertion pour la table "IdentitéCosplay"
    $sqlInsertIdentiteCosplay = 'INSERT INTO "IdentitéCosplay" ("idCosplayeur", "NomPersonnage", "OrigineCosplay") VALUES (:idCosplayeur, :nomPersonnage, :origineCosplay)';

    // Préparer la requête SQL pour "IdentitéCosplay"
    $stmtIdentiteCosplay = $bdd->prepare($sqlInsertIdentiteCosplay);

    // Liaison des paramètres pour "IdentitéCosplay"
    $stmtIdentiteCosplay->bindParam(':nomPersonnage', $nomPersonnage);
    $stmtIdentiteCosplay->bindParam(':origineCosplay', $origineCosplay);
    $stmtIdentiteCosplay->bindParam(':idCosplayeur', $newId);

    // Exécution de la requête pour "IdentitéCosplay"
    try {
        $stmtIdentiteCosplay->execute();
        echo "Données insérées avec succès dans la table IdentitéCosplay.";
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion dans la table IdentitéCosplay : " . $e->getMessage();
    }
}

else {
    echo "Aucune donnée n'a été soumise.";
}

// Fermer la connexion à la base de données
$bdd = null;
?>