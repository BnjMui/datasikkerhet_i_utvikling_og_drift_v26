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
        <div class="register-container">
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

            <form method="POST" action="process_register.php" enctype="multipart/form-data" novalidate>
                <div class="user-type-section">
                    <h3>Velg brukertype</h3>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="user_type" value="student" checked required>
                            <span>Registrer som student</span>
                        </label>
                        <label>
                            <input type="radio" name="user_type" value="lecturer" required>
                            <span>Registrer som Foreleser</span>
                        </label>
                    </div>
                </div>

                <h3>Personlig informasjon</h3>

                <div class="form-group">
                    <label for="firstname">Fornavn *</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Ditt fornavn" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Etternavn *</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Ditt etternavn" required>
                </div>

                <div class="form-group">
                    <label for="email">E-post *</label>
                    <input type="email" id="email" name="email" placeholder="din.email@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Passord *</label>
                    <input type="password" id="password" name="password" placeholder="Minst 6 tegn" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="password_confirm">Bekreft passord *</label>
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Bekreft ditt passord" required minlength="6">
                </div>

                <!-- Foreleser-spesifikk del -->
                <div id="lecturer-section" class="hidden-section">
                    <h3>Foreleser-informasjon</h3>

                    <div class="form-group">
                        <label for="emne">Emne</label>
                        <input type="emne" id="emne" name="emne" placeholder="INF100" required>
                    </div>

                    <div class="form-group">
                        <label for="picture">Profilbilde</label>
                        <div class="file-input-wrapper">
                            <input type="file" id="picture" name="picture" accept="image/*">
                            <p class="file-input-info">Tillatte formater: JPG, PNG, GIF (maks 5MB)</p>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit">Registrer</button>
                    <a href="login.php" class="btn-login">Logg inn</a>
                </div>
            </form>

            <div class="login-link">
                <p>Har du allerede en konto? <a href="login.php">Logg inn her</a></p>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        // Velger student/Foreleser section basert på valgt brukertype
        const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
        const lecturerSection = document.getElementById('lecturer-section');

        function updateLecturerSection() {
            const selectedType = document.querySelector('input[name="user_type"]:checked').value;
            if (lecturerSection) {
                if (selectedType === 'lecturer') {
                    lecturerSection.classList.add('active');
                } else {
                    lecturerSection.classList.remove('active');
                }
            }
        }

        userTypeRadios.forEach(radio => {
            radio.addEventListener('change', updateLecturerSection);
        });

        // KJører funksjon ved lasting
        updateLecturerSection();

        // Sjekker at passordene stemmer
        const passwordForm = document.querySelector('form');
        passwordForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;

            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Shit! Passordene er ikke like!');
                return false;
            }
        });
    </script>
</body>

</html>
