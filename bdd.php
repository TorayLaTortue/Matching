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

// Requête SQL pour récupérer des données de la table Convention
$sql = 'SELECT * FROM "Convention" c';

// Exécution de la requête SQL
try {
    $result = $bdd->query($sql);

    // Vérifier si la requête a réussi
    if ($result) {
        // Parcourir les résultats de la requête
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Afficher les données (vous pouvez ajuster ceci en fonction de votre table)
            echo "NomConvention : " . $row["NomConvention"] . "<br>";
            echo "NomPays : " . $row["NomPays"] . "<br>";
            echo "NomRegion : " . $row["NomRegion"] . "<br>";
            echo "idConvention : " . $row["idConvention"] . "<br>";
            echo "<br>";
        }

        // Libérer le résultat
        $result->closeCursor();
    } else {
        // Afficher une erreur si la requête a échoué
        echo "Erreur lors de l'exécution de la requête : " . print_r($bdd->errorInfo(), true);
    }
} catch (PDOException $e) {
    // Gérer les exceptions PDO
    echo "Erreur PDO : " . $e->getMessage();
}

// Fermer la connexion à la base de données
$bdd = null;
?>
