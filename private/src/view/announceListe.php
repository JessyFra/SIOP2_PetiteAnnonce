<h1>Liste des Annonces</h1>
<p>Retrouvez ici la liste des annonces que vous pourriez vouloir</p>

<?php $categories = CategoryDAO::getAll() ?>

<?php foreach ($categories as $category) { ?>
    <?php $announces = $category->getAnnounces() ?>

    <section class="category">
        <h3><?php echo $category->getName() ?></h3>
        <div class="articles">

        <?php foreach ($announces as $announce) { ?>
            <?php

            $pathImage = "public/assets/img/".$announce->getId().".png";
            if (!file_exists($pathImage)) {
                $pathImage = "public/assets/default.png";
            }

            ?>
            <article id="<?php echo $announce->getId() ?>" class="announce">
                <div class="announce-picture">
                    <img class="picture" src="<?php echo $pathImage ?>"
                         alt="<?php echo $announce->getTitle() ?>"
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
