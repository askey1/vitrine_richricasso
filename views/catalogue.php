<?php
// /C:/xampp/htdocs/vitrine_richricasso/views/catalogue.php
// Simple catalogue view: scans an images folder and displays a responsive gallery with captions and a lightbox.

// Configure relative image folder (try common locations)
$possibleDirs = [
    __DIR__ . '/../assets/images/catalogue',
    __DIR__ . '/../assets/images',
    __DIR__ . '/../../public/assets/images/catalogue',
    __DIR__ . '/images',
];
$imagesDir = null;
foreach ($possibleDirs as $d) {
    if (is_dir($d)) { $imagesDir = realpath($d); break; }
}

$images = [];
if ($imagesDir) {
    $files = glob($imagesDir . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    sort($files);
    foreach ($files as $f) $images[] = $f;
}

// Compute web-accessible base URL for images (best-effort)
$baseUrl = '';
if ($imagesDir && isset($_SERVER['DOCUMENT_ROOT'])) {
    $docRoot = realpath($_SERVER['DOCUMENT_ROOT']);
    $rel = str_replace('\\', '/', str_replace($docRoot, '', $imagesDir));
    $rel = '/' . ltrim($rel, '/');
    $baseUrl = $rel;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Catalogue</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
 <?php include __DIR__ . '/includes/header.php'; ?>
<body>
<h1>Catalogue</h1>

<?php if (!$imagesDir): ?>
    <div class="no-images">Images folder not found. Expected one of: <code><?= htmlspecialchars(implode(', ', $possibleDirs)) ?></code></div>
<?php elseif (empty($images)): ?>
    <div class="no-images">No images found in <code><?= htmlspecialchars($imagesDir) ?></code></div>
<?php else: ?>
    <div class="gallery" id="gallery">
        <?php foreach ($images as $imgPath):
            $file = basename($imgPath);
            // build caption from filename
            $name = pathinfo($file, PATHINFO_FILENAME);
            $caption = ucwords(str_replace(['-', '_'], ' ', $name));
            // build web src
            $src = $baseUrl ? ($baseUrl . '/' . rawurlencode($file)) : ('data:,' ); // fallback empty data uri (shouldn't happen)
        ?>
        <figure class="card" tabindex="0" data-src="<?= htmlspecialchars($src) ?>" data-caption="<?= htmlspecialchars($caption) ?>">
            <img class="thumb" src="<?= htmlspecialchars($src) ?>" alt="<?= htmlspecialchars($caption) ?>">
            <figcaption class="meta">
                <div class="title"><?= htmlspecialchars($caption) ?></div>
                <div class="filename"><?= htmlspecialchars($file) ?></div>
            </figcaption>
        </figure>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" role="dialog" aria-hidden="true">
    <span class="lb-close" id="lbClose" title="Close" aria-label="Close">&times;</span>
    <img id="lbImg" alt="">
</div>

<script>
(function(){
    const gallery = document.getElementById('gallery');
    const lightbox = document.getElementById('lightbox');
    const lbImg = document.getElementById('lbImg');
    const lbClose = document.getElementById('lbClose');

    function open(src, alt){
        lbImg.src = src;
        lbImg.alt = alt || '';
        lightbox.classList.add('open');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    function close(){
        lightbox.classList.remove('open');
        lightbox.setAttribute('aria-hidden', 'true');
        lbImg.src = '';
        document.body.style.overflow = '';
    }

    if (gallery){
        gallery.addEventListener('click', function(e){
            const card = e.target.closest('.card');
            if (!card) return;
            open(card.getAttribute('data-src'), card.getAttribute('data-caption'));
        });
        gallery.addEventListener('keydown', function(e){
            if (e.key === 'Enter' || e.key === ' ') {
                const card = e.target.closest('.card');
                if (card) { e.preventDefault(); open(card.getAttribute('data-src'), card.getAttribute('data-caption')); }
            }
        });
    }

    lbClose.addEventListener('click', close);
    lightbox.addEventListener('click', function(e){
        if (e.target === lightbox) close();
    });
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') close();
    });
})();
</script>
</body>
   <?php include __DIR__ . '/includes/footer.php'; ?>
</html>