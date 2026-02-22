<?php
session_start();

include_once $_SERVER["DOCUMENT_ROOT"] . '../../course_api_service.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '../../login_api_service.php';
include_once $_SERVER["DOCUMENT_ROOT"] . '../../api_client.php';

$user = isset($_SESSION['session_data']) ? $_SESSION['session_data'] : null;

$course_id = $_GET['course_id'];
$course_code = $_GET["course_code"];

$_SESSION["prev_course_code"] = $course_code;
if ($user && $course_id) {
    $course = get_course($course_id);
}

$error_message = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin_code = $_POST["pin_code"];
    if (!$pin_code) {
        $error_message = "Ingen pin, prøv igjen.";
    }
    if (isset($pin_code)) {
        $course = get_course($course_id, $pin_code);
        $_SESSION["pin_code"] = $pin_code;
    }
    if (!$course) {
        $error_message = "Feil pin, forsøk igjen";
    }
}

if ($course) {
    $course_data = $course["course"];
    $lecturer = $course["lecturer"];
}
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Emneside - Emneportal</title>
    <link rel="stylesheet" href="/steg1/styles.css">
</head>

<body>
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . '/steg1/header.php'; ?>

    <main>
        <nav aria-label="Navigasjon">
            <a href="/steg1" class="back-link">
                <span aria-hidden="true">←</span> Tilbake til emneoversikt
            </a>
        </nav>
        <?php if (!$user && !$course_data): ?>
            <article class="emne-container">
                <header class="emne-header">
                    <h1><?php echo htmlspecialchars($course_code); ?></h1>
                </header>

                <section class="pin-section" aria-labelledby="pin-title">
                    <header>
                        <h2 id="pin-title">
                            Skriv inn PIN-kode
                        </h2>
                        <p>Skriv inn PIN-koden for å få tilgang til emneinnholdet.</p>
                        <p>
                            <small><a href="/steg1/login">Logg inn</a> for å slippe PIN-kode.</small>
                        </p>
                    </header>

                    <form method="POST" aria-describedby="pin-title">
                        <fieldset>
                            <legend class="visually-hidden">PIN-kode for <?php echo htmlspecialchars($course_code); ?></legend>

                            <p class="form-group">
                                <label for="pin">PIN-kode (4 siffer)</label>
                                <input
                                    id="pin_code"
                                    type="password"
                                    name="pin_code"
                                    class="pin-input"
                                    maxlength="4"
                                    placeholder="••••"
                                    pattern="[0-9]{4}"
                                    inputmode="numeric"
                                    autocomplete="one-time-code"
                                    required
                                    autofocus
                                    aria-describedby="<?php echo $error_message ? 'pin-error' : ''; ?>">
                            </p>
                        </fieldset>

                        <button type="submit">Gå videre</button>

                        <?php if ($error_message): ?>
                            <p id="pin-error" class="error-message" role="alert">
                                <strong>Feil:</strong> Feil PIN-kode. Prøv igjen.
                            </p>
                        <?php endif; ?>
                    </form>
                </section>
            </article>
            <?php endif; ?>
            <?php if ($course_data): ?>
            <header>
                <h1>
                    <?php echo $course_data["course_name"]; ?>
                </h1>
                <p>
                <?php echo $course_data["course_code"]; ?>
                </p>
                </header>
            <section>
                <h2>Emneansvarlig</h2>
                <img src="/steg1/register/<?php echo $lecturer["avatar"];?>" width="250"/>
                <p>
                    <?php echo $lecturer["first_name"] . " " . $lecturer["last_name"]; ?>
                </p>
            </section>
            <section>
                <h2>Meldinger</h2>
                <ul>
                <?php if ($course["messages"]):
                    foreach ($course["messages"] as $message) { ?>
                    <li>
                        <h3>Message #ID: <?php echo $message["message_id"]?></h3>
                        <article>
                            <p>
                            <?php echo $message["text"]; ?>
                            </p>
                            <p>
                            <?php echo $message["created_at"]; ?>
                            </p>
                            <?php if (!$user): ?>
                            <form method="POST" action="/steg1/course/post_comment.php">
                        <input
                            id="message"
                            name="message"
                            placeholder="Skriv her..."
                            type="text"
                            />
                                <label for="report">Rapporter?</label>
                                <input type="checkbox" name="report">
                                <input type="hidden" name="course_id" value=<?php echo $course_data["course_id"] ?>>
                                <input type="hidden" name="message_id" value=<?php echo $message["message_id"] ?>>
                                <input type="hidden" name="pin_code" value=<?php echo $pin_code ?>>
                                <button type="submit">Send</button>
                    </form>

                            <?php endif ?>
                            <?php if ($user["role"] == "lecturer" && !$message["replies"]): ?>
                            <form method="POST" action="/steg1/course/post_comment.php">
                        <input
                            id="message"
                            name="message"
                            placeholder="Skriv her..."
                            type="text"
                            />
                                <input type="hidden" name="message_id" value=<?php echo $message["message_id"]; ?>>
                                <input type="hidden" name="course_id" value=<?php echo $course_data["course_id"] ?>>
                                <button type="submit">Send</button>
                    </form>

                            <?php endif?>


                        </article>
                            <h4>Reply</h4>
                        <article>
                        <?php if (!$message["replies"]): ?>
                            <p>Foreleseren har enda ikke svart på denne meldingen</p>
                            <?php else:
                                foreach ($message["replies"] as $reply) {
                                    ?>
                            <p>
                            <?php echo $reply["text"]; ?>
                            </p>
                            <p>
                            <?php echo $reply["created_at"]; ?>
                            </p>
                            <?php
                                }
                            endif ?>
                        </article>
                        <?php if ($message["comments"]): ?>
                            <h4>Comments</h4>
                        <?php foreach ($message["comments"] as $comment) { ?>
                            <article>
                            <p>
                            <?php echo $comment["text"]; ?>
                            </p>
                            <p>
                            <?php echo $comment["created_at"]; ?>
                            </p>
                            </article>
                            <?php } endif ?>
                        </article>
                    </li>
                    <?php } ?>
                    </ul>

                <?php if ($user["role"] == "student"): ?>
                <section>
                    <form method="POST" action="/steg1/course/post_comment.php">
                        <input
                            id="message"
                            name="message"
                            placeholder="Skriv her..."
                            type="text"
                            />
                        <input type="hidden" name="course_id" value=<?php echo $course_id ?> />
                        <button type="submit">Send</button>
                    </form>
                </section>
                <?php endif ?>
            <?php endif; ?>
            <?php endif; ?>

        <?php if (!$course_id && !$user): ?>
            <section class="feilmelding-container">
                <header>
                    <h1>Emne ikke funnet</h1>
                </header>
                <p>Beklager, vi fant ikke emnet du leter etter.</p>
                    <section/>
        <?php endif; ?>

    </main>

    <?php include_once $_SERVER["DOCUMENT_ROOT"] . "/steg1/footer.php"; ?>
</body>

</html>
