<?php

// phpinfo(); // php v7.3
// middeware : http://195.221.61.190/station2022/decode.php?device={device}&data={data}&time={time}

$hexadecimal= "FF2710FF64";

// segmentation de la trame
$IdStation = substr($hexadecimal, 0, 2);    //255
$NiveauEau = substr($hexadecimal, 2, 4);    //10 000
$CumulePluie = substr($hexadecimal, 6, 2); //255
$TauxCharge = substr($hexadecimal, 8, 2); // 100

echo "Identifiant de la Station : ". intval($IdStation, 16);
echo "<br>";
echo "Niveau eau : ". intval($NiveauEau, 16);
echo "<br>";
echo "Cumule de la pluie: ". intval($CumulePluie, 16);
echo "<br>";
echo "Niveau de charge de la batterie : ". intval($TauxCharge, 16);

?>