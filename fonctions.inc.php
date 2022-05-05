<?php

define("SERVEURBDD", "172.18.58.86");
define("LOGIN", "root");
define("MOTDEPASSE", "toto");
define("NOMDELABASE", "mesure_piezometrique");

function connexionBdd() {
    try {

        $pdOptions = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        $bdd = new PDO('mysql:host=' . SERVEURBDD . ';dbname=' . NOMDELABASE, LOGIN, MOTDEPASSE, $pdOptions);
        $bdd->exec('set names utf8');
        echo "Connexion base de données > OK !";
        echo "<br/>";
        echo "Enregistrement base de données > OK !";
        return $bdd;
        //si erreur on tue le processus et on affiche le message d'erreur    
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}

function majBdd($IdStationDec, $NiveauEauDec, $CumulPluieDec, $TauxChargeDec, $DateTime) {
    try {
        // connexion BDD
        $bdd = connexionBdd();
        // execution de la requete
        $requete = $bdd->prepare("INSERT INTO Mesures (IdStation, NiveauEau, CumulPluie, TauxCharge, Date) VALUES (:IdStation,:NiveauEau,:CumulPluie,:TauxCharge,:Date)") ;
        $requete->bindParam(":IdStation", $IdStationDec);
        $requete->bindParam(":NiveauEau", $NiveauEauDec);
        $requete->bindParam(":CumulPluie", $CumulPluieDec);
        $requete->bindParam(":TauxCharge", $TauxChargeDec);
        $requete->bindParam(":Date", $DateTime);
        $requete->execute();

    } catch (PDOException $e) {
        print "Erreur : " . $e->getMessage() . "<br/>";
        die();
    }
}

 function decodageNiveauEau($hexadecimal) {
        $NiveauEau = substr($hexadecimal, 0, 4);
        $NiveauEauDec = intval($NiveauEau, 16);
        return $NiveauEauDec;
    }
    
     function decodageCumulPluie($hexadecimal) {
        $CumulPluie = substr($hexadecimal, 6, 2);
        $CumulPluieDec = intval($CumulPluie, 16);
        return $CumulPluieDec;
    }

    function decodageTauxCharge($hexadecimal) {
        $TauxCharge = substr($hexadecimal, 8, 2);
        $TauxChargeDec = intval($TauxCharge, 16);
        return $TauxChargeDec;
    }

    function decodeDate($hexadecimal) {
        $Date = substr($hexadecimal, 10, 8);
        $DateDec = base_convert($Date, 16, 10); // conversion Hexa -> décimal
        $Datetime = date('Y-m-d H:i:s', $DateDec); // conversion timestamp en datetime
        return $Datetime;
    }
    

