<?php
session_start();

if (isset($_SESSION["session_data"]["user_id"])) {
    header('Location: /steg2');
    exit;
}
if (isset($_POST["role"])) {
    $user_type = $_POST["role"];
} else {
    $user_type = "student";
}

$error_message = '';

?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrering - Emneportal</title>
    <link rel="stylesheet" href="/steg2/styles.css">
</head>

<body>
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . '/steg1/header.php'; ?>

    <main role="main">
        <section class="register-container">
            <h1>Registrering</h1>

            <?php if ($error_message): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

                <section>
                    <h3>Velg brukertype</h3>
                    <form method="POST" action="">
                    <fieldset class="user-type-section">
                            <input type="radio" id="user_type_student" name="role" value="student" checked required>
                        <label for="user_type_student">Registrer deg som student</label>
                        <input type="radio" name="role" value="lecturer" <?php if ($user_type == "lecturer"): ?> checked <?php endif ?> required>
                            <label for="user_type_lecurer">Registrer deg som foreleser</label>
                </fieldset>
                    <button type="submit">Lagre</button>
                    </form>
                </section>

                <section>
                <h3>Registreringsskjema</h3>

                <form method="POST" action="/steg2/register/register.php" class="form-group" enctype="multipart/form-data">
                        <fieldset>
                            <legend>Kontaktinformasjon</legend>
                        <label for="first_name">Fornavn *</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Fornavn" required>

                        <label for="last_name">Etternavn *</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Etternavn" required>

                        <label for="mail">E-post *</label>
                        <input type="mail" id="mail" name="mail" placeholder="epost@eksempel.no" required>

                        <label for="password">Passord (minimum 8 tegn) *</label>
                        <input type="password" id="password" name="password" placeholder="********" minlength="8">

                        <label for="password_confirm">Bekreft passord *</label>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="********" minlength="8">
                            </fieldset>

                <!-- Student-spesifikk del -->
                    <?php if ($user_type == "student"): ?>
                        <input type="hidden" name="role" value="student" />
                        <fieldset>
                            <legend>Studentinformasjon</legend>

                        <label for="study_field">Studieretning *</label>
                        <input type="text" id="study_field" name="study_field" placeholder="Informasjonssystemer" required>

                        <label for="class_year">Studiekull</label>
                        <input type="number" id="class_year" name="class_year" placeholder="2023" min="2000" max="2100" required>
                            </fieldset>
                </article>
                    <?php endif ?>
                    <?php if($user_type == "lecturer"): ?>
                        <input type="hidden" name="role" value="lecturer" />
                    <fieldset>
                        <legend>Foreleserinformasjon</legend>

                            <label for="course_name">Emne *</label>
                            <input type="text" id="course_name" name="course_name" required/>

                            <label for="course_code">Emnekode (maks 14 tegn) *</label>
                            <input type="text" id="course_code" name="course_code" maxlength="14" required/>

                            <label for="pin_code">PIN for emne (Kun 4 tall) *</label>
                            <input type="password" id="pin_code" name="pin_code" placeholder="****" pattern="\d{4,4}" required>

                            <label for="avatar">Profilbilde</label>
                                <input type="file" id="avatar" name="avatar" accept="image/png, image/jpg">
                                <p class="file-input-info">Tillatte formater: JPG, PNG (maks 5MB)</p>

                            <label for="security_question">Sikkerhetsspørsmål *</label>
                            <input type="text" id="security_question" name="security_question" placeholder="Skriv ditt sikkerhetsspørsmål" required>

                            <label for="security_answer">Svar på sikkerhetsspørsmål *</label>
                            <input type="text" id="security_answer" name="security_answer" placeholder="Svar" required>
                        </fieldset>


                    <?php endif ?>
                    <button type="submit">Registrer</button>
                    </form>

            </section>

            <section class="login-link">
                <p>Har du allerede en konto? <a href="/steg2/login">Logg inn her</a></p>
                </article>
            </section>
    </main>

    <?php include_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/footer.php'; ?>

</body>

</html>
