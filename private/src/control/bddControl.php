<?php

class bddControl {
    private static string $hostLocal = "mysql:host=localhost;dbname=SIOP2_PetiteAnnonce;charset=utf8";
    private static string $loginLocal = "root";
    private static string $passwordLocal = "";


    private static $connex;

    public static function getConnexion(): PDO {
        if (self::$connex == null) {
            self::$connex = new PDO(self::$hostLocal, self::$loginLocal, self::$passwordLocal);
        }

        return self::$connex;
    }
}
