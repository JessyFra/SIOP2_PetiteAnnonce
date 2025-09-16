<?php

class userControl
{
    public function connexion()
    {
        // Vue du formulaire de connexion
        include_once 'private/src/view/connexionForm.php';
    }

    public function inscription()
    {
        // Vue du formulaire d'inscription
        include_once 'private/src/view/inscriptionForm.php';
    }
}
