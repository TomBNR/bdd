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

function majBdd($IdStation, $NiveauEau, $CumulPluie, $TauxCharge, $Date) {
    try {
        // connexion BDD
        $bdd = connexionBdd();
        // execution de la requete
        $requete = $bdd->prepare("INSERT INTO Mesures (IdStation, NiveauEau, CumulPluie, TauxCharge, Date) VALUES (:IdStation,:NiveauEau,:CumulPluie,:TauxCharge,:Date)") ;
        $requete->bindParam(":IdStation", $IdStation);
        $requete->bindParam(":NiveauEau", $NiveauEau);
        $requete->bindParam(":CumulPluie", $CumulPluie);
        $requete->bindParam(":TauxCharge", $TauxCharge);
        $requete->bindParam(":Date", $Date);
        $requete->execute();

    } catch (PDOException $e) {
        print "Erreur : " . $e->getMessage() . "<br/>";
        die();
    }
}

