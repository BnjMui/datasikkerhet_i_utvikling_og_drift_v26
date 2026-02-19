<?php
session_start();

require_once __DIR__ . "/" . '../course_api_service.php';
require_once __DIR__ . "/" . '../login_api_service.php';
require_once __DIR__ . "/" . '../api_client.php';

// Hent brukerinfo fra session
$user = isset($_SESSION['session_data']) ? $_SESSION['session_data'] : null;
$role = $user["role"];

$courses = get_courses();
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studentportal - hjemmeside</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include __DIR__ . '/../header.php'; ?>

    <main>
        <header class="page-header">
            <h1>Emneoversikt</h1>

            <?php if ($role === 'lecturer'): ?>
                <p class="rolle-info">
                    Du ser kun emner du underviser i.
                </p>
            <?php elseif ($role === 'student'): ?>
                <p class="rolle-info">
                    Som student kan du sende anonyme meldinger til alle emner.
                </p>
            <?php else: ?>
                <p class="rolle-info">
                    Du er ikke innlogget. <a href="/login">Logg inn</a> for flere funksjoner.
                </p>
            <?php endif; ?>
        </header>

        <?php if ($user): ?>
            <section class="user-profile-section">
                <h2>Brukerprofil</h2>
                <p><strong>Navn:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
                <p><strong>E-post:</strong> <?php echo htmlspecialchars($user['mail']); ?></p>
                <p><strong>Rolle:</strong> <?php echo ucfirst(htmlspecialchars($user['role'])); ?></p>
                <p>
                    <a href="passordbytte.php">
                        Endre passord
                    </a>
                </p>
            </section>
        <?php endif; ?>

        <section aria-labelledby="emne-liste-title">
            <h2 id="emne-liste-title" class="visually-hidden">Liste over emner</h2>

            <?php if (empty($courses)): ?>
                <p class="ingen-emner">
                    <strong>Ingen emner å vise.</strong>
                    Du har ingen emner tilknyttet din brukerkonto.
                </p>
            <?php else: ?>
                <nav aria-label="Emner">
                    <ul class="emne-liste">
                        <?php foreach ($courses as $course): ?>
                        <li>
                            <article class="emne-kort">
                                <a href=
                                    <?php
                                    echo "/course?course_id=" . 
                                    urlencode($course['course_id']) .
                                    "&course_code=" .
                                    urlencode($course["course_code"]);
                                    ?>
                                    aria-label="<?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?>">

                                <header>
                                    <h3 class="emne-kode"><?php echo htmlspecialchars($course['course_code']); ?></h3>
                                </header>
                                <p class="emne-navn"><?php echo htmlspecialchars($course['course_name']); ?></p>
                                    <p> 
                                        <?php 
                                        if ($user["user_id"] == $course["lecturer_id"]) {
                                        echo "Du underviser dette emnet";
                                        } 
                                        ?>
                                    </p>
                                </a>
                            </article>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/../footer.php'; ?>
</body>

</html>
