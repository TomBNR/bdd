<?php
require_once 'fonctions.inc.php';

// phpinfo(); // php v7.3
// middeware : http://195.221.61.190/station2022/decode.php?device={device}&data={data}&time={time}

//FF2710FF64
$device=$_GET['device'];
$hexadecimal= $_GET['data'];
//$Date=$_GET['time']; // temps sigfox
//$date=date('Y-m-d H:i:s',time()); //time temps actuel

if ($device=="C50EE6"){
// segmentation de la trame
//$IdStation = substr($hexadecimal, 0, 2);    //255
$NiveauEau = substr($hexadecimal, 0, 4);    //10 000
$CumulPluie = substr($hexadecimal, 6, 2); //255
$TauxCharge = substr($hexadecimal, 8, 2); // 100
$Date = substr($hexadecimal, 10, 8); // 100

// Conversion Hexa -> decimal
//$IdStationDec=intval($IdStation, 16);
//echo "Identifiant de la Station : $IdStationDec<br>";
$NiveauEauDec=intval($NiveauEau, 16);
//echo "Niveau eau : $NiveauEauDec<br>"; 
$CumulPluieDec=intval($CumulPluie, 16);
//echo "Cumul de la pluie: $CumulPluieDec<br>";
$TauxChargeDec=intval($TauxCharge, 16);
//echo "Niveau de charge de la batterie : $TauxChargeDec<br>";
//echo $date;
//echo "<br>";

 $IdStationDec=1;
$DateDec= base_convert($Date,16 ,10); // conversion Hexa -> décimal
$Datetime=date('Y-m-d H:i:s',$DateDec); // conversion timestamp en datetime
//echo $Datetime;

majBdd($IdStationDec, $NiveauEauDec, $CumulPluieDec, $TauxChargeDec, $Datetime);
} else {
    echo "le device $device invailide";
}
