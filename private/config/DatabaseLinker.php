<?php

class DatabaseLinker {
    private static string $hostLocal = "mysql:host=localhost;dbname=202425_b2_jfrachisse;charset=utf8";
    private static string $loginLocal = "root";
    private static string $passwordLocal = "";

    private static ?PDO $bdd = null;

    public static function getConnexion(): PDO {
        if (self::$bdd == null) {
            self::$bdd = new PDO(self::$hostLocal, self::$loginLocal, self::$passwordLocal);
        }

        return self::$bdd;
    }
}
