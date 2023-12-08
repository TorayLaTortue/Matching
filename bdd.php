<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        
        $host = "localhost";
        $database = "Matching";
        $username = "Postgres";
        $password = "Vemer835";
        

        // Connexion à la base de données
        $conn = new mysqli($host, $username, $password, $database);

        // Vérifier la connexion
        if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);}

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