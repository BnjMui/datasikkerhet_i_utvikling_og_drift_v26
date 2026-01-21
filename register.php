<!DOCTYPE html>
<html lang="nb">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreringsside</title>
</head>
<body>
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

