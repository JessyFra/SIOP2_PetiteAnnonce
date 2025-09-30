<?php

include_once 'private/src/model/DAO/AnnounceDAO.php';
include_once 'private/src/model/DAO/CategoryDAO.php';

class AnnounceControl {
    public function announces() {
        // Vue de la liste des annonces
        include_once 'private/src/view/announceListe.php';

        $categories = CategoryDAO::getAll();

        foreach ($categories as $category) {
            $announces = $category->getAnnounces();

            echo "<section class='category'>";
                echo "<h3>".$category->getName()."</h3>";
                echo "<div class='articles'>";

                foreach ($announces as $announce) {
                        echo "<article class='announce'>";
                            echo "<div class='announce-picture'>";
                                echo "<img class='picture' 
                                    src='public/assets/img/".$announce->getId().".png' 
                                    alt='".$announce->getTitle()."' 
                                    onerror=\"this.onerror=null;this.src='public/assets/default.png'\">";
                            echo "</div>";
                                echo "<div class='announce-description'>";
                                echo "<h3>".$announce->getTitle()."</h3>";
                                echo "<p>".$announce->getDescription()."</p>";
                                echo "<p> Prix : ".$announce->getPrice()." €</p>";
                                echo "<p> 📍 ".$announce->getCityName()."</p>";
                                echo "</div>";
                        echo "</article>";
                }
                echo "</div>";
            echo "</section>";
        }
    }

    public function announce() {

    }
}
