<?php
session_start();

// Inkluder emnedata og brukerdata
require_once '../emne_db.php';
require_once '../bruker_db.php';

// Hent brukerinfo fra session
$bruker = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$rolle = ($bruker && isset($bruker['rolle'])) ? $bruker['rolle'] : 'guest';

// Hent nåværende side
$currentPage = 'emne';

// Hent emnekode fra URL og finn emnet
$emneKode = isset($_GET['kode']) ? $_GET['kode'] : '';
$emne = finnEmne($emneKode);

// Hvis bruker er innlogget (student eller foreleser), redirect direkte til meldinger
$ingenTilgang = false;
if ($bruker && $rolle !== 'guest' && $emne) {
    // Foreleser må ha tilgang til emnet
    if ($rolle === 'foreleser' && !harForeleserTilgang($emneKode, $bruker['email'])) {
        $ingenTilgang = true;
    } else {
        // Student eller foreleser med tilgang - redirect til meldinger
        header("Location: guest_meldinger.php?kode=" . urlencode($emneKode));
        exit;
    }
}

// PIN-kode verifisering (kun for guest)
$pinFeil = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pin'])) {
    if (sjekkEmnePin($emneKode, $_POST['pin'])) {
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
    <title><?php echo $emne ? htmlspecialchars($emne['kode']) : 'Emne ikke funnet'; ?> - Emneportal</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <?php include __DIR__ . '/../header.php'; ?>

    <main>
        <?php if ($ingenTilgang): ?>
            <article class="feilmelding-container">
                <header>
                    <h1>
                        <span aria-hidden="true"></span> Ingen tilgang
                    </h1>
                </header>
                <p>Du har ikke tilgang til dette emnet som foreleser.</p>
                <nav aria-label="Navigasjon">
                    <a href="guest_hjemmeside.php" class="back-link">
                        <span aria-hidden="true">←</span> Tilbake til emneoversikt
                    </a>
                </nav>
            </article>

        <?php elseif ($emne): ?>
            <article class="emne-container">
                <header class="emne-header">
                    <p class="emne-kode"><?php echo htmlspecialchars(strtoupper($emne['kode'])); ?></p>
                    <h1><?php echo htmlspecialchars($emne['navn']); ?></h1>
                </header>

                <section class="pin-section" aria-labelledby="pin-title">
                    <header>
                        <h2 id="pin-title">
                            <span aria-hidden="true"></span> Skriv inn PIN-kode
                        </h2>
                        <p>Skriv inn PIN-koden for å få tilgang til emneinnholdet.</p>
                        <p>
                            <small><a href="login.php">Logg inn</a> for å slippe PIN-kode.</small>
                        </p>
                    </header>

                    <form method="POST" aria-describedby="pin-title">
                        <fieldset>
                            <legend class="visually-hidden">PIN-kode for <?php echo htmlspecialchars($emne['kode']); ?></legend>

                            <p class="form-group">
                                <label for="pin">PIN-kode (4 siffer)</label>
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
                                    autofocus
                                    aria-describedby="<?php echo $pinFeil ? 'pin-error' : ''; ?>">
                            </p>
                        </fieldset>

                        <button type="submit">Gå videre</button>

                        <?php if ($pinFeil): ?>
                            <p id="pin-error" class="error-message" role="alert">
                                <strong>Feil:</strong> Feil PIN-kode. Prøv igjen.
                            </p>
                        <?php endif; ?>
                    </form>
                </section>
            </article>

        <?php else: ?>
            <article class="feilmelding-container">
                <header>
                    <h1>Emne ikke funnet</h1>
                </header>
                <p>Beklager, vi fant ikke emnet du leter etter.</p>
                <nav aria-label="Navigasjon">
                    <a href="guest_hjemmeside.php" class="back-link">
                        <span aria-hidden="true">←</span> Tilbake til emneoversikt
                    </a>
                </nav>
            </article>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../footer.php'; ?>
</body>

</html>