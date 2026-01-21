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
    <header>
        <a href="guest_hjemmeside.php">
            <div class="logo">StudiePortal</div>
        </a>

        <nav>
            <a href="guest_hjemmeside.php" class="<?php echo $currentPage === 'emne' ? 'active' : ''; ?>">Emner</a>
            <a href="?page=meldinger" class="<?php echo $currentPage === 'meldinger' ?  'active' : ''; ?>">Meldinger</a>
        </nav>

        <div class="user-section">
            <span class="current-page"><?php echo ucfirst($currentPage); ?></span>

            <?php if (isset($_SESSION['user'])): ?>
                <div class="user-profile">
                    <span><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
                </div>
                <a href="? logout=1" class="login-btn">Logg ut</a>
            <?php else: ?>
                <a href="login. php" class="login-btn">Logg inn</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <?php if ($emne): ?>
            <!-- Emne header med foreleser info -->
            <div class="emne-header">
                <div class="emne-info">
                    <span class="emne-kode"><?php echo htmlspecialchars(strtoupper($emne['kode'])); ?></span>
                    <h1><?php echo htmlspecialchars($emne['navn']); ?></h1>
                </div>

                <div class="foreleser-kort">
                    <img src="<?php echo htmlspecialchars($emne['foreleser']['bilde']); ?>" alt="Foreleser">
                    <div class="foreleser-info">
                        <span class="label">Foreleser</span>
                        <div class="navn"><?php echo htmlspecialchars($emne['foreleser']['navn']); ?></div>
                        <div class="email"><?php echo htmlspecialchars($emne['foreleser']['email']); ?></div>
                    </div>
                </div>
            </div>

            <!-- Meldinger seksjon -->
            <div class="meldinger-header">
                <h2>Meldinger</h2>
                <span class="melding-count"><?php echo count($meldinger); ?> meldinger</span>
            </div>

            <?php foreach ($meldinger as $melding): ?>
                <div class="melding-kort">
                    <div class="melding-header">
                        <div class="student-info">
                            <div class="student-avatar">
                                <?php echo strtoupper(substr($melding['student'], 0, 1)); ?>
                            </div>
                            <div>
                                <div class="student-navn"><?php echo htmlspecialchars($melding['student']); ?></div>
                                <div class="melding-dato"><?php echo htmlspecialchars($melding['dato']); ?></div>
                            </div>
                        </div>
                        <button class="rapporter-btn" onclick="openReportModal(<?php echo $melding['id']; ?>, '<?php echo htmlspecialchars($melding['student']); ?>')">
                            Rapporter
                        </button>
                    </div>

                    <div class="melding-innhold">
                        <?php echo htmlspecialchars($melding['innhold']); ?>
                    </div>

                    <!-- Eksisterende kommentarer -->
                    <?php if (!empty($melding['kommentarer'])): ?>
                        <div class="kommentarer">
                            <?php foreach ($melding['kommentarer'] as $kommentar): ?>
                                <div class="kommentar">
                                    <div class="kommentar-forfatter"><?php echo htmlspecialchars($kommentar['forfatter']); ?></div>
                                    <div class="kommentar-tekst"><?php echo htmlspecialchars($kommentar['tekst']); ?></div>
                                    <div class="kommentar-dato"><?php echo htmlspecialchars($kommentar['dato']); ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Legg til kommentar -->
                    <form class="kommentar-form" method="POST">
                        <input type="hidden" name="melding_id" value="<?php echo $melding['id']; ?>">
                        <input type="text" name="kommentar" class="kommentar-input" placeholder="Skriv en kommentar..." required>
                        <button type="submit" class="kommentar-btn">Send</button>
                    </form>
                </div>
            <?php endforeach; ?>

            <a href="guest_hjemmeside.php" class="back-link">← Tilbake til emneoversikt</a>

        <?php else: ?>
            <div class="pin-section">
                <h2>Emne ikke funnet</h2>
                <p>Beklager, vi fant ikke emnet du leter etter. </p>
                <a href="guest_hjemmeside.php" class="back-link">← Tilbake til emneoversikt</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Rapport Modal -->
    <div class="modal-overlay" id="reportModal">
        <div class="modal">
            <h3>Rapporter melding</h3>
            <p>Du rapporterer en melding fra <strong id="reportStudentName"></strong></p>
            <form method="POST">
                <input type="hidden" name="rapporter_id" id="reportMeldingId">
                <textarea name="rapport_grunn" placeholder="Beskriv hvorfor denne meldingen er upassende..." required></textarea>
                <div class="modal-actions">
                    <button type="button" class="modal-cancel" onclick="closeReportModal()">Avbryt</button>
                    <button type="submit" class="modal-submit">Send rapport</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Suksess toast -->
    <div class="success-toast" id="successToast">
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