<?php
session_start();

// Hvis allerede innlogget, redirect til hjemmeside
if (isset($_SESSION['user']) && isset($_SESSION['user']['rolle'])) {
    header('Location: guest_hjemmeside.php');
    exit;
}

$feilmelding = '';
$suksessmelding = '';

// Hent success meldinger fra GET
if (isset($_GET['success'])) {
    $suksessmelding = 'Registrering vellykket! Logg inn med dine opplysninger.';
}

$currentPage = 'register';
$bruker = null;
$rolle = 'guest';
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrering - Emneportal</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <main role="main">
        <section class="register-container">
            <h1>Registrering</h1>

            <?php if ($suksessmelding): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($suksessmelding); ?>
                </div>
            <?php endif; ?>

            <?php if ($feilmelding): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($feilmelding); ?>
                </div>
            <?php endif; ?>

            <section method="POST" action="registreringsprosess.php" enctype="multipart/form-data" novalidate>
                <fieldset class="user-type-section">
                    <h3>Velg brukertype</h3>
                    <article class="radio-group">
                        <label>
                            <input type="radio" name="user_type" value="student" checked required>
                            <span>Registrer som student</span>
                        </label>
                        <label>
                            <input type="radio" name="user_type" value="lecturer" required>
                            <span>Registrer som Foreleser</span>
                        </label>
                    </article>
                </fieldset>

                <h3>Personlig informasjon</h3>

                <form class="form-group">
                    <label for="firstname">Fornavn *</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Ditt fornavn" required>
                </form>

                <form class="form-group">
                    <label for="lastname">Etternavn *</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Ditt etternavn" required>
                </form>

                <form class="form-group">
                    <label for="email">E-post *</label>
                    <input type="email" id="email" name="email" placeholder="din.email@example.com" required>
                </form>

                <form class="form-group">
                    <label for="password">Passord *</label>
                    <input type="password" id="password" name="password" placeholder="Minst 6 tegn" minlength="6">
                </form>

                <form class="form-group">
                    <label for="password_confirm">Bekreft passord *</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Bekreft ditt passord" minlength="6">
                </form>

                <!-- Student-spesifikk del -->
                <article id="student-section" class="hidden-section">
                    <h3>Studentinformasjon</h3>

                    <form class="form-group">
                        <label for="studieretning">Studieretning *</label>
                        <input type="text" id="studieretning" name="studieretning" placeholder="f.eks. Datasikkerhet" required>
                    </form>

                    <form class="form-group">
                        <label for="studiekull">Studiekull (år) *</label>
                        <input type="number" id="studiekull" name="studiekull" placeholder="f.eks. 2024" min="2000" max="2100" required>
                    </form>
                </article>

                <!-- Foreleser-spesifikk del -->
                <section id="lecturer-section" class="hidden-section">
                    <h3>Foreleser-informasjon</h3>

                    <article class="form-group">
                        <label for="emne">Velg emne du underviser i *</label>
                        <select id="emne" name="emne" required>
                            <option value="">-- Velg emne --</option>
                            <?php
                            require_once 'emne_db.php';
                            foreach (hentAlleEmner() as $emne) {
                                echo '<option value="' . htmlspecialchars($emne['kode']) . '">' . 
                                     htmlspecialchars($emne['navn'] . ' (' . $emne['kode'] . ')') . 
                                     '</option>';
                            }
                            ?>
                        </select>
                    </article>

                    <form class="form-group">
                        <label for="emne_pin">PIN for emne</label>
                        <input type="text" id="emne_pin" name="emne_pin" placeholder="PIN for emnet (vises når emnet velges)" readonly>
                    </form>

                    <article class="form-group">
                        <label for="picture">Profilbilde</label>
                        <form class="file-input-wrapper">
                            <input type="file" id="picture" name="picture" accept="image/*">
                            <p class="file-input-info">Tillatte formater: JPG, PNG, GIF (maks 5MB)</p>
                        </form>
                    </article>

                    <article class="form-group">
                        <label for="security_question">Sikkerhetsspørsmål *</label>
                        <input type="text" id="security_question" name="security_question" placeholder="Skriv ditt sikkerhetsspørsmål" required>
                    </article>

                    <article class="form-group">
                        <label for="security_answer">Svar på sikkerhetsspørsmål *</label>
                        <input type="text" id="security_answer" name="security_answer" placeholder="Svar" required>
                    </article>
                </section>

                <section class="button-group">
                    <button type="submit">Registrer</button>
                    <a href="login.php" class="btn-login">Logg inn</a>
                </section>
            </section>

            <section class="login-link">
                <p>Har du allerede en konto? <a href="login.php">Logg inn her</a></p>
            </article>
        </section>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        // Velger student/Foreleser section basert på valgt brukertype
        const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
        const studentSection = document.getElementById('student-section');
        const lecturerSection = document.getElementById('lecturer-section');

        function updateSectionVisibility() {
            const selectedType = document.querySelector('input[name="user_type"]:checked').value;
            const passwordField = document.getElementById('password');
            const passwordConfirmField = document.getElementById('password_confirm');
            const securityQuestionField = document.getElementById('security_question');
            const securityAnswerField = document.getElementById('security_answer');
            
            if (selectedType === 'student') {
                if (studentSection) {
                    studentSection.classList.add('active');
                    studentSection.classList.remove('hidden-section');
                }
                if (lecturerSection) {
                    lecturerSection.classList.remove('active');
                    lecturerSection.classList.add('hidden-section');
                }
                // Password required for students
                passwordField.required = true;
                passwordConfirmField.required = true;
                if (securityQuestionField) securityQuestionField.required = false;
                if (securityAnswerField) securityAnswerField.required = false;
            } else if (selectedType === 'lecturer') {
                if (studentSection) {
                    studentSection.classList.remove('active');
                    studentSection.classList.add('hidden-section');
                }
                if (lecturerSection) {
                    lecturerSection.classList.add('active');
                    lecturerSection.classList.remove('hidden-section');
                }
                // Password not required for lecturers
                passwordField.required = false;
                passwordConfirmField.required = false;
                if (securityQuestionField) securityQuestionField.required = true;
                if (securityAnswerField) securityAnswerField.required = true;
            }
        }

        userTypeRadios.forEach(radio => {
            radio.addEventListener('change', updateSectionVisibility);
        });

        // KJører funksjon ved lasting
        updateSectionVisibility();

        // Håndter emne-valg for forelesere
        const emneSelect = document.getElementById('emne');
        const emnePinField = document.getElementById('emne_pin');
        
        // Emne data fra backend
        const emneData = <?php 
            require_once 'emne_db.php';
            $emneDataJson = [];
            foreach (hentAlleEmner() as $emne) {
                $emneDataJson[$emne['kode']] = $emne['pin'];
            }
            echo json_encode($emneDataJson);
        ?>;

        if (emneSelect) {
            emneSelect.addEventListener('change', function() {
                if (this.value && emneData[this.value]) {
                    emnePinField.value = emneData[this.value];
                } else {
                    emnePinField.value = '';
                }
            });
        }

        // Sjekker at passordene stemmer
        const passwordForm = document.querySelector('form');
        passwordForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;

            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Passordene samsvarer ikke!');
                return false;
            }
        });
    </script>
</body>

</html>
