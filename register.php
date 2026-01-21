<!DOCTYPE html>
<html lang="nb">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreringsside</title>
</head>
<body>
<header>
    <div class="logo">StudiePortal</div>

    <nav>
        <a href="?page=emne" class="<?php echo $currentPage === 'emne' ? 'active' : ''; ?>">Emner</a>
        <a href="?page=meldinger" class="<?php echo $currentPage === 'meldinger' ?  'active' : ''; ?>">Meldinger</a>
    </nav>

    <div class="user-section">
        <span class="current-page"><?php echo ucfirst($currentPage); ?></span>

        <?php if (isset($_SESSION['user'])): ?>
            <!-- Innlogget bruker -->
            <div class="user-profile">
                <span><?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
            </div>
            <a href="? logout=1" class="login-btn">Logg ut</a>
        <?php else: ?>
            <!-- Ikke innlogget -->
            <a href="login.php" class="login-btn">Logg inn</a>
        <?php endif; ?>
    </div>
</header>
    <h1>Registrer deg her!</h1>
    
    <form method="POST" action="#">
        <label>Navn:</label>
        <input type="text" name="name" required>
        <br><br>
        
        <label>Epost:</label>
        <input type="email" name="email" required>
        <br><br>
        
        <label>Passord:</label>
        <input type="password" name="password" required>
        <br><br>
        
        <label>Bekreft Passord:</label>
        <input type="password" name="confirm_password" required>
        <br><br>
        
        <button type="submit">Register</button>
    </form>
    
    <p>Har du allerede en bruker? <a href="login.php">Logg inn her</a></p>
</body>
</html>

