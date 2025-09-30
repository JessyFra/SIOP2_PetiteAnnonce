<h1>Annonce</h1>

<?php

if (!empty($_GET["id"])) {
    $announceId = htmlspecialchars($_GET["id"], ENT_QUOTES);
} else {
    header("Location: index.php?page=annonces");
    exit;
}

$announce = AnnounceDAO::get($announceId);
echo $announceId;

?>
