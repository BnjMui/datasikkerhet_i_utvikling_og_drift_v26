<?php
// Hent brukerinfo hvis ikke allerede satt
if (!isset($bruker)) {
    $bruker = isset($_SESSION['user']) ? $_SESSION['user'] : null;
}
if (!isset($rolle)) {
    $rolle = ($bruker && isset($bruker['rolle'])) ? $bruker['rolle'] : 'guest';
}

// Hent brukernavn trygt
$brukerNavn = ($bruker && isset($bruker['navn'])) ? $bruker['navn'] : '';
?>
<header role="banner">
    <nav aria-label="Hovednavigasjon">
        <a href="./guest_hjemmeside.php" class="logo">
            <span aria-hidden="true"></span> Studentportal
        </a>
    </nav>

    <aside class="bruker-seksjon" aria-label="Brukerinformasjon">
        <?php if ($bruker && $rolle !== 'guest'): ?>
            <p class="bruker-info">
                <span class="bruker-rolle">
                    <?php
                    if ($rolle === 'student') {
                        echo '<span aria-hidden="true"></span> Student';
                    } elseif ($rolle === 'foreleser') {
                        echo '<span aria-hidden="true"></span> Foreleser';
                    }
                    ?>
                </span>
                <?php if (!empty($brukerNavn)): ?>
                    <strong class="bruker-navn"><?php echo htmlspecialchars($brukerNavn); ?></strong>
                <?php endif; ?>
            </p>
            <a href="../logout.php" class="btn-logout">Logg ut</a>
        <?php else: ?>
            <p class="guest-info">
                <span aria-hidden="true"></span> Gjest
            </p>
            <a href="login.php" class="btn-login">Logg inn</a>
        <?php endif; ?>
    </aside>
</header>