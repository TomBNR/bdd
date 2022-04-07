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
        echo "Connext > OK !";
        return $bdd;
        //si erreur on tue le processus et on affiche le message d'erreur    
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}

function getCallbacks() {
    try {
        // connexion BDD
        $bdd = connexionBdd();

        $requete = $bdd->query("SELECT IdStation, NiveauEau, CumulePluie, TauxCharge, TensionBatterie, AmperageBatterie FROM Mesures;") ;

        $tabTableau = array();

        while ($ligne = $requete->fetch()) {
            array_push($tabTableau, array(
                $ligne['IdStation'],
                $ligne['NiveauEau'],
                $ligne['CumulePluie'],
                $ligne['TauxCharge'],
                $ligne['TensionBatterie']));
        }

        $requete->close();
        return $tabTableau;
    } catch (PDOException $e) {
        print "Erreur : " . $e->getMessage() . "<br/>";
        die();
    }
}
