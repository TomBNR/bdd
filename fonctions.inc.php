<?php

require_once './constante.inc';

/**
 * Connexion à la base de données en fonction du SERVEURBDD, NOMDELABASE, LOGIN, MOTDEPASSE
 * renseigné dans le fichier constante
 * @return \PDO
 */
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

/**
 * Ajout des données dans la base de données avec INSERT INTO
 * @param type $IdStationDec numéro de la station
 * @param type EauDec niveau d'eau relever par le capteur
 * @param type $CumulPluieDec cumul de pluie 
 * @param type $TauxChargeDec Niveau charge de la batterie de l'esp32
 * @param type $DateTime Date à laquel la mesure a été effectué 
 */
function majBdd($IdStationDec, $EauDec, $CumulPluieDec, $TauxChargeDec, $DateTime) {
    try {
        // connexion BDD
        $bdd = connexionBdd();
        // execution de la requete
        $requete = $bdd->prepare("INSERT INTO Mesures (IdStation, Eau, CumulPluie, TauxCharge, Date) VALUES (:IdStation,:Eau,:CumulPluie,:TauxCharge,:Date)") ;
        $requete->bindParam(":IdStation", $IdStationDec);
        $requete->bindParam(":Eau", $EauDec);
        $requete->bindParam(":CumulPluie", $CumulPluieDec);
        $requete->bindParam(":TauxCharge", $TauxChargeDec);
        $requete->bindParam(":Date", $DateTime);
        $requete->execute();

    } catch (PDOException $e) {
        print "Erreur : " . $e->getMessage() . "<br/>";
        die();
    }
}

// segmentation de la trame
// Conversion Hexa -> decimal
/**
 * decode la trame hexadécimal et la converti en décimal en prenant les 4 premiers chiffres
 * @param type $hexadecimal
 * @return type
 */
    function decodageEau($hexadecimal) {     //cm
        $Eau = substr($hexadecimal, 0, 4);
        $EauDec = intval($Eau, 16);
        echo "Eau : $EauDec cm<br>";
        return $EauDec;
    }

    function decodageCumulPluie($hexadecimal) {    //mm
        $CumulPluie = substr($hexadecimal, 4, 2);
        $CumulPluieDec = intval($CumulPluie, 16);
        echo "Cumul de la pluie: $CumulPluieDec mm<br>";
        return $CumulPluieDec;
    }

    function decodageTauxCharge($hexadecimal) {
        $TauxCharge = substr($hexadecimal, 6, 2);
        $TauxChargeDec = intval($TauxCharge, 16);
        echo "Niveau de charge de la batterie : $TauxChargeDec %<br>";
        return $TauxChargeDec;
    }

    function decodeDate($hexadecimal) {
        $Date = substr($hexadecimal, 8, 8);
        $DateDec = base_convert($Date, 16, 10);     // conversion Hexa -> décimal
        $Datetime = date('Y-m-d', $DateDec);  // conversion timestamp en datetime
        echo "$Datetime <br>";
        return $Datetime;
    }

    function MessageErreur($EauDec,$CumulPluieDec,$TauxChargeDec){
        if ($EauDec > "10000"){echo "probleme eau </br>";}
        if ($CumulPluieDec > "200"){echo "probleme cumul pluie </br>";}
        if ($TauxChargeDec > "100"){echo "probleme Charge </br>";}
    }