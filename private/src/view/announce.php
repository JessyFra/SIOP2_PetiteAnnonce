<?php

if (!empty($_GET["id"])) {
    $announceId = htmlspecialchars($_GET["id"], ENT_QUOTES);
} else {
    header("Location: index.php?page=annonces");
    exit;
}

$announce = AnnounceDAO::get($announceId);

?>


<section class="announce row">
    <article class="picture col-6">
        <img src="public/assets/img/<?php echo $announce->getId() ?>.png"
             alt="<?php echo $announce->getTitle() ?>"
             onerror="this.onerror=null;this.src='public/assets/default.png'"
        >
    </article>

    <article class="purchase black-border col-4">
        <h5><?php echo $announce->getTitle() ?></h5>
        <button class="btn btn-secondary">Acheter</button>
        <button class="btn btn-primary">Contacter</button>
    </article>
</section>

<div class="details black-border col-12 mt-3">
    <p>Prix : <?php echo $announce->getPrice() ?> €</p>
    <p>📍 <?php echo $announce->getCityName() ?></p>
    <p><?php echo $announce->getDescription() ?></p>
</div>

