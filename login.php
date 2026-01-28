<?php
session_start();

require_once 'bruker_db.php';

// Hvis allerede innlogget, redirect til hjemmeside
if (isset($_SESSION['user']) && isset($_SESSION['user']['rolle'])) {
    header('Location: guest_hjemmeside.php');
    exit;
}

$feilmelding = '';

// Håndter innlogging
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brukernavn = isset($_POST['brukernavn']) ? trim($_POST['brukernavn']) : '';
    $passord = isset($_POST['passord']) ? $_POST['passord'] : '';

    $bruker = finnBruker($brukernavn, $passord);

    if ($bruker) {
        // Regenerer session ID for sikkerhet
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'id' => $bruker['id'],
            'navn' => $bruker['navn'],
            'email' => $bruker['email'],
            'rolle' => $bruker['rolle']
        ];

        header('Location: guest_hjemmeside.php');
        exit;
    } else {
        $feilmelding = 'Feil brukernavn eller passord.';
    }
}

$currentPage = 'login';
$bruker = null;
$rolle = 'guest';
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logg inn - Emneportal</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>

    <main>
        <article class="login-container">
            <header>
                <h1 id="login-title">Logg inn</h1>
                <p>Logg inn som student eller foreleser</p>
            </header>

            <form method="POST" aria-labelledby="login-title">
                <fieldset>
                    <legend class="visually-hidden">Innloggingsinformasjon</legend>

                    <p class="form-group">
                        <label for="brukernavn">Brukernavn</label>
                        <input
                            id="brukernavn"
                            type="text"
                            name="brukernavn"
                            autocomplete="username"
                            required
                            autofocus>
                    </p>

                    <p class="form-group">
                        <label for="passord">Passord</label>
                        <input
                            id="passord"
                            type="password"
                            name="passord"
                            autocomplete="current-password"
                            required>
                    </p>
                </fieldset>

                <button type="submit">Logg inn</button>

                <?php if ($feilmelding): ?>
                    <p class="error-message" role="alert">
                        <strong>Feil:</strong> <?php echo htmlspecialchars($feilmelding); ?>
                    </p>
                <?php endif; ?>
            </form>
        </article>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>