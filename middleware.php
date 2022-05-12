<?php

require_once 'fonctions.inc.php';

// phpinfo(); // php v7.3
// middeware : http://195.221.61.190/station2022/decode.php?device={device}&data={data}&time={time}

$device = $_GET['device'];
$hexadecimal = $_GET['data'];
//$Date=$_GET['time']; // temps sigfox

if ($device == "DEVICE") {
    $IdStationDec = 1;

// segmentation de la trame
// Conversion Hexa -> decimal

    $NiveauEauDec=decodageNiveauEau($hexadecimal);
    $CumulPluieDec=decodageCumulPluie($hexadecimal);
    $TauxChargeDec=decodageTauxCharge($hexadecimal);
    $Datetime=decodeDate($hexadecimal);
    MessageErreur($NiveauEauDec,$CumulPluieDec,$TauxChargeDec);
    majBdd($IdStationDec, $NiveauEauDec, $CumulPluieDec, $TauxChargeDec, $Datetime);    //appel de la fonction majBdd
    
}