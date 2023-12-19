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

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les paramètres de recherche depuis le formulaire
        $champRecherche = isset($_POST["champRecherche"]) ? $_POST["champRecherche"] : '';
        $idConvention = isset($_POST["idConvention"]) ? $_POST["idConvention"] : null;
        $faitMain = isset($_POST["faitMain"]) ? $_POST["faitMain"] : null;
        $achete = isset($_POST["achete"]) ? $_POST["achete"] : null;
        $nomConvention = isset($_POST["nomConvention"]) ? $_POST["nomConvention"] : '';

        // Construire la partie WHERE dynamiquement en fonction des paramètres
        $conditions = array();
        $params = array();

        echo "Valeur de idConvention du formulaire : " . $idConvention . "<br>";

        if (!empty($champRecherche)) {
            $conditions[] = '"Cosplayeur"."NomPrenom" LIKE :champRecherche';
            $params[':champRecherche'] = '%' . $champRecherche . '%';
        }

        if (!is_null($faitMain)) {
            $conditions[] = '"TypeCosplay"."Main" = :faitMain';
            $params[':faitMain'] = $faitMain;
        }

        if (!is_null($achete)) {
            $conditions[] = '"TypeCosplay"."Acheté" = :achete';
            $params[':achete'] = $achete;
        }

        if (!is_null($idConvention)) {
            $conditions[] = '"Cosplayeur"."idConvention" = :idConvention';
            $params[':idConvention'] = $idConvention;
        }

        // Requête SQL avec conditions dynamiques
        $sql = 'SELECT "Cosplayeur"."idCosplayeur", "Cosplayeur"."NomPrenom", "TypeCosplay"."Main", "TypeCosplay"."Acheté", "TypeCosplay"."Qualité", "TypeCosplay"."Niveau", "Convention"."NomConvention"
        FROM "Cosplayeur"
        LEFT JOIN "TypeCosplay" ON "Cosplayeur"."idCosplayeur" = "TypeCosplay"."idCosplayeur"
        LEFT JOIN "Convention" ON "Cosplayeur"."idConvention" = "Convention"."idConvention"';

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        // Exécuter la requête de recherche dans la base de données
        $stmt = $bdd->prepare($sql);
        $stmt->execute($params);

    // Afficher les résultats
    echo "Valeur de idConvention du formulaire : " . $idConvention . "<br>";


    echo "<p>Résultats de la recherche pour '$champRecherche'</p>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //echo "ID: " . $row['idCosplayeur'] . "<br>";
        echo "Nom et Prénom: " . $row['NomPrenom'] . "<br>";
        echo "Fait main: " . ($row['Main'] ? 'Oui' : 'Non') . "<br>";
        echo "Acheté: " . ($row['Acheté'] ? 'Oui' : 'Non') . "<br>";
        echo "Qualité: " . $row['Qualité'] . "<br>";
        echo "Niveau: " . $row['Niveau'] . "<br>";
        echo "Convention: " . $row['NomConvention'] . "<br>";
        echo "<hr>";
    }
} else {
    echo "Aucune donnée n'a été soumise.";
}

// Fermer la connexion à la base de données
$bdd = null;
?>
