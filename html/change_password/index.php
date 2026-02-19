<?php
session_start();

?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endre passord - Emneportal</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <?php include __DIR__ . '/../header.php'; ?>
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

    <?php include __DIR__ . '/../footer.php'; ?>
</body>

</html>
