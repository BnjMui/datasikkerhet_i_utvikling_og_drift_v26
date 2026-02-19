<?php
session_start();

?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Glemt passord - Foreleser</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <?php include __DIR__ . '/../header.php'; ?>
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
    <?php include __DIR__ . '/../footer.php'; ?>
</body>

</html>
