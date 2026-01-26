<?php
session_start();

// Inkluder emnedata
require_once 'emne_db.php';

// Simuler innlogget bruker (sett til null for å teste utlogget tilstand)
$_SESSION['user'] = [
    'name' => 'Ola Nordmann',
    'email' => 'ola@example.com',
];

// Hent nåværende side
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'emne';

// Hent alle emner fra data-filen
$alleEmner = hentAlleEmner();
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Min Side - <?php echo ucfirst($currentPage); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- HEADER -->
    <?php

    $currentPage = $_GET['page'] ?? 'emne'; // eller hva som passer

    include __DIR__ . '/header.php'; // trygg måte (absolutt sti)
    ?>

    <!-- MAIN CONTENT -->
    <main>
        <h1>Emneoversikt</h1>

        <nav aria-label="Emner">
            <ul class="emne-liste">
                <?php foreach ($alleEmner as $emne): ?>
                    <li class="emne-kort">
                        <a href="emne.php?kode=<?php echo urlencode($emne['kode']); ?>">
                            <span class="emne-kode"><?php echo htmlspecialchars($emne['kode']); ?></span>
                            <span class="emne-navn"><?php echo htmlspecialchars($emne['navn']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>