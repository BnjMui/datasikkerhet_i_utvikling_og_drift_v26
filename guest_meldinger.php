<?php
session_start();

// Inkluder emnedata
require_once 'emne_db.php';

// Simuler innlogget bruker
$_SESSION['user'] = [
    'name' => 'Ola Nordmann',
    'email' => 'ola@example.com',
];

// Hent nåværende side
$currentPage = 'meldinger';

// Hent emnekode fra URL og finn emnet
$emneKode = isset($_GET['kode']) ? $_GET['kode'] :  '';
$emne = finnEmne($emneKode);

// Simulerte meldinger fra studenter
$meldinger = [
    [
        'id' => 1,
        'student' => 'Erik Olsen',
        'dato' => '2026-01-20 14:30',
        'innhold' => 'Når er fristen for innlevering av oblig 2? ',
        'kommentarer' => [
            [
                'forfatter' => $emne ?  $emne['foreleser']['navn'] : 'Foreleser',
                'tekst' => 'Fristen er 1. februar kl. 23:59.',
                'dato' => '2026-01-20 15:00'
            ]
        ]
    ],
    [
        'id' => 2,
        'student' => 'Lisa Berg',
        'dato' => '2026-01-19 10:15',
        'innhold' => 'Kan noen forklare forskjellen mellom while og for-løkker?',
        'kommentarer' => []
    ],
    [
        'id' => 3,
        'student' => 'Magnus Vik',
        'dato' => '2026-01-18 09:00',
        'innhold' => 'Er det mulig å få ekstra veiledning før eksamen?',
        'kommentarer' => []
    ],
];

// Håndter kommentar-innsending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kommentar']) && isset($_POST['melding_id'])) {
    $nyKommentar = htmlspecialchars($_POST['kommentar']);
    $meldingId = $_POST['melding_id'];
}

// Håndter rapportering
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rapporter_id'])) {
    $rapportertId = $_POST['rapporter_id'];
    $rapportGrunn = isset($_POST['rapport_grunn']) ? htmlspecialchars($_POST['rapport_grunn']) : '';
}
?>
<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $emne ?  htmlspecialchars(strtoupper($emne['kode'])) : 'Emne ikke funnet'; ?> - Meldinger</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- HEADER -->
    <?php

    $currentPage = $_GET['page'] ?? 'emne'; // eller hva som passer

    include __DIR__ . '/header.php'; // trygg måte (absolutt sti)
    ?>

    <main>
        <?php if ($emne): ?>
            <!-- Emne header med foreleser info -->
            <section class="emne-header" aria-labelledby="emne-title">
                <div class="emne-info">
                    <p class="emne-kode"><?php echo htmlspecialchars(strtoupper($emne['kode'])); ?></p>
                    <h1 id="emne-title"><?php echo htmlspecialchars($emne['navn']); ?></h1>
                </div>

                <article class="foreleser-kort" aria-label="Foreleser">
                    <img src="<?php echo htmlspecialchars($emne['foreleser']['bilde']); ?>"
                        alt="Portrett av <?php echo htmlspecialchars($emne['foreleser']['navn']); ?>">

                    <div class="foreleser-info">
                        <p class="label">Foreleser</p>
                        <p class="navn"><?php echo htmlspecialchars($emne['foreleser']['navn']); ?></p>
                        <p class="email">
                            <a href="mailto:<?php echo htmlspecialchars($emne['foreleser']['email']); ?>">
                                <?php echo htmlspecialchars($emne['foreleser']['email']); ?>
                            </a>
                        </p>
                    </div>
                </article>
            </section>

            <!-- Meldinger seksjon -->
            <section class="meldinger" aria-labelledby="meldinger-title">
                <section class="meldinger-header">
                    <h2 id="meldinger-title">Meldinger</h2>
                    <p class="melding-count"><?php echo count($meldinger); ?> meldinger</p>
                </section>

                <?php foreach ($meldinger as $melding): ?>
                    <article class="melding-kort" aria-labelledby="melding-<?php echo $melding['id']; ?>-title">
                        <section class="melding-header">
                            <div class="student-info">
                                <div class="student-avatar" aria-hidden="true">
                                    <?php echo strtoupper(substr($melding['student'], 0, 1)); ?>
                                </div>

                                <div>
                                    <h3 id="melding-<?php echo $melding['id']; ?>-title" class="student-navn">
                                        <?php echo htmlspecialchars($melding['student']); ?>
                                    </h3>

                                    <time class="melding-dato" datetime="<?php echo htmlspecialchars($melding['dato']); ?>">
                                        <?php echo htmlspecialchars($melding['dato']); ?>
                                    </time>
                                </div>
                            </div>

                            <button
                                type="button"
                                class="rapporter-btn"
                                onclick="openReportModal(<?php echo $melding['id']; ?>, '<?php echo htmlspecialchars($melding['student']); ?>')">
                                Rapporter
                            </button>
                        </section>

                        <p class="melding-innhold">
                            <?php echo htmlspecialchars($melding['innhold']); ?>
                        </p>

                        <!-- Eksisterende kommentarer -->
                        <?php if (!empty($melding['kommentarer'])): ?>
                            <section class="kommentarer" aria-label="Kommentarer">
                                <ul class="kommentar-liste">
                                    <?php foreach ($melding['kommentarer'] as $kommentar): ?>
                                        <li class="kommentar">
                                            <p class="kommentar-forfatter">
                                                <?php echo htmlspecialchars($kommentar['forfatter']); ?>
                                            </p>
                                            <p class="kommentar-tekst">
                                                <?php echo htmlspecialchars($kommentar['tekst']); ?>
                                            </p>
                                            <time class="kommentar-dato" datetime="<?php echo htmlspecialchars($kommentar['dato']); ?>">
                                                <?php echo htmlspecialchars($kommentar['dato']); ?>
                                            </time>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </section>
                        <?php endif; ?>

                        <!-- Legg til kommentar -->
                        <form class="kommentar-form" method="POST">
                            <input type="hidden" name="melding_id" value="<?php echo $melding['id']; ?>">

                            <label for="kommentar-<?php echo $melding['id']; ?>" class="visually-hidden">
                                Skriv en kommentar til melding fra <?php echo htmlspecialchars($melding['student']); ?>
                            </label>
                            <input
                                id="kommentar-<?php echo $melding['id']; ?>"
                                type="text"
                                name="kommentar"
                                class="kommentar-input"
                                placeholder="Skriv en kommentar..."
                                required>

                            <button type="submit" class="kommentar-btn">Send</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </section>

            <nav aria-label="Tilbake">
                <a href="guest_hjemmeside.php" class="back-link">← Tilbake til emneoversikt</a>
            </nav>

        <?php else: ?>
            <section class="pin-section" aria-labelledby="not-found-title">
                <h1 id="not-found-title">Emne ikke funnet</h1>
                <p>Beklager, vi fant ikke emnet du leter etter.</p>

                <nav aria-label="Tilbake">
                    <a href="guest_hjemmeside.php" class="back-link">← Tilbake til emneoversikt</a>
                </nav>
            </section>
        <?php endif; ?>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Rapport Modal (dialog) -->
    <div class="modal-overlay" id="reportModal" hidden>
        <section
            class="modal"
            role="dialog"
            aria-modal="true"
            aria-labelledby="report-title"
            aria-describedby="report-desc">
            <h2 id="report-title">Rapporter melding</h2>
            <p id="report-desc">Du rapporterer en melding fra <strong id="reportStudentName"></strong></p>

            <form method="POST">
                <input type="hidden" name="rapporter_id" id="reportMeldingId">

                <label for="rapport_grunn" class="visually-hidden">Begrunnelse</label>
                <textarea id="rapport_grunn" name="rapport_grunn" placeholder="Beskriv hvorfor denne meldingen er upassende..." required></textarea>

                <div class="modal-actions">
                    <button type="button" class="modal-cancel" onclick="closeReportModal()">Avbryt</button>
                    <button type="submit" class="modal-submit">Send rapport</button>
                </div>
            </form>
        </section>
    </div>

    <!-- Suksess toast -->
    <div class="success-toast" id="successToast" role="status" aria-live="polite" hidden>
        ✓ Handlingen ble utført!
    </div>

    <script>
        function openReportModal(meldingId, studentNavn) {
            document.getElementById('reportModal').classList.add('active');
            document.getElementById('reportMeldingId').value = meldingId;
            document.getElementById('reportStudentName').textContent = studentNavn;
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.remove('active');
        }

        document.getElementById('reportModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeReportModal();
            }
        });

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            document.getElementById('successToast').classList.add('active');
            setTimeout(function() {
                document.getElementById('successToast').classList.remove('active');
            }, 3000);
        <?php endif; ?>
    </script>
</body>

</html>