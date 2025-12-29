<?php
require 'db.php';
$id = $_GET['id'];

// 1. Get Publication + User Info
$stmt = $pdo->prepare("SELECT p.*, u.fullname FROM publications p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

// 2. Get Gallery Images
$gallery_stmt = $pdo->prepare("SELECT image_path FROM gallery_images WHERE pub_id = ?");
$gallery_stmt->execute([$id]);
$images = $gallery_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sahla - <?php echo htmlspecialchars($item['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style-details.css">
</head>
<body>

    <header class="top-banner">
        <div class="banner-logo"><img src="./Assets/logo-sahla-webapp-text-bold.png" alt="sahla" height="180" width="180"></div>
    </header>

    <div class="container details-container">
        <a href="dashboard.php" class="btn btn-outline-secondary mb-4">← Back to Dashboard</a>
        
        <div class="row g-5d">
            <div class="col-lg-8">
                <div id="publicationCarousel" class="carousel slide shadow-sm mb-4" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#publicationCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                        <?php for($i = 1; $i <= count($images); $i++): ?>
                            <button type="button" data-bs-target="#publicationCarousel" data-bs-slide-to="<?php echo $i; ?>"></button>
                        <?php endfor; ?>
                    </div>

                    <div class="carousel-inner border-radius-lg">
                        <div class="carousel-item active">
                            <img src="uploads/<?php echo htmlspecialchars($item['main_image']); ?>" class="d-block w-100 main-img-large" alt="Main Image">
                        </div>

                        <?php foreach($images as $img): ?>
                        <div class="carousel-item">
                            <img src="uploads/<?php echo htmlspecialchars($img['image_path']); ?>" class="d-block w-100 main-img-large" alt="Gallery Image">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#publicationCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#publicationCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <h1 class="green-text"><?php echo htmlspecialchars($item['title']); ?></h1>
                <div class="mt-4 p-4 bg-light rounded shadow-sm">
                <h4 class="green-text border-bottom pb-2">Description</h4>
                <p class="lead mt-3" style="white-space: pre-line; color: #555;">
                    <?php 
                        // htmlspecialchars for security 
                        // nl2br to keep your line breaks (paragraphs)
                        echo nl2br(htmlspecialchars($item['description'])); 
                    ?>
                </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="contact-box shadow">
                    <h5 class="mb-3">Rental Price</h5>
                    <div class="badge-price mb-4 text-center">
                        <?php echo htmlspecialchars($item['price']); ?> DA / <?php echo htmlspecialchars($item['period']); ?>
                    </div>
                    
                    <h5 class="mb-2">Contact Owner</h5>
                    <p class="mb-1"><i class="fas fa-phone"></i> Phone:</p>
                    <a href="tel:<?php echo $item['phone']; ?>" class="btn btn-light w-100 fw-bold mb-3"><?php echo htmlspecialchars($item['phone']); ?></a>
                    
                    <p class="mb-1">Location:</p>
                    <p class="fw-bold"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($item['address']); ?></p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateImage(src) {
            document.getElementById('mainDisplay').src = src;
        } 
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>