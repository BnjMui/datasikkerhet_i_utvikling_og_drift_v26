<?php
session_start();

// Inkluder emnedata og brukerdata
require_once '../emne_db.php';
require_once '../bruker_db.php';

// Hent brukerinfo fra session
$bruker = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$rolle = ($bruker && isset($bruker['rolle'])) ? $bruker['rolle'] : 'guest';

// Hent nåværende side
$currentPage = 'meldinger';

// Hent emnekode fra URL og finn emnet
$emneKode = isset($_GET['kode']) ? $_GET['kode'] : '';
$emne = finnEmne($emneKode);

// Sjekk tilgang for foreleser
$harTilgang = true;
if ($rolle === 'foreleser' && $emne) {
    $harTilgang = harForeleserTilgang($emneKode, $bruker['email']);
}

// Hent meldinger for emnet
$meldinger = hentMeldinger($emneKode);

// Håndter ny melding fra student
$meldingSendt = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ny_melding']) && $rolle === 'student') {
    $nyMelding = trim($_POST['ny_melding']);
    if (!empty($nyMelding)) {
        leggTilMelding($emneKode, $nyMelding);
        $meldingSendt = true;
        $meldinger = hentMeldinger($emneKode);
    }
}

// Håndter svar fra foreleser
$svarSendt = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['svar']) && isset($_POST['melding_id']) && $rolle === 'foreleser') {
    $svarTekst = trim($_POST['svar']);
    $meldingId = $_POST['melding_id'];
    if (!empty($svarTekst) && $harTilgang) {
        leggTilSvar($emneKode, $meldingId, $svarTekst, $bruker['navn']);
        $svarSendt = true;
        $meldinger = hentMeldinger($emneKode);
    }
}

// Håndter rapportering (kun for guest)
$rapportSendt = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rapporter_id']) && $rolle === 'guest') {
    $rapportertId = $_POST['rapporter_id'];
    $rapportGrunn = isset($_POST['rapport_grunn']) ? htmlspecialchars($_POST['rapport_grunn']) : '';
    $rapportSendt = true;
}
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $emne ? htmlspecialchars(strtoupper($emne['kode'])) : 'Emne ikke funnet'; ?> - Meldinger</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <?php include __DIR__ . '/../header.php'; ?>

    <main>
        <?php if (!$harTilgang): ?>
            <article class="feilmelding-container">
                <header>
                    <h1>
                        <span aria-hidden="true"></span> Ingen tilgang
                    </h1>
                </header>
                <p>Du har ikke tilgang til meldinger i dette emnet.</p>
                <nav aria-label="Navigasjon">
                    <a href="guest_hjemmeside.php" class="back-link">
                        <span aria-hidden="true">←</span> Tilbake til emneoversikt
                    </a>
                </nav>
            </article>

        <?php elseif ($emne): ?>
            <article class="emne-container">
                <!-- Emne header med foreleser info -->
                <header class="emne-header">
                    <section class="emne-info">
                        <p class="emne-kode"><?php echo htmlspecialchars(strtoupper($emne['kode'])); ?></p>
                        <h1><?php echo htmlspecialchars($emne['navn']); ?></h1>
                    </section>

                    <figure class="foreleser-kort">
                        <img src="<?php echo htmlspecialchars($emne['foreleser']['bilde']); ?>"
                            alt=""
                            width="100"
                            height="100">
                        <figcaption>
                            <p class="label">Foreleser</p>
                            <p class="navn"><?php echo htmlspecialchars($emne['foreleser']['navn']); ?></p>
                            <address>
                                <a href="mailto:<?php echo htmlspecialchars($emne['foreleser']['email']); ?>">
                                    <?php echo htmlspecialchars($emne['foreleser']['email']); ?>
                                </a>
                            </address>
                        </figcaption>
                    </figure>
                </header>

                <!-- Skjema for ny melding (kun for studenter) -->
                <?php if ($rolle === 'student'): ?>
                    <section class="ny-melding-section" aria-labelledby="ny-melding-title">
                        <header>
                            <h2 id="ny-melding-title">
                                <span aria-hidden="true"></span> Send anonym melding
                            </h2>
                        </header>

                        <form method="POST">
                            <fieldset>
                                <legend class="visually-hidden">Ny anonym melding</legend>

                                <p class="form-group">
                                    <label for="ny_melding">Din melding</label>
                                    <textarea
                                        id="ny_melding"
                                        name="ny_melding"
                                        rows="4"
                                        placeholder="Skriv din anonyme melding her..."
                                        required></textarea>
                                </p>
                            </fieldset>

                            <button type="submit">Send melding</button>
                        </form>

                        <?php if ($meldingSendt): ?>
                            <p class="success-message" role="status">
                                <strong>✓ Sendt:</strong> Meldingen din ble sendt anonymt!
                            </p>
                        <?php endif; ?>
                    </section>
                <?php endif; ?>

                <!-- Meldinger seksjon -->
                <section class="meldinger-section" aria-labelledby="meldinger-title">
                    <header class="meldinger-header">
                        <h2 id="meldinger-title">Meldinger</h2>
                        <p class="melding-count">
                            <strong><?php echo count($meldinger); ?></strong>
                            <?php echo count($meldinger) === 1 ? 'melding' : 'meldinger'; ?>
                        </p>
                    </header>

                    <?php if (empty($meldinger)): ?>
                        <p class="ingen-meldinger">Ingen meldinger ennå i dette emnet.</p>
                    <?php else: ?>
                        <ol class="melding-liste" aria-label="Liste over meldinger">
                            <?php foreach ($meldinger as $melding): ?>
                                <li>
                                    <article class="melding-kort" aria-labelledby="melding-<?php echo $melding['id']; ?>-title">
                                        <header class="melding-header">
                                            <figure class="student-avatar" aria-hidden="true">
                                                <span>?</span>
                                            </figure>

                                            <hgroup>
                                                <h3 id="melding-<?php echo $melding['id']; ?>-title">
                                                    Anonym student
                                                </h3>
                                                <time datetime="<?php echo htmlspecialchars($melding['dato']); ?>">
                                                    <?php echo htmlspecialchars($melding['dato']); ?>
                                                </time>
                                            </hgroup>

                                            <?php if ($rolle === 'guest'): ?>
                                                <button
                                                    type="button"
                                                    class="rapporter-btn"
                                                    aria-haspopup="dialog"
                                                    onclick="openReportModal(<?php echo $melding['id']; ?>)">
                                                    <span aria-hidden="true"></span> Rapporter
                                                </button>
                                            <?php endif; ?>
                                        </header>

                                        <p class="melding-innhold">
                                            <?php echo htmlspecialchars($melding['innhold']); ?>
                                        </p>

                                        <!-- Eksisterende svar -->
                                        <?php if ($melding['svar']): ?>
                                            <aside class="foreleser-svar" aria-label="Svar fra foreleser">
                                                <header>
                                                    <p class="svar-forfatter">
                                                        <span aria-hidden="true"></span>
                                                        <strong><?php echo htmlspecialchars($melding['svar']['forfatter']); ?></strong>
                                                    </p>
                                                    <time datetime="<?php echo htmlspecialchars($melding['svar']['dato']); ?>">
                                                        <?php echo htmlspecialchars($melding['svar']['dato']); ?>
                                                    </time>
                                                </header>
                                                <blockquote>
                                                    <p><?php echo htmlspecialchars($melding['svar']['tekst']); ?></p>
                                                </blockquote>
                                            </aside>
                                        <?php elseif ($rolle === 'foreleser' && $harTilgang): ?>
                                            <!-- Foreleser kan svare hvis det ikke finnes svar -->
                                            <form class="svar-form" method="POST">
                                                <fieldset>
                                                    <legend class="visually-hidden">Svar på melding</legend>
                                                    <input type="hidden" name="melding_id" value="<?php echo $melding['id']; ?>">

                                                    <p class="form-group">
                                                        <label for="svar-<?php echo $melding['id']; ?>">Ditt svar</label>
                                                        <input
                                                            id="svar-<?php echo $melding['id']; ?>"
                                                            type="text"
                                                            name="svar"
                                                            placeholder="Skriv ditt svar..."
                                                            required>
                                                    </p>
                                                </fieldset>

                                                <button type="submit">Svar</button>
                                            </form>
                                        <?php endif; ?>
                                    </article>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                </section>

                <nav aria-label="Tilbakenavigasjon">
                    <a href="guest_hjemmeside.php" class="back-link">
                        <span aria-hidden="true">←</span> Tilbake til emneoversikt
                    </a>
                </nav>
            </article>

        <?php else: ?>
            <article class="feilmelding-container">
                <header>
                    <h1>Emne ikke funnet</h1>
                </header>
                <p>Beklager, vi fant ikke emnet du leter etter.</p>
                <nav aria-label="Navigasjon">
                    <a href="guest_hjemmeside.php" class="back-link">
                        <span aria-hidden="true">←</span> Tilbake til emneoversikt
                    </a>
                </nav>
            </article>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../footer.php'; ?>

    <!-- Rapport Modal (kun for guest) -->
    <?php if ($rolle === 'guest' && $emne): ?>
        <dialog id="reportModal" aria-labelledby="report-title" aria-describedby="report-desc">
            <article class="modal-content">
                <header>
                    <h2 id="report-title">
                        <span aria-hidden="true"></span> Rapporter melding
                    </h2>
                    <p id="report-desc">Beskriv hvorfor denne meldingen bør gjennomgås.</p>
                </header>

                <form method="POST">
                    <input type="hidden" name="rapporter_id" id="reportMeldingId">

                    <fieldset>
                        <legend class="visually-hidden">Rapportdetaljer</legend>

                        <p class="form-group">
                            <label for="rapport_grunn">Begrunnelse</label>
                            <textarea
                                id="rapport_grunn"
                                name="rapport_grunn"
                                rows="4"
                                placeholder="Beskriv hvorfor denne meldingen er upassende..."
                                required></textarea>
                        </p>
                    </fieldset>

                    <footer class="modal-actions">
                        <button type="button" class="btn-secondary" onclick="closeReportModal()">Avbryt</button>
                        <button type="submit" class="btn-danger">Send rapport</button>
                    </footer>
                </form>
            </article>
        </dialog>

        <script>
            const reportModal = document.getElementById('reportModal');

            function openReportModal(meldingId) {
                document.getElementById('reportMeldingId').value = meldingId;
                reportModal.showModal();
            }

            function closeReportModal() {
                reportModal.close();
            }

            reportModal.addEventListener('click', function(event) {
                if (event.target === reportModal) {
                    closeReportModal();
                }
            });

            reportModal.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeReportModal();
                }
            });
        </script>
    <?php endif; ?>

    <!-- Suksess toast -->
    <output id="successToast" class="success-toast" role="status" aria-live="polite" hidden>
        <p>✓ Handlingen ble utført!</p>
    </output>

    <script>
        <?php if ($meldingSendt || $svarSendt || $rapportSendt): ?>
            const toast = document.getElementById('successToast');
            toast.removeAttribute('hidden');
            toast.classList.add('active');

            setTimeout(function() {
                toast.classList.remove('active');
                setTimeout(function() {
                    toast.setAttribute('hidden', '');
                }, 300);
            }, 3000);
        <?php endif; ?>
    </script>
</body>

</html>