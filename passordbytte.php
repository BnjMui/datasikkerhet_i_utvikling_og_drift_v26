<?php
session_start();

require_once 'bruker_db.php';

// Hvis ikke innlogget, redirect til login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$feilmelding = '';
$suksessmelding = '';
$currentUser = $_SESSION['user'];

// Håndter skjema submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gammelPassord = $_POST['gammel_passord'] ?? '';
    $nyttPassord = $_POST['nytt_passord'] ?? '';
    $nyttPassordBekreft = $_POST['nytt_passord_bekreft'] ?? '';

    // Validering
    if (empty($gammelPassord)) {
        $feilmelding = 'Gammelt passord er påkrevd.';
    } elseif (empty($nyttPassord) || strlen($nyttPassord) < 6) {
        $feilmelding = 'Nytt passord må være minst 6 tegn.';
    } elseif ($nyttPassord !== $nyttPassordBekreft) {
        $feilmelding = 'De nye passordene samsvarer ikke.';
    } else {
        // Verifiser gammelt passord
        $bruker = finnBrukerMedId($currentUser['id']);
        
        if ($bruker && password_verify($gammelPassord, $bruker['passord'])) {
            // Oppdater passord
            if (oppdaterPassord($currentUser['id'], $nyttPassord)) {
                // Oppdater session
                $_SESSION['user'] = $bruker;
                $suksessmelding = 'Passord endret med suksess!';
                // Redirect etter 2 sekunder
                echo '<meta http-equiv="refresh" content="2; url=guest_hjemmeside.php">';
            } else {
                $feilmelding = 'Feil ved oppdatering av passord. Prøv igjen.';
            }
        } else {
            $feilmelding = 'Gammelt passord er feil.';
        }
    }
}

$currentPage = 'passordbytte';
$rolle = isset($currentUser['rolle']) ? $currentUser['rolle'] : 'guest';
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endre passord - Emneportal</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <main>
        <article class="login-container">
            <header>
                <h1>Endre passord</h1>
            </header>

            <?php if ($suksessmelding): ?>
                <p class="success-message" role="status">
                    <?php echo htmlspecialchars($suksessmelding); ?>
                </p>
            <?php endif; ?>

            <form method="POST" aria-labelledby="change-title">
                <fieldset>
                    <legend class="visually-hidden">Passordendring</legend>

                    <p class="form-group">
                        <label for="gammel_passord">Gammelt passord *</label>
                        <input
                            id="gammel_passord"
                            type="password"
                            name="gammel_passord"
                            autocomplete="current-password"
                            required>
                    </p>

                    <p class="form-group">
                        <label for="nytt_passord">Nytt passord *</label>
                        <input
                            id="nytt_passord"
                            type="password"
                            name="nytt_passord"
                            autocomplete="new-password"
                            required
                            minlength="6">
                    </p>

                    <p class="form-group">
                        <label for="nytt_passord_bekreft">Bekreft nytt passord *</label>
                        <input
                            id="nytt_passord_bekreft"
                            type="password"
                            name="nytt_passord_bekreft"
                            autocomplete="new-password"
                            required
                            minlength="6">
                    </p>
                </fieldset>

                <button type="submit">Endre passord</button>

                <?php if ($feilmelding): ?>
                    <p class="error-message" role="alert">
                        <strong>Feil:</strong> <?php echo htmlspecialchars($feilmelding); ?>
                    </p>
                <?php endif; ?>
            </form>

            <section>
                <p><a href="guest_hjemmeside.php">Tilbake til hjemmeside</a></p>
            </section>
        </article>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>
