<?php
    $user = isset($_SESSION['session_data']) ? $_SESSION['session_data'] : null;
?>
<header role="banner">
    <nav aria-label="Hovednavigasjon">
        <a href="/" class="logo">
            <span aria-hidden="true"></span> Studentportal
        </a>
    </nav>

    <aside class="bruker-seksjon" aria-label="Brukerinformasjon">
        <?php if ($user): ?>
            <p class="bruker-info">
                <span class="bruker-rolle">
                    <?php
                if ($user != null):
                if ($user["role"] == "student") {
                echo "Student";
                }
                if ($user["role"] == "lecturer") {
                echo "Foreleser";
                }
                    ?>
                </span>
                    <strong class="bruker-navn"><?php echo htmlspecialchars($user["first_name"]); ?></strong>
                <?php endif; ?>
            </p>
            <a href="logout.php" class="btn-logout">Logg ut</a>
        <?php else: ?>
            <p class="guest-info">
                <span aria-hidden="true"></span> Gjest
            </p>
            <button  class="btn-login"><a href="/login">Logg inn</a></button>
        <?php endif; ?>
    </aside>
</header>
