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
        $qualite = isset($_POST["Qualite"]) ? $_POST["Qualite"] : null;
        $niveau = isset($_POST["Niveau"]) ? $_POST["Niveau"] : null;
        $nomPersonnage = isset($_POST["NomPersonnage"]) ? $_POST["NomPersonnage"] : '';
        $origineCosplay = isset($_POST["OrigineCosplay"]) ? $_POST["OrigineCosplay"] : '';


        // Construire la partie WHERE dynamiquement en fonction des paramètres
        $conditions = array();
        $params = array();

        //echo "Valeur de idConvention du formulaire : " . $idConvention . "<br>";
        //echo "Valeur de faitMain du formulaire dans POST : " . $faitMain . "<br>";
        //echo "Valeur de achete du formulaire dans POST : " . $achete . "<br>";
        //echo "Valeur de idConvention du formulaire : " . $niveau . "<br>";
        //echo "Valeur de idConvention du formulaire : " . $qualite . "<br>";
        //echo "Valeur de idConvention du formulaire : " . $origineCosplay . "<br>";
        

        
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

        if (!is_null($qualite)) {
            $conditions[] = '"TypeCosplay"."Qualité" = :Qualite';
            $params[':Qualite'] = $qualite;
        }
    
        if (!is_null($niveau)) {
            $conditions[] = '"TypeCosplay"."Niveau" = :Niveau';
            $params[':Niveau'] = $niveau;
        }
    
        if (!empty($origineCosplay)) {
            $conditions[] = '"IdentitéCosplay"."OrigineCosplay" LIKE :OrigineCosplay';
            $params[':OrigineCosplay'] = '%' .  $origineCosplay . '%' ;
        }

        if (!empty($nomPersonnage)) {
            $conditions[] = '"IdentitéCosplay"."NomPersonnage" LIKE :NomPersonnage';
            $params[':NomPersonnage'] = '%' . $nomPersonnage . '%';
        }
    


        // Requête SQL avec conditions dynamiques
        $sql = 'SELECT "Cosplayeur"."idCosplayeur", "Cosplayeur"."NomPrenom", "TypeCosplay"."Main", "TypeCosplay"."Acheté", "TypeCosplay"."Qualité", "TypeCosplay"."Niveau", "Convention"."NomConvention", "Convention"."NomPays", "Convention"."NomRegion", "IdentitéCosplay"."NomPersonnage", "IdentitéCosplay"."OrigineCosplay"
        FROM "Cosplayeur"
        LEFT JOIN "TypeCosplay" ON "Cosplayeur"."idCosplayeur" = "TypeCosplay"."idCosplayeur"
        LEFT JOIN "Convention" ON "Cosplayeur"."idConvention" = "Convention"."idConvention"
        LEFT JOIN "IdentitéCosplay" ON "Cosplayeur"."idCosplayeur" = "IdentitéCosplay"."idCosplayeur"';




        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        
        //echo $sql;
        // Exécuter la requête de recherche dans la base de données
        $stmt = $bdd->prepare($sql);
        $stmt->execute($params);

    // Afficher les résultats
    //echo "Valeur de idConvention du formulaire : " . $idConvention . "<br>";


    echo "<div class='resultat'>";
    echo "<p>Résultats de la recherche </p>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<div class='resultat-individuel'>";
    echo "<p>Nom et Prénom: " . $row['NomPrenom'] . "</p>";
    echo "<p>Fait main: " . ($row['Main'] ? 'Oui' : 'Non') . "</p>";
    echo "<p>Acheté: " . ($row['Acheté'] ? 'Oui' : 'Non') . "</p>";
    echo "<p>Qualité: " . $row['Qualité'] . "</p>";
    echo "<p>Niveau: " . $row['Niveau'] . "</p>";
    echo "<p>Convention: " . $row['NomConvention'] . "</p>";
    echo "<p>Nom du Pays: " . $row['NomPays'] . "</p>";
    echo "<p>Nom de la Région: " . $row['NomRegion'] . "</p>";
    echo "<p>Nom du personnage: " . $row['NomPersonnage'] . "</p>";
    echo "<p>Origine du cosplay: " . $row['OrigineCosplay'] . "</p>";
    echo "</div>";
    echo "<hr>";
}
echo "</div>";}
    else {
        echo "Aucune donnée n'a été soumise.";
    }

// Fermer la connexion à la base de données
$bdd = null;
?>
