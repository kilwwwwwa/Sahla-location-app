<?php
require 'db.php';

// Fetch all publications
try {
    $stmt = $pdo->query("SELECT id, title, description, price, period, main_image, date_published FROM publications ORDER BY date_published DESC");
    $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Create XML document
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><inventory></inventory>');
$xml->addAttribute('export_date', date('Y-m-d H:i:s'));
$xml->addAttribute('total_items', count($publications));

// Add each publication to XML
foreach ($publications as $pub) {
    $publication = $xml->addChild('publication');
    $publication->addAttribute('id', $pub['id']);
    
    $publication->addChild('title', htmlspecialchars($pub['title']));
    $publication->addChild('description', htmlspecialchars($pub['description'] ?? ''));
    $publication->addChild('price', htmlspecialchars($pub['price']));
    $publication->addChild('period', htmlspecialchars($pub['period']));
    $publication->addChild('main_image', htmlspecialchars($pub['main_image'] ?? ''));
    $publication->addChild('date_published', $pub['date_published']);
}

// Set headers for file download
header('Content-Type: application/xml; charset=utf-8');
header('Content-Disposition: attachment; filename="publications_' . date('Y-m-d_His') . '.xml"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Output the XML
echo $xml->asXML();
exit;
?>
