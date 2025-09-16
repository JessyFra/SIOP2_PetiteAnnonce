<h1>Liste des Annonces</h1>
<p>Ici s'affichera la liste des annonces.</p>

<?php
require_once 'private/src/control/bddControl.php';

$bdd = bddControl::getConnexion();
$query = $bdd->query("SELECT * FROM ad");
$ads = $query->fetchAll();

?>