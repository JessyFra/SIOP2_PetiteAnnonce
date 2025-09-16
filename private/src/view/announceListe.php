<h1>Liste des Annonces</h1>
<p>Ici s'affichera la liste des annonces.</p>

<p>En ce moment sur NomDuSite :</p>

<?php

require_once 'private/src/model/DTO/AnnounceDTO.php';

$announces = AnnounceDTO::getAllAnnounces();
var_dump($announces);

?>