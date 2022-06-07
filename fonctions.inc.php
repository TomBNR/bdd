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
 * décode les 4 premiers caractères du champ DEVICE
 * qui correspond au niveau de l'eau
 * et qui sera converti de l'hexadécimal vers décimal
 * @param type $hexadecimal
 * @return type
 */
    function decodageEau($hexadecimal) {     //cm
        $Eau = substr($hexadecimal, 0, 4);
        $EauDec = intval($Eau, 16);
        echo "Eau : $EauDec cm<br>";
        return $EauDec;
    }
/**
 * décode les 2 caractères après ceux du decodageEau du champ DEVICE
 * qui correspond au cumul de la pluie
 * et qui sera converti de l'hexadécimal vers décimal
 * @param type $hexadecimal
 * @return type
 */
    function decodageCumulPluie($hexadecimal) {    //mm
        $CumulPluie = substr($hexadecimal, 4, 2);
        $CumulPluieDec = intval($CumulPluie, 16);
        echo "Cumul de la pluie: $CumulPluieDec mm<br>";
        return $CumulPluieDec;
    }
/**
 * décode les 2 caractères après ceux du decodageCumulPluie du champ DEVICE
 * qui correspond au taux de charge de la batterie de la station
 * et qui sera converti de l'hexadécimal vers décimal
 * @param type $hexadecimal
 * @return type
 */
    function decodageTauxCharge($hexadecimal) {
        $TauxCharge = substr($hexadecimal, 6, 2);
        $TauxChargeDec = intval($TauxCharge, 16);
        echo "Niveau de charge de la batterie : $TauxChargeDec %<br>";
        return $TauxChargeDec;
    }
/**
 * décode les 8 caractères après ceux du decodageTauxCharge du champ DEVICE
 * qui correspond à la date à laquelle les mesures ont été prises par la station
 * et qui sera converti de l'hexadécimal vers décimal
 * @param type $hexadecimal
 * @return type
 */
    function decodeDate($hexadecimal) {
        $Date = substr($hexadecimal, 8, 8);
        $DateDec = base_convert($Date, 16, 10);     // conversion Hexa -> décimal
        $Datetime = date('Y-m-d', $DateDec);  // conversion timestamp en datetime
        echo "$Datetime <br>";
        return $Datetime;
    }
/**
 * function qui affiche une erreur si
 * EauDec dépasse 10000 cm,
 * CumulPluieDec dépasse 200 mm,
 * TauxChargeDec dépasse 100 %
 * car les mesures sont irréalistes
 * @param type $EauDec
 * @param type $CumulPluieDec
 * @param type $TauxChargeDec
 */
    function MessageErreur($EauDec,$CumulPluieDec,$TauxChargeDec){
        if ($EauDec > "10000"){echo "probleme eau </br>";}
        if ($CumulPluieDec > "200"){echo "probleme cumul pluie </br>";}
        if ($TauxChargeDec > "100"){echo "probleme Charge </br>";}
    }