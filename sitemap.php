<?php
declare(strict_types=1);

header('Content-Type: application/xml; charset=UTF-8');

$baseUrl = 'https://ynwebdesign.nl';
$excludedFiles = ['404.php', 'sitemap.php'];
$pages = [];

foreach (glob(__DIR__ . '/*.php') ?: [] as $filePath) {
    $fileName = basename($filePath);
    if (in_array($fileName, $excludedFiles, true)) {
        continue;
    }

    $location = $fileName === 'index.php'
        ? $baseUrl . '/'
        : $baseUrl . '/' . rawurlencode($fileName);

    $pages[] = [
        'location' => $location,
        'lastModified' => gmdate('Y-m-d', (int) filemtime($filePath)),
        'priority' => $fileName === 'index.php' ? '1.0' : '0.8',
    ];
}

usort($pages, static fn(array $a, array $b): int => strcmp($a['location'], $b['location']));

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $page): ?>
    <url>
        <loc><?= htmlspecialchars($page['location'], ENT_XML1, 'UTF-8') ?></loc>
        <lastmod><?= $page['lastModified'] ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority><?= $page['priority'] ?></priority>
    </url>
<?php endforeach; ?>
</urlset>
