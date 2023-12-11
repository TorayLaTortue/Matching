<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        
        $host = "localhost";
        $dbname = "Matching";
        $user = "postgres";
        $password = "Vemer835";
        

        // Connexion à la base de données
        try{
        $bdd = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);}
        catch(PDOException $e){// Vérifier la connexion
        die("Échec de la connexion à la base de données : " . $e->getMessage());}

       // Requête SQL pour récupérer des données d'une table (remplacez "votre_table" par le nom de votre table)
        $sql = "SELECT * FROM Cosplayeur";
        $result = $conn->query($sql);

        // Vérifier si la requête a réussi
        if ($result) {
        // Parcourir les résultats de la requête
        while ($row = $result->fetch_assoc()) {
        // Afficher les données (vous pouvez ajuster ceci en fonction de votre table)
        echo "idCosplayeur : " . $row["idCosplayeur"] . "<br>";
        //echo "Nom : " . $row["nom"] . "<br>";
        //echo "Description : " . $row["description"] . "<br>";
        // Ajoutez d'autres champs selon votre table
        echo "<br>";}
    // Libérer le résultat
    $result->free();
    } else {
    // Afficher une erreur si la requête a échoué
    echo "Erreur lors de l'exécution de la requête : " . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();
?>