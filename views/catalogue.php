<?php
// GitHub Copilot
// File: /C:/xampp/htdocs/vitrine_richricasso/views/catalogue.php

// - Inclure le header si présent (plusieurs emplacements courants)
$possibleHeaders = [
    __DIR__ . '/../header.php',
    __DIR__ . '/header.php',
    __DIR__ . '/../views/header.php',
    __DIR__ . '/../partials/header.php',
];


// Trouver tous les fichiers .webp dans un ou plusieurs dossiers (configurable)
$searchPaths = [
    __DIR__ . '/../images',
    __DIR__ . '/../assets/images',
    __DIR__ . '/images',
    __DIR__ . '/../public/images',
    __DIR__ . '/../uploads',
    __DIR__ . '/..',
];

$found = [];
function findWebpRecursive($dir, &$out) {
    if (!is_dir($dir)) return;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if ($file->isFile() && strtolower($file->getExtension()) === 'webp') {
            $out[] = $file->getRealPath();
        }
    }
}

foreach ($searchPaths as $p) {
    if (is_dir($p)) {
        findWebpRecursive($p, $found);
    }
}

// Déduire le chemin web depuis le chemin système (DocumentRoot)
$docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: null;
$images = [];
foreach (array_unique($found) as $f) {
    $real = realpath($f);
    if ($docRoot && strpos($real, $docRoot) === 0) {
        $web = '/' . ltrim(str_replace('\\', '/', substr($real, strlen($docRoot))), '/');
    } else {
        // fallback : chemin relatif depuis le dossier views (peut nécessiter ajustement)
        $web = str_replace('\\', '/', substr($real, strlen(realpath(__DIR__ . '/..'))));
        $web = '/' . ltrim($web, '/');
    }
    $images[] = htmlspecialchars($web, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Catalogue d'images (.webp)</title>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 1rem; background:#f5f5f5; }
        .catalogue { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: 12px; }
        .card { background: white; padding: 8px; border-radius: 6px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); text-align:center; }
        .card img { max-width:100%; height: auto; display:block; margin: 0 auto 8px; }
        .empty { padding: 2rem; text-align:center; color:#666; }
        header.small-note { margin-bottom: 1rem; }
    </style>
</head>
<body>
<?php if (! $headerIncluded): ?>
    <header class="small-note"><h1>Catalogue</h1></header>
<?php endif; ?>

<main>
    <?php if (count($images) === 0): ?>
        <div class="empty">
            <p>Aucune image .webp trouvée dans les chemins recherchés. Vérifiez l'emplacement des images ou ajustez les chemins dans views/catalogue.php.</p>
            <p>Chemins recherchés (serveur) :</p>
            <ul>
                <?php foreach ($searchPaths as $p): ?>
                    <li><?= htmlspecialchars($p, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="catalogue">
            <?php foreach ($images as $img): ?>
                <div class="card">
                    <a href="<?= $img ?>" target="_blank" rel="noopener">
                        <img src="<?= $img ?>" alt="Image catalogue">
                    </a>
                    <div style="font-size:.9rem;color:#444;word-break:break-all;"><?= basename($img) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

</body>
</html>