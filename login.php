<!DOCTYPE html>
<html lang="nb">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Registeringsside</title>
</head>
<body>
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