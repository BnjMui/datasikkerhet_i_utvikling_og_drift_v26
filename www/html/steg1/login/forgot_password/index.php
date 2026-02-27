<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/../login_api_service.php';
session_start();

$error_message = "";
$success = false;

// Håndter innlogging
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST["new_password"]) {
        $new_password = $_POST["new_password"];
        $password_matches = $new_password == $_POST["new_password_confirm"];

        if (!$password_matches) {
            $error_message = "Passordene er ikke like";
        }
        if ($password_matches) {

            $result = forgot_password($_POST["mail"], $_POST["security_answer"], $new_password);

            header("Location: /steg1/login");
        }
    }
    if (!$_POST["new_password"] && $_POST["mail"]) {
        $result = get_security_question($_POST["mail"]);

        $security_question = $result;
    }

}
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Glemt passord - Foreleser</title>
    <link rel="stylesheet" href="/steg1/styles.css">
</head>

<body>
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . '/steg1/header.php'; ?>
    <main>
        <article class="login-container">
            <header>
                <h1>Glemt passord (Foreleser)</h1>
            </header>

            <?php if ($success): ?>
                <p class="success-message">Passord endret!</p>
                <p><a href="/steg1/login">Gå til innlogging</a></p>
                <?php endif ?>
            <?php if (!$security_question): ?>

                    <form method="POST" aria-label="Be om sikkerhetsspørsmål" class="form-group">
                            <label for="mail">E-postadresse</label>
                            <input id="mail" type="email" name="mail" required>
                        <button type="submit">Hent sikkerhetsspørsmål</button>
                    </form>
                <?php endif ?>

                <?php if ($security_question): ?>
                    <form method="POST" action="" aria-label="Svar sikkerhetsspørsmål" class="form-group">
                        <input type="hidden" name="mail" value="<?php echo $_POST['mail']; ?>">
                    <?php echo $_POST["mail"]; ?>

                            <label>Sikkerhetsspørsmål</label>
                        <p><strong><?php echo htmlspecialchars($security_question); ?></strong></p>

                            <label for="security_answer">Svar</label>
                            <input id="security_answer" type="text" name="security_answer" required>

                            <label for="new_password">Nytt passord</label>
                            <input id="new_password" type="password" name="new_password" required minlength="8">

                            <label for="new_password_confirm">Bekreft nytt passord</label>
                            <input id="new_password_confirm" type="password" name="new_password_confirm" required minlength="8">

                        <button type="submit">Reset passord</button>
                    </form>
                <?php endif; ?>

                <?php if ($error_message): ?>
                    <p class="error-message" role="alert"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>


            <p><a href="/steg1/login">Tilbake til innlogging</a></p>
        </article>
    </main>
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . '/steg1/footer.php'; ?>
</body>

</html>
