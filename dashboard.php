<?php
session_start();
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all publications
try {
    $stmt = $pdo->query("SELECT id, title, price, period, main_image, date_published FROM publications ORDER BY date_published DESC");
    $all_publications = $stmt->fetchAll();
    
    // Fetch user's own publications
    $stmt = $pdo->prepare("SELECT id, title, price, period, main_image, date_published FROM publications WHERE user_id = ? ORDER BY date_published DESC");
    $stmt->execute([$user_id]);
    $user_publications = $stmt->fetchAll();
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="style-dashboard.css">
    </head>
    <body class="d-flex flex-column align-items-center">

        <header class="top-banner d-flex justify-content-around align-items-center">
            <div class="banner-logo"><img src="./Assets/logo-sahla-webapp-text-bold.png" alt="sahla" height="130" width="130"></div>
            <a href="logout.php" class="btn btn-danger rounded-pill">Logout</a>
        </header>
        <button id="toggleFilters" class="btn btn-secondary mb-3 rounded-pill">Hide Filters</button>
        <div class="filter-container shadow-sm p-4 mb-5 bg-white rounded">
            <div class="row align-items-end g-3">
                <div class="col-md-5">
                    <label class="form-label fw-bold green-text mb-2">
                        <i class="fas fa-search me-2"></i>Filter By Search:
                    </label>
                    <div class="input-group custom-input-group">
                        <span class="input-group-text">Search</span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by title...">
                    </div>
                </div>
                <div class="col-md-7">
                    <label class="form-label fw-bold green-text mb-2">
                        <i class="fas fa-money-bill-wave me-2"></i>Filter By Price (DA):
                    </label>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="input-group custom-input-group">
                                <span class="input-group-text">Min DA</span>
                                <input type="number" id="minPrice" class="form-control" placeholder="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group custom-input-group">
                                <span class="input-group-text">Max DA</span>
                                <input type="number" id="maxPrice" class="form-control" placeholder="Any">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div class="d-flex align-items-center gap-3">
                    <h2 class="green-text mb-0">Rent Publications</h2>
                    <button id="viewToggle" class="btn btn-outline-success rounded-pill" data-view="all">
                        <i class="fas fa-list me-2"></i>Mes Publications
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <a href="export-xml.php" class="btn btn-dark rounded-pill text-center">Export to XML</a>
                    <a href="add-publication.html" class="add-btn">
                        <span>+</span> Add Publication
                    </a>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <?php if (count($all_publications) > 0): ?>
                    <?php foreach ($all_publications as $pub): ?>
                        <div class="col-md-4 searchable-item col-sm-6" data-user-pub="false">
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

            <div class="row g-4 justify-content-center" id="userPublications" style="display: none;">
                <?php if (count($user_publications) > 0): ?>
                    <?php foreach ($user_publications as $pub): ?>
                        <div class="col-md-4 searchable-item col-sm-6" data-user-pub="true">
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
                        <h4 class="text-muted fw-normal">You have no publications yet</h4>
                        <p class="text-secondary">Create your first publication by clicking the green button above!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <footer class="app-footer mt-auto">
            2026 &copy; Made With ❤️ By Sahla Team
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            $(document).ready(function(){
                let currentView = "all"; // Track current view

                // Toggle between All Publications and Mes Publications
                $("#viewToggle").click(function() {
                    currentView = currentView === "all" ? "user" : "all";
                    
                    if (currentView === "all") {
                        $(".row.g-4").show();
                        $("#userPublications").hide();
                        $(this).removeClass("active");
                        $(this).html('<i class="fas fa-list me-2"></i>Mes Publications');
                    } else {
                        $(".row.g-4").hide();
                        $("#userPublications").show();
                        $(this).addClass("active");
                        $(this).html('<i class="fas fa-list me-2"></i>All Publications');
                    }
                    
                    // Clear filters when switching views
                    $("#searchInput").val("");
                    $("#minPrice").val("");
                    $("#maxPrice").val("");
                    
                    // Reapply filters
                    applyFilters();
                });

                // Toggle filters
                $("#toggleFilters").click(function() {
                    $(".filter-container").toggle();
                    $(this).text($(this).text() === "Hide Filters" ? "Show Filters" : "Hide Filters");
                });

                // Trigger filter when typing in search OR price boxes
                $("#searchInput, #minPrice, #maxPrice").on("keyup change", function() {
                    applyFilters();
                });

                function applyFilters() {
                    // 1. Get all filter values
                    var searchTerm = $("#searchInput").val().toLowerCase().trim();
                    var min = parseFloat($("#minPrice").val()) || 0;
                    var max = parseFloat($("#maxPrice").val()) || Infinity;

                    // Select the visible row container
                    var containerSelector = currentView === "all" ? ".row.g-4:visible" : "#userPublications:visible .row.g-4";
                    
                    $(containerSelector + " .searchable-item").each(function() {
                        // 2. Get the Title
                        var titleText = $(this).find(".pub-title").text().toLowerCase().trim();
                        var words = titleText.split(/\s+/);

                        // 3. Get the Price (extract only the number from "500 DA / day")
                        var priceText = $(this).find(".pub-price").text();
                        var itemPrice = parseFloat(priceText.replace(/[^0-9.]/g, ''));

                        // 4. Check Logic:
                        // Does it match the title search?
                        var titleMatches = searchTerm === "" || titleText.startsWith(searchTerm) || words.some(word => word.startsWith(searchTerm));
                        
                        // Is it within the price range?
                        var priceMatches = (itemPrice >= min && itemPrice <= max);

                        // 5. Toggle visibility (Item must pass BOTH tests)
                        $(this).toggle(titleMatches && priceMatches);
                    });

                    // "No Results" message handling
                    handleNoResults();
                }

                function handleNoResults() {
                    if($(".searchable-item:visible").length === 0) {
                        if($("#noResultsMsg").length === 0) {
                            $(".row.g-4:visible, #userPublications:visible .row.g-4").append('<div id="noResultsMsg" class="col-12 text-center py-5"><h4 class="text-muted">No items match your criteria.</h4></div>');
                        }
                    } else {
                        $("#noResultsMsg").remove();
                    }
                }
            });
        </script>
    </body>
</html>