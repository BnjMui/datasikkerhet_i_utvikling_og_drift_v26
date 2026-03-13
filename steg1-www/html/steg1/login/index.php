<?php
session_start();

include_once $_SERVER["DOCUMENT_ROOT"] . '/../login_api_service.php';

// Hvis allerede innlogget, redirect til hjemmeside
if (isset($_SESSION['session_data']) && isset($_SESSION['session_data']['user_id'])) {
    header('Location: /steg1');
    exit;
}

$error_message = '';

// Håndter innlogging
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = isset($_POST['mail']) ? trim($_POST['mail']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $user_data = get_login($mail, $password);
    if ($user_data) {
        $_SESSION["session_data"] = $user_data;
        header("Location: /steg1");
    }
    $error_message = "Feil e-post eller passord, vennligst forsøk igjen.";
}

?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logg inn - Emneportal</title>
    <link rel="stylesheet" href="/steg1/styles.css">
</head>
<body>
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . '/steg1/header.php'; ?>
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
                        <label for="brukernavn">E-post</label>
                        <input
                            id="mail"
                            type="text"
                            name="mail"
                            autocomplete="username"
                            required
                            autofocus>
                    </p>

                    <p class="form-group">
                        <label for="password">Passord</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            required>
                    </p>
                </fieldset>

                <button type="submit">Logg inn</button>

                <?php if ($error_message): ?>
                    <p class="error-message" role="alert">
                        <strong>Feil:</strong> <?php echo htmlspecialchars($error_message); ?>
                    </p>
                <?php endif; ?>
            </form>

            <section>
                <p>Har du ikke en konto? <a href="/steg1/register">Registrer deg her</a></p>
                <p>Glemt passord som foreleser? <a href="/steg1/login/forgot_password">Tilbakestill her</a></p>
            </section>
        </article>
    </main>

    <?php include_once $_SERVER["DOCUMENT_ROOT"] . '/steg1/footer.php'; ?>
</body>

</html>
