<?php

require_once 'fonctions.inc.php';
// middeware : http://195.221.61.190/station2022/


$device = $_GET['device'];
$hexadecimal = $_GET['data'];

if ($device == "DEVICE") {
    $IdStationDec = 1;

// segmentation de la trame
// Conversion Hexa -> decimal

    $EauDec=decodageEau($hexadecimal);
    $CumulPluieDec=decodageCumulPluie($hexadecimal);
    $TauxChargeDec=decodageTauxCharge($hexadecimal);
    $Datetime=decodeDate($hexadecimal);
    MessageErreur($EauDec,$CumulPluieDec,$TauxChargeDec);
    majBdd($IdStationDec, $EauDec, $CumulPluieDec, $TauxChargeDec, $Datetime);    //appel de la fonction majBdd
    
}