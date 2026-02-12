<?php
// api/test.php, for å teste at alt fungerer
// VIKTIG: Slett før innlev

require_once 'Includes/db.php';

$results = [];

// ---------- 1. DATABASETILKOBLING ----------
try {
    $database = new Database('localhost', 'datasikkerhet', 'root', 'dev');
    $db = $database->getDb();
    $db->query("SELECT 1");
    $results['database'] = ['status' => '✅ OK', 'melding' => 'Koblet til databasen'];
} catch (Exception $e) {
    $results['database'] = ['status' => '❌ FEIL', 'melding' => $e->getMessage()];
}

// ---------- 2. TABELLER FINNES ----------
$tabeller = ['users', 'students', 'lecturers', 'courses', 'messages', 'replies', 'comments', 'reports'];
foreach ($tabeller as $tabell) {
    try {
        $db->query("SELECT 1 FROM $tabell LIMIT 1");
        $results['tabeller'][$tabell] = '✅ Finnes';
    } catch (Exception $e) {
        $results['tabeller'][$tabell] = '❌ Mangler';
    }
}

// ---------- 3. ANTALL RADER ----------
foreach (['users', 'students', 'courses'] as $tabell) {
    try {
        $count = $db->query("SELECT COUNT(*) FROM $tabell")->fetchColumn();
        $results['rader'][$tabell] = "$count rader";
    } catch (Exception $e) {
        $results['rader'][$tabell] = '❌ Feil';
    }
}

// ---------- 4. API-FILER FINNES ----------
$filer = ['helpers.php', 'login.php', 'register.php', 'logout.php', 'profile.php', 'messages.php', 'subjects.php'];
foreach ($filer as $fil) {
    $results['filer'][$fil] = file_exists(__DIR__ . '/' . $fil) ? '✅ Finnes' : '❌ Mangler';
}

// ---------- 5. PHP-UTVIDELSER ----------
$extensions = ['pdo', 'pdo_mysql', 'json'];
foreach ($extensions as $ext) {
    $results['php'][$ext] = extension_loaded($ext) ? '✅ Lastet' : '❌ Mangler';
}

// ---------- VIS RESULTAT ----------
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>API Test</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        h2   { color: #569cd6; }
        h3   { color: #9cdcfe; margin-top: 20px; }
        pre  { background: #252526; padding: 10px; border-radius: 4px; }
        .ok  { color: #4ec9b0; }
        .err { color: #f44747; }
        .warn{ color: #dcdcaa; }
    </style>
</head>
<body>
    <h2>🔍 API Testresultater</h2>

    <h3>1. Databasetilkobling</h3>
    <pre class="<?= str_contains($results['database']['status'], '✅') ? 'ok' : 'err' ?>">
<?= $results['database']['status'] . ' — ' . $results['database']['melding'] ?>
    </pre>

    <h3>2. Tabeller</h3>
    <pre>
<?php foreach ($results['tabeller'] as $tabell => $status): ?>
<?= str_pad($tabell, 15) . $status . "\n" ?>
<?php endforeach; ?>
    </pre>

    <h3>3. Antall rader (dummy-data sjekk)</h3>
    <pre>
<?php foreach ($results['rader'] as $tabell => $count): ?>
<?= str_pad($tabell, 15) . $count . "\n" ?>
<?php endforeach; ?>
    </pre>

    <h3>4. API-filer</h3>
    <pre>
<?php foreach ($results['filer'] as $fil => $status): ?>
<?= str_pad($fil, 20) . $status . "\n" ?>
<?php endforeach; ?>
    </pre>

    <h3>5. PHP-utvidelser</h3>
    <pre>
<?php foreach ($results['php'] as $ext => $status): ?>
<?= str_pad($ext, 15) . $status . "\n" ?>
<?php endforeach; ?>
    </pre>

    <h3>⚠️ Husk</h3>
    <pre class="warn">Slett eller beskytt test.php før innlevering!</pre>
</body>
</html>