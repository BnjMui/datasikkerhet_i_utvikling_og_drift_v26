<?php
// header.php
?>
<!-- HEADER -->
<header>
    <a href="guest_hjemmeside.php">
        <div class="logo">StudiePortal</div>
    </a>

    <nav>
        <a href="?page=emne" class="<?php echo $currentPage === 'emne' ? 'active' :  ''; ?>">Emner</a>
        <a href="?page=meldinger" class="<?php echo $currentPage === 'meldinger' ?  'active' : ''; ?>">Meldinger</a>
    </nav>

    <div class="user-section">
        <span class="current-page"><?php echo ucfirst($currentPage); ?></span>

        <?php if (isset($_SESSION['user'])): ?>
            <div class="user-profile">
                <span><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
            </div>
            <a href="?logout=1" class="login-btn">Logg ut</a>
        <?php else: ?>
            <a href="login.php" class="login-btn">Logg inn</a>
        <?php endif; ?>
    </div>
</header>