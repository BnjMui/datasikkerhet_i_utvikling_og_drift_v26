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
    <!-- HEADER -->
    <?php

    $currentPage = $_GET['page'] ?? 'emne'; // eller hva som passer

    include __DIR__ . '/header.php'; // trygg måte (absolutt sti)
    ?>

    <main>
        <?php if ($emne): ?>
            <!-- Toppinfo om valgt emne -->
            <section class="emne-header">
                <p class="emne-kode">
                    <?php echo htmlspecialchars(strtoupper($emne['kode'])); ?>
                </p>
                <h1><?php echo htmlspecialchars($emne['navn']); ?></h1>
            </section>

            <!-- PIN-kode / tilgang -->
            <section class="pin-section" aria-labelledby="pin-title">
                <h2 id="pin-title">🔒 Skriv inn PIN-kode</h2>
                <p>Skriv inn PIN-koden for å få tilgang til emneinnholdet</p>

                <form method="POST">
                    <label for="pin" class="visually-hidden">PIN-kode</label>
                    <input
                        id="pin"
                        type="password"
                        name="pin"
                        class="pin-input"
                        maxlength="4"
                        placeholder="••••"
                        pattern="[0-9]{4}"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        required
                        autofocus>

                    <button type="submit" class="pin-btn">Gå videre</button>

                    <?php if ($pinFeil): ?>
                        <p class="error-message" role="alert">❌ Feil PIN-kode. Prøv igjen.</p>
                    <?php endif; ?>
                </form>
            </section>
        <?php else: ?>
            <section class="pin-section" aria-labelledby="not-found-title">
                <h1 id="not-found-title">Emne ikke funnet</h1>
                <p>Beklager, vi fant ikke emnet du leter etter.</p>
                <p>
                    <a href="guest_hjemmeside.php" class="back-link">← Tilbake til emneoversikt</a>
                </p>
            </section>
        <?php endif; ?>
    </main>
    <?php include 'footer.php'; ?>
</body>

</html>