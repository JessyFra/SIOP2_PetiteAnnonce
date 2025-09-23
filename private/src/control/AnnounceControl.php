<?php

include_once 'private/src/model/DAO/AnnounceDAO.php';
include_once 'private/src/model/DAO/CategoryDAO.php';

class AnnounceControl {
    public function annonce() {
        // Vue de la liste des annonces
        include_once 'private/src/view/announceListe.php';

        $categories = CategoryDAO::getAll();
        foreach ($categories as $category) {
            $annonces = $category->getAnnounces();

            echo "<br><h3>".$category->getName()."</h3>";
            echo "<div class='announces'>";
            foreach ($annonces as $annonce) {
                echo "<section class='announce'>";
                echo "<div class='announce-picture'>";
                echo "<img class='picture' src='public/assets/img/".$annonce->getId().".png' alt=" . $annonce->getTitle() .">";
                echo "</div>";
                echo "<div class='announce-description'>";
                echo "<h3>".$annonce->getTitle()."</h3>";
                echo "<p>".$annonce->getDescription()."</p>";
                echo "<p>".$annonce->getPrice()."</p>";
                echo "<p>".$annonce->getCityName()."</p>";
                echo "</div>";
                echo "</section>";
            }
            echo "</div>";
        }
    }
}
