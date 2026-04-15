<?php
session_start();

include_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/bootstrap.php";
use DatasikkerhetG7\Frontend\ApiClient;

# include_once $_SERVER["DOCUMENT_ROOT"] . '/../course_api_service.php';
# include_once $_SERVER["DOCUMENT_ROOT"] . '/../login_api_service.php';
# include_once $_SERVER["DOCUMENT_ROOT"] . '/../api_client.php';

// Hent brukerinfo fra session
$user = null;
$role = null;
$student_courses = [];

if (isset($_SESSION['session_data'])) {

    $user = $_SESSION['session_data'];
    $role = $user["role"];

}
$courses = ApiClient::get_courses();
if (isset($user["role"]) && $user["role"] == "student") {
    $student_courses = ApiClient::get_student_courses();
}



?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studentportal - hjemmeside</title>
    <link rel="stylesheet" href="/steg2/styles.css">
</head>

<body>
    <?php include_once $_SERVER["DOCUMENT_ROOT"] . '/steg2/header.php'; ?>

    <main>
        <header class="page-header">
            <h1>Emneoversikt</h1>

            <?php if ($role === 'lecturer'): ?>
                <p class="rolle-info">
                    Du ser alle emner.
                </p>
            <?php elseif ($role === 'student'): ?>
                <p class="rolle-info">
                    Som student kan du sende anonyme meldinger til alle emner.
                </p>
            <?php else: ?>
                <p class="rolle-info">
                    Du er ikke innlogget. <a href="/steg2/login">Logg inn</a> for flere funksjoner.
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
                    <a href="/steg2/change_password">
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
                <?php if (isset($user["role"]) && $user["role"] == "student" && $student_courses): ?>
                <nav aria-label="Emner">
                    <h3>Dine emner</h3>
                    <ul class="emne-liste">
                        <?php foreach ($student_courses as $course): ?>
                        <li>
                            <article class="emne-kort">
                                <a href=
                                    <?php
                                    echo "/steg2/course?course_id=" .
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

                <?php endif ?>
                <nav aria-label="Emner">
                    <h3> Alle emner</h3>
                    <ul class="emne-liste">
                        <?php foreach ($courses as $course):

                            ?>
                        <?php if (!in_array($course["course_id"], array_column($student_courses, "course_id"))): ?>
                        <li>
                            <article class="emne-kort">
                                <a href=
                                    <?php
                                        echo "/steg2/course?course_id=" .
                                        urlencode($course['course_id']) .
                                        "&course_code=" .
                                        urlencode($course["course_code"]);
                            ?>
                                    aria-label="<?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?>">

                                <header>
                                    <h3 class="emne-kode"><?php echo htmlspecialchars($course['course_code']); ?></h3>
                                </header>
                                <p class="emne-navn"><?php echo htmlspecialchars($course['course_name']); ?></p>
                                        <?php
                                    if (isset($user["role"]) && $user["role"] == "student") { ?>
                                    <form method="POST" action="/steg2/register_course.php">
                                        <input type="hidden" name="course_id" value="<?php echo $course["course_id"]; ?>" />
                                            <input type="submit" value="Registrer"/>
                                        </form>
                                    <?php
                                    }
                            ?>
                                    <p> 
                                        <?php
                            if (isset($user["user_id"]) && $user["user_id"] == $course["lecturer_id"]) {
                                echo "Du underviser dette emnet";
                            }
                            ?>
                                    </p>
                                </a>
                            </article>
                        </li>

                        <?php endif;
                        endforeach; ?>

                    </ul>
                </nav>
            <?php endif; ?>
        </section>
    </main>

    <?php include_once $_SERVER["DOCUMENT_ROOT"] . "/steg2/footer.php"; ?>
</body>

</html>
