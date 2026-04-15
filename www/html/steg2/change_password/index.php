<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "bootstrap.php";

use DatasikkerhetG7\Frontend\ApiClient;

session_start();

$error_message = "";
$success = false;

// Håndter innlogging
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST["new_password"];
    $password_matches = $new_password == $_POST["new_password_confirm"];

    if (!$password_matches) {
        $error_message = "Passordene er ikke like";
    }
    if ($password_matches) {
        $result = ApiClient::change_password($new_password);

        if (!$result) {
            $error_message = "Ukjent feil";
        }
        header("Location: /steg2");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Endre passord - Emneportal</title>
    <link rel="stylesheet" href="/steg2/styles.css">
</head>

<body>
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/header.php'; ?>
    <main>
        <section class="login-container">
            <header>
                <h1>Endre passord</h1>
            </header>

            <?php if ($success): ?>
                <p class="success-message" role="status">
                    Passord endret!
                </p>
            <?php endif; ?>

            <form method="POST" action="" class="form-group">
                <fieldset>
                    <legend>Endre passord</legend>
                        <label for="new_password">Nytt passord *</label>
                        <input
                            id="new_password"
                            type="password"
                            name="new_password"
                            autocomplete="new-password"
                            required
                            minlength="8">

                        <label for="new_password_confirm">Bekreft nytt passord *</label>
                        <input
                            id="new_password_confirm"
                            type="password"
                            name="new_password_confirm"
                            autocomplete="new-password"
                            required
                            minlength="8">
                </fieldset>

                <button type="submit">Endre passord</button>

                <?php if ($error_message): ?>
                    <p class="error-message" role="alert">
                        <strong>Feil:</strong> <?php echo htmlspecialchars($error_message); ?>
                    </p>
                <?php endif; ?>
            </form>
        </section>
    </main>

    <?php include_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/footer.php"; ?>
</body>

</html>
