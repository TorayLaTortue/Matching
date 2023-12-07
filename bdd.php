<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        
        // Connexion à la base de données PostgreSQL
        $host = "localhost";
     
        $dbname = "Matching"; // Nom de votre base de données
        $user = "Postgres"; // Votre nom d'utilisateur PostgreSQL
        $password = "Vemer835"; // Votre mot de passe PostgreSQL

        $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

        // Vérifier la connexion
        if (!$conn) {
            die("Connection failed: " . pg_last_error());
        }

        // Exécuter une requête SQL
        $sql = "SELECT * FROM Tables";  // Remplacez par le nom de votre table
        $result = pg_query($conn, $sql);

        // Afficher les résultats dans un tableau HTML
        if ($result) {
            echo "<table border='1'><tr><th>ID</th><th>Nom</th></tr>";
            while($row = pg_fetch_assoc($result)) {
                echo "<tr><td>" . $row["id"] . "</td><td>" . $row["nom"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        // Fermer la connexion à la base de données
        pg_close($conn);
    ?>
    