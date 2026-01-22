<!DOCTYPE html>
<html lang="nb">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Registeringsside</title>
</head>
<body>
<header>
    <div class="logo">StudiePortal</div>

    <nav>
        <a href="?page=emne" class="<?php echo $currentPage === 'emne' ? 'active' : ''; ?>">Emner</a>
        <a href="?page=meldinger" class="<?php echo $currentPage === 'meldinger' ?  'active' : ''; ?>">Meldinger</a>
    </nav>

    <div class="user-section">
       

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
    <h1>Logg Inn</h1>
    
    <form method="POST" action="#">
        <label>Epost:</label>
        <input type="email" name="email" required>
        <br><br>
        
        <label>Passord:</label>
        <input type="password" name="password" required>
        <br><br>
        
        <button type="submit">Logg inn</button>
    </form>
    
    <p>Har du ikke en bruker? <a href="register.php">Registrer deg her</a></p>
</body>
</html>