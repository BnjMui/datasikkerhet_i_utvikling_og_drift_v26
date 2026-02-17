<?php
session_start();

// Inkluder emnedata og brukerdata
require_once '../emne_db.php';
require_once '../bruker_db.php';

// Hent brukerinfo fra session
$bruker = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$rolle = ($bruker && isset($bruker['rolle'])) ? $bruker['rolle'] : 'guest';

// Hent nåværende side
$currentPage = 'Hjemmeside';

// Hent emner basert på rolle
if ($rolle === 'foreleser') {
    // Foreleser ser kun egne emner
    $alleEmner = hentForeleserEmner($bruker['email']);
} else {
    // Guest og student ser alle emner
    $alleEmner = hentAlleEmner();
}
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emneoversikt - Emneportal</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <?php include __DIR__ . '/../header.php'; ?>

    <main>
        <header class="page-header">
            <h1>Emneoversikt</h1>

            <?php if ($rolle === 'foreleser'): ?>
                <p class="rolle-info">
                    <span aria-hidden="true"></span>
                    Du ser kun emner du underviser i.
                </p>
            <?php elseif ($rolle === 'student'): ?>
                <p class="rolle-info">
                    <span aria-hidden="true"></span>
                    Som student kan du sende anonyme meldinger til alle emner.
                </p>
            <?php else: ?>
                <p class="rolle-info">
                    <span aria-hidden="true"></span>
                    Du er ikke innlogget. <a href="login.php">Logg inn</a> for flere funksjoner.
                </p>
            <?php endif; ?>
        </header>

        <?php if ($bruker): ?>
            <section class="user-profile-section">
                <h2>Brukerprofil</h2>
                <p><strong>Navn:</strong> <?php echo htmlspecialchars($bruker['navn']); ?></p>
                <p><strong>E-post:</strong> <?php echo htmlspecialchars($bruker['email']); ?></p>
                <p><strong>Rolle:</strong> <?php echo ucfirst(htmlspecialchars($bruker['rolle'])); ?></p>
                <p>
                    <a href="passordbytte.php">
                        Endre passord
                    </a>
                </p>
            </section>
        <?php endif; ?>

        <section aria-labelledby="emne-liste-title">
            <h2 id="emne-liste-title" class="visually-hidden">Liste over emner</h2>

            <?php if (empty($alleEmner)): ?>
                <p class="ingen-emner">
                    <strong>Ingen emner å vise.</strong>
                    Du har ingen emner tilknyttet din brukerkonto.
                </p>
            <?php else: ?>
                <nav aria-label="Emner">
                    <ul class="emne-liste">
                        <?php foreach ($alleEmner as $emne): ?>
                            <li>
                                <article class="emne-kort">
                                    <?php if ($rolle === 'guest'): ?>
                                        <a href="emne.php?kode=<?php echo urlencode($emne['kode']); ?>"
                                            aria-label="<?php echo htmlspecialchars($emne['kode'] . ' - ' . $emne['navn']); ?>">
                                        <?php else: ?>
                                            <a href="guest_meldinger.php?kode=<?php echo urlencode($emne['kode']); ?>"
                                                aria-label="<?php echo htmlspecialchars($emne['kode'] . ' - ' . $emne['navn']); ?>">
                                            <?php endif; ?>
                                            <header>
                                                <h3 class="emne-kode"><?php echo htmlspecialchars($emne['kode']); ?></h3>
                                            </header>
                                            <p class="emne-navn"><?php echo htmlspecialchars($emne['navn']); ?></p>
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