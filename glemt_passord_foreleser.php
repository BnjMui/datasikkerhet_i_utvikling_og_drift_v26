<?php
session_start();

require_once 'bruker_db.php';

$feilmelding = '';
$suksessmelding = '';
$step = 1;
$user = null;

// Steg 1: bruker oppgir e-post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'request') {
    $email = trim($_POST['email'] ?? '');
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feilmelding = 'Vennligst oppgi en gyldig e-postadresse.';
    } else {
        $user = finnBrukerMedEmail($email);
        if (!$user || ($user['rolle'] ?? '') !== 'foreleser') {
            $feilmelding = 'Fant ingen foreleser med denne e-posten.';
        } elseif (empty($user['sikkerhet_sporsmal'])) {
            $feilmelding = 'Denne kontoen har ikke satt opp et sikkerhetsspørsmål.';
        } else {
            $step = 2;
        }
    }
}

// Steg 2: verifiser svar og oppdater passord
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset') {
    $email = trim($_POST['email'] ?? '');
    $svar = trim($_POST['svar'] ?? '');
    $nyttPassord = $_POST['nytt_passord'] ?? '';
    $nyttPassordBekreft = $_POST['nytt_passord_bekreft'] ?? '';

    if (empty($email) || empty($svar)) {
        $feilmelding = 'E-post og svar er påkrevd.';
    } elseif (empty($nyttPassord) || strlen($nyttPassord) < 6) {
        $feilmelding = 'Nytt passord må være minst 6 tegn.';
    } elseif ($nyttPassord !== $nyttPassordBekreft) {
        $feilmelding = 'Passordene samsvarer ikke.';
    } else {
        $user = finnBrukerMedEmail($email);
        if (!$user || ($user['rolle'] ?? '') !== 'foreleser') {
            $feilmelding = 'Fant ingen foreleser med denne e-posten.';
        } elseif (empty($user['sikkerhet_svar']) || !password_verify($svar, $user['sikkerhet_svar'])) {
            $feilmelding = 'Sikkerhetssvaret er feil.';
        } else {
            // Oppdater passord
            if (oppdaterPassord($user['id'], $nyttPassord)) {
                $suksessmelding = 'Passordet ble oppdatert. Du kan nå logge inn.';
            } else {
                $feilmelding = 'Feil ved oppdatering av passord. Prøv igjen.';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Glemt passord - Foreleser</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <article class="login-container">
            <header>
                <h1>Glemt passord (Foreleser)</h1>
            </header>

            <?php if ($suksessmelding): ?>
                <p class="success-message"><?php echo htmlspecialchars($suksessmelding); ?></p>
                <p><a href="login.php">Gå til innlogging</a></p>
            <?php else: ?>

                <?php if ($step === 1): ?>
                    <form method="POST" aria-label="Be om sikkerhetsspørsmål">
                        <input type="hidden" name="action" value="request">
                        <p class="form-group">
                            <label for="email">E-postadresse</label>
                            <input id="email" type="email" name="email" required>
                        </p>
                        <button type="submit">Hent sikkerhetsspørsmål</button>
                    </form>
                <?php endif; ?>

                <?php if ($step === 2): ?>
                    <?php $user = $user ?? finnBrukerMedEmail($_POST['email'] ?? ''); ?>
                    <form method="POST" aria-label="Svar sikkerhetsspørsmål">
                        <input type="hidden" name="action" value="reset">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

                        <p class="form-group">
                            <label>Sikkerhetsspørsmål</label>
                            <p><strong><?php echo htmlspecialchars($user['sikkerhet_sporsmal']); ?></strong></p>
                        </p>

                        <p class="form-group">
                            <label for="svar">Svar</label>
                            <input id="svar" type="text" name="svar" required>
                        </p>

                        <p class="form-group">
                            <label for="nytt_passord">Nytt passord</label>
                            <input id="nytt_passord" type="password" name="nytt_passord" required minlength="6">
                        </p>

                        <p class="form-group">
                            <label for="nytt_passord_bekreft">Bekreft nytt passord</label>
                            <input id="nytt_passord_bekreft" type="password" name="nytt_passord_bekreft" required minlength="6">
                        </p>

                        <button type="submit">Reset passord</button>
                    </form>
                <?php endif; ?>

                <?php if ($feilmelding): ?>
                    <p class="error-message" role="alert"><?php echo htmlspecialchars($feilmelding); ?></p>
                <?php endif; ?>

            <?php endif; ?>

            <p><a href="login.php">Tilbake til innlogging</a></p>
        </article>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
