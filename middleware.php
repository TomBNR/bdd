<?php

require_once 'fonctions.inc.php';

// phpinfo(); // php v7.3
// middeware : http://195.221.61.190/station2022/decode.php?device={device}&data={data}&time={time}
//FF2710FF64
$device = $_GET['device'];
$hexadecimal = $_GET['data'];
//$Date=$_GET['time']; // temps sigfox
//$date=date('Y-m-d H:i:s',time()); //time temps actuel


/*
  $NiveauEau = substr($hexadecimal, 0, 4);    //10 000
  $CumulPluie = substr($hexadecimal, 6, 2);   //255
  $TauxCharge = substr($hexadecimal, 8, 2);   // 100
  $Date = substr($hexadecimal, 10, 8);

  $NiveauEauDec = intval($NiveauEau, 16);
  //echo "Niveau eau : $NiveauEauDec<br>";
  $CumulPluieDec = intval($CumulPluie, 16);
  //echo "Cumul de la pluie: $CumulPluieDec<br>";
  $TauxChargeDec = intval($TauxCharge, 16);
  //echo "Niveau de charge de la batterie : $TauxChargeDec<br>";
  //echo $date;
  //echo "<br>"
 */


if ($device == "C50EE6") {
    $IdStationDec = 1;

// segmentation de la trame
// Conversion Hexa -> decimal

    $NiveauEauDec=decodageNiveauEau($hexadecimal);
    $CumulPluieDec=decodageCumulPluie($hexadecimal);
    $TauxChargeDec=decodageTauxCharge($hexadecimal);
    $Datetime=decodeDate($hexadecimal);
    
    MessageErreur($NiveauEauDec,$CumulPluieDec,$TauxChargeDec);
    
    
    majBdd($IdStationDec, $NiveauEauDec, $CumulPluieDec, $TauxChargeDec, $Datetime);    //appel de la fonction majBdd
    
   
    
} else {
    echo "le device $device est invalide";
}
