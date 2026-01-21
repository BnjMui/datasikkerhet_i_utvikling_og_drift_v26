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
    <header>
        <a href="guest_hjemmeside.php">
            <div class="logo">StudiePortal</div>
        </a>

        <nav>
            <a href="?page=emne" class="<?php echo $currentPage === 'emne' ? 'active' :  ''; ?>">Emner</a>
            <a href="?page=meldinger" class="<?php echo $currentPage === 'meldinger' ?  'active' : ''; ?>">Meldinger</a>
        </nav>

        <div class="user-section">
            <span class="current-page"><?php echo ucfirst($currentPage); ?></span>

            <?php if (isset($_SESSION['user'])): ?>
                <!-- Innlogget bruker -->
                <div class="user-profile">
                    <span><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                </div>
                <a href="? logout=1" class="login-btn">Logg ut</a>
            <?php else: ?>
                <!-- Ikke innlogget -->
                <a href="login.php" class="login-btn">Logg inn</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main>
        <h1>Emneoversikt</h1>

        <div class="emne-liste">
            <?php foreach ($alleEmner as $emne): ?>
                <div class="emne-kort">
                    <a href="emne.php?kode=<?php echo urlencode($emne['kode']); ?>">
                        <span class="emne-kode"><?php echo htmlspecialchars($emne['kode']); ?></span>
                        <span class="emne-navn"><?php echo htmlspecialchars($emne['navn']); ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>
            Kontakt oss: <a href="mailto:kontakt@studieportal.no">kontakt@studieportal.no</a>
        </p>
        <p class="copyright">
            &copy; <?php echo date('Y'); ?> StudiePortal. Alle rettigheter reservert.
        </p>
    </footer>
</body>

</html>