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
        echo "Connexion > OK !";
        return $bdd;
        //si erreur on tue le processus et on affiche le message d'erreur    
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
        die();
    }
}

function majBd($IdStation, $NiveauEau, $CumulePluie, $TauxCharge, $Date) {
    try {
        // connexion BDD
        $bdd = connexionBdd();
        // execution de la requete
        $requete = $bdd->prepare("INSERT INTO Mesures (IdStation, NiveauEau, CumulePluie, TauxCharge, Date) VALUES (:IdStation,:NiveauEau,:CumulePluie,:TauxCharge,:Date)") ;

        $tabTableau = array();

        while ($ligne = $requete->fetch()) {
            array_push($tabTableau, array(
                $ligne->$requete['IdStation']=':IdStation',
                $ligne['NiveauEau'],
                $ligne['CumulePluie'],
                $ligne['TauxCharge'],
                $ligne['Date']));
        }

        $requete->closeCursor();
        return $tabTableau;
    } catch (PDOException $e) {
        print "Erreur : " . $e->getMessage() . "<br/>";
        die();
    }
}
