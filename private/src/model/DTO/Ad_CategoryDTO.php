<?php

class Ad_CategoryDTO {
    
    private $ad_id;
    private $category_id;

    public function getAdId() {
        return $this->ad_id;
    }

    public function setAdId($ad_id) {
        $this->ad_id = $ad_id;
    }

    public function getCategoryId() {
        return $this->category_id;
    }

    public function setCategoryId($category_id) {
        $this->category_id = $category_id;
    }

}
