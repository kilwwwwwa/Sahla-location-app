<?php
require 'db.php'; // Includes your PDO connection

// Fetch all publications
try {
    $stmt = $pdo->query("SELECT id, title, price, period, main_image, date_published FROM publications ORDER BY date_published DESC");
    $publications = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahla - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style-dashboard.css">
</head>
<body class="d-flex flex-column align-items-center">

    <header class="top-banner">
        <div class="banner-logo"><img src="./Assets/logo-sahla-webapp-text-bold.png" alt="sahla" height="130" width="130"></div>
    </header>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="green-text">Rent Publications</h2>
            <a href="add-publication.html" class="add-btn">
                <span>+</span> Add Publication
            </a>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (count($publications) > 0): ?>
                
                <?php foreach ($publications as $pub): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="pub-card h-100">
                        <div class="card-img-container">
                            <?php 
                                $img = !empty($pub['main_image']) ? "uploads/".$pub['main_image'] : "assets/placeholder.jpg";
                            ?>
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="Item">
                        </div>
                        <div class="card-body-custom d-flex flex-column">
                            <h5 class="pub-title"><?php echo htmlspecialchars($pub['title']); ?></h5>
                            <p class="pub-price"><?php echo htmlspecialchars($pub['price']); ?> DA / <?php echo htmlspecialchars($pub['period']); ?></p>
                            <p class="pub-date">Published: <?php echo date('M d, Y', strtotime($pub['date_published'])); ?></p>
                            <div class="mt-auto">
                                <a href="details.php?id=<?php echo $pub['id']; ?>" class="btn consult-btn w-100">Consult Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted fw-normal">There is no publications available for now</h4>
                    <p class="text-secondary">Be the first to add one by clicking the green button above!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="app-footer mt-auto">
        2026 &copy; Made With ❤️ By Sahla Team
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>