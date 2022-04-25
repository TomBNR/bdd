<?php
require_once 'fonctions.inc.php';
require_once 'bdd.php';

// phpinfo(); // php v7.3
// middeware : http://195.221.61.190/station2022/decode.php?device={device}&data={data}&time={time}

//FF2710FF64
$device=$_GET['device'];
$hexadecimal= $_GET['data'];
//$time=$_GET['time']; // temps sigfox
$date=date('Y-m-d H:i:s',time()); //time temps actuel 

// segmentation de la trame
$IdStation = substr($hexadecimal, 0, 2);    //255
$NiveauEau = substr($hexadecimal, 2, 4);    //10 000
$CumulPluie = substr($hexadecimal, 6, 2); //255
$TauxCharge = substr($hexadecimal, 8, 2); // 100


$IdStationDec=intval($IdStation, 16);
echo "Identifiant de la Station : $IdStationDec<br>";
$NiveauEauDec=intval($NiveauEau, 16);
echo "Niveau eau : $NiveauEauDec<br>"; 
$CumulPluieDec=intval($CumulPluie, 16);
echo "Cumul de la pluie: $CumulPluieDec<br>";
$TauxChargeDec=intval($TauxCharge, 16);
echo "Niveau de charge de la batterie : $TauxChargeDec<br>";
echo $date;
echo "<br>";
majBd($IdStationDec, $NiveauEauDec, $CumulPluieDec, $TauxChargeDec);
