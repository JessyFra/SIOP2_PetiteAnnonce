<?php

class DateHelper
{
    /**
     * Convertit une date en format relatif (il y a X temps)
     * @param string $datetime Date au format SQL (YYYY-MM-DD HH:MM:SS)
     * @return string Format relatif de la date
     */
    public static function getRelativeTime($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        // Secondes
        if ($diff < 60) {
            return "À l'instant";
        }

        // Minutes
        if ($diff < 3600) {
            $minutes = floor($diff / 60);
            return "Il y a " . $minutes . " minute" . ($minutes > 1 ? 's' : '');
        }

        // Heures
        if ($diff < 86400) {
            $hours = floor($diff / 3600);
            return "Il y a " . $hours . " heure" . ($hours > 1 ? 's' : '');
        }

        // Jours
        if ($diff < 604800) {
            $days = floor($diff / 86400);
            if ($days == 1) {
                return "Hier";
            }
            return "Il y a " . $days . " jours";
        }

        // Semaines
        if ($diff < 2592000) {
            $weeks = floor($diff / 604800);
            return "Il y a " . $weeks . " semaine" . ($weeks > 1 ? 's' : '');
        }

        // Mois
        if ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return "Il y a " . $months . " mois";
        }

        // Années
        $years = floor($diff / 31536000);
        return "Il y a " . $years . " an" . ($years > 1 ? 's' : '');
    }

    /**
     * Formate une date au format français
     * @param string $datetime Date au format SQL
     * @param bool $withTime Inclure l'heure
     * @return string Date formatée
     */
    public static function formatFrench($datetime, $withTime = true)
    {
        $timestamp = strtotime($datetime);

        if ($withTime) {
            return date('d/m/Y à H:i', $timestamp);
        }

        return date('d/m/Y', $timestamp);
    }

    /**
     * Retourne le format complet avec date relative et date exacte
     * @param string $datetime Date au format SQL
     * @return string Format complet
     */
    public static function getFullFormat($datetime)
    {
        $relative = self::getRelativeTime($datetime);
        $exact = self::formatFrench($datetime);

        return $relative . " (" . $exact . ")";
    }
}
