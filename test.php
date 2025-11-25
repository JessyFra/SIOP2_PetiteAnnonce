<?php
$uploadDir = 'public/assets/img/';

echo "<h2>Diagnostic des permissions</h2>";

// 1. Le dossier existe-t-il ?
echo "<p><strong>Dossier existe :</strong> " . (is_dir($uploadDir) ? "✅ OUI" : "❌ NON") . "</p>";

// 2. Permissions du dossier
if (is_dir($uploadDir)) {
    $perms = substr(sprintf('%o', fileperms($uploadDir)), -4);
    echo "<p><strong>Permissions actuelles :</strong> $perms</p>";

    // 3. Est-il accessible en écriture ?
    echo "<p><strong>Accessible en écriture :</strong> " . (is_writable($uploadDir) ? "✅ OUI" : "❌ NON") . "</p>";

    // 4. Propriétaire et groupe
    $stat = stat($uploadDir);
    $owner = posix_getpwuid($stat['uid']);
    $group = posix_getgrgid($stat['gid']);
    echo "<p><strong>Propriétaire :</strong> " . $owner['name'] . "</p>";
    echo "<p><strong>Groupe :</strong> " . $group['name'] . "</p>";
}

// 5. Utilisateur du serveur web
$currentUser = posix_getpwuid(posix_geteuid());
echo "<p><strong>Utilisateur PHP/Apache :</strong> " . $currentUser['name'] . "</p>";

// 6. Chemin absolu
echo "<p><strong>Chemin absolu :</strong> " . realpath($uploadDir) . "</p>";

// 7. Test de création de fichier
$testFile = $uploadDir . 'test_' . time() . '.txt';
if (@file_put_contents($testFile, 'test')) {
    echo "<p><strong>Test d'écriture :</strong> ✅ SUCCÈS</p>";
    @unlink($testFile);
} else {
    echo "<p><strong>Test d'écriture :</strong> ❌ ÉCHEC</p>";
    $error = error_get_last();
    echo "<p style='color:red'><strong>Erreur :</strong> " . ($error['message'] ?? 'Inconnue') . "</p>";
}
