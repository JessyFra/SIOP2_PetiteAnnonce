<h1>Liste des Annonces</h1>
<p>Retrouvez ici la liste des annonces que vous pourriez désirer</p>

<?php $categories = CategoryDAO::getAll() ?>

<?php foreach ($categories as $category) { ?>
    <?php $announces = $category->getAnnounces() ?>

    <section class="category">
        <h3><?php echo $category->getName() ?></h3>
        <div class="articles">

        <?php foreach ($announces as $announce) { ?>
            <article class="announce">
                <div class="announce-picture">
                    <img class="picture" src="public/assets/img/<?php echo $announce->getId() ?>.png"
                         alt="<?php echo $announce->getTitle() ?>"
                         onerror="this.onerror=null;this.src='public/assets/default.png'"
                    >
                </div>

                <div class="announce-description">
                    <h3><?php echo $announce->getTitle() ?></h3>
                    <p><?php echo $announce->getDescription() ?></p>
                    <p>Prix : <?php echo $announce->getPrice() ?> €</p>
                    <p>📍 <?php echo $announce->getCityName() ?></p>
                </div>
            </article>
        <?php } ?>
        </div>
    </section>
<?php } ?>