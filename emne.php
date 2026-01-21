<?php
session_start();

// Inkluder emnedata
require_once 'emne_db.php';

// Simuler innlogget bruker
$_SESSION['user'] = [
    'name' => 'Ola Nordmann',
    'email' => 'ola@example.com',
];

// Hent nåværende side
$currentPage = 'emne';

// Hent emnekode fra URL og finn emnet
$emneKode = isset($_GET['kode']) ? $_GET['kode'] : '';
$emne = finnEmne($emneKode);

// PIN-kode verifisering
$pinFeil = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pin'])) {
    if (sjekkEmnePin($emneKode, $_POST['pin'])) {
        // Redirect til meldinger-siden
        header("Location: guest_meldinger.php?kode=" . urlencode($emneKode));
        exit;
    } else {
        $pinFeil = true;
    }
}
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Min Side - <?php echo $emne ?  htmlspecialchars($emne['kode']) : 'Emne ikke funnet'; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <a href="guest_hjemmeside.php">
            <div class="logo">StudiePortal</div>
        </a>

        <nav>
            <a href="guest_hjemmeside.php" class="<?php echo $currentPage === 'emne' ? 'active' : ''; ?>">Emner</a>
            <a href="? page=meldinger" class="<?php echo $currentPage === 'meldinger' ?  'active' : ''; ?>">Meldinger</a>
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

    <main>
        <?php if ($emne): ?>
            <!-- Vis valgt emne øverst -->
            <div class="emne-header">
                <span class="emne-kode"><?php echo htmlspecialchars(strtoupper($emne['kode'])); ?></span>
                <h1><?php echo htmlspecialchars($emne['navn']); ?></h1>
            </div>

            <!-- PIN-kode seksjon -->
            <div class="pin-section">
                <h2>🔒 Skriv inn PIN-kode</h2>
                <p>Skriv inn PIN-koden for å få tilgang til emneinnholdet</p>

                <form method="POST">
                    <input
                        type="password"
                        name="pin"
                        class="pin-input"
                        maxlength="4"
                        placeholder="••••"
                        pattern="[0-9]{4}"
                        required
                        autofocus>
                    <br>
                    <button type="submit" class="pin-btn">Gå videre</button>

                    <?php if ($pinFeil): ?>
                        <p class="error-message">❌ Feil PIN-kode. Prøv igjen.</p>
                    <?php endif; ?>
                </form>
            </div>
        <?php else: ?>
            <div class="pin-section">
                <h2>Emne ikke funnet</h2>
                <p>Beklager, vi fant ikke emnet du leter etter. </p>
                <a href="guest_hjemmeside.php" class="back-link">← Tilbake til emneoversikt</a>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>