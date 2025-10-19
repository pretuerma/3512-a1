<?php
require __DIR__ . '/includes/config.inc.php';
require __DIR__ . '/includes/db-classes.inc.php';

try {
    $pdo   = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS));
    $comps = new CompaniesDB($pdo);

    $symbol = trim($_GET['symbol'] ?? '');
    $company = $comps->getBySymbol($symbol);
  
    $kpi = DatabaseHelper::runQuery(
        $pdo,
        "SELECT MAX(high) AS hi, 
        MIN(low) AS lo, 
        SUM(volume) AS vol_sum, 
        AVG(volume) AS vol_avg
        FROM history 
        WHERE symbol = ?",
        [$company['symbol']])->fetch();
    
    $history = DatabaseHelper::runQuery(
        $pdo,
        "SELECT date, open, high, low, close, volume
        FROM history
        WHERE symbol = ?
        ORDER BY date DESC",
        [$company['symbol']])->fetchAll();

    }  
    catch (Exception $e) 
    { 
    die( $e->getMessage() ); 
    } 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Portfolio</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <h1><?= $company['symbol'] ?></h1>

    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <tr><th colspan="2">Company Info</th></tr>
        <tr><td>Symbol</td><td><?= $company['symbol'] ?></td></tr>
        <tr><td>Sector</td><td><?= $company['sector'] ?></td></tr>
        <tr><td>Subindustry</td><td><?= $company['subindustry'] ?></td></tr>
        <tr><td>Exchange</td><td><?= $company['exchange'] ?></td></tr>
        <tr><td>Address</td><td><?= $company['address'] ?? '' ?></td></tr>
        <tr>
            <td>Website</td>
            <td>
                <a href="<?= $company['website'] ?>" target="_blank"><?= $company['website'] ?></a>
            </td>
        </tr>
        <tr><td>Description</td><td><?= $company['description']?></td></tr>
    </table>

    <br>

    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <tr><td>History High</td><td>$<?= number_format((float)($kpi['hi'] ?? 0), 2) ?></td></tr>
        <tr><td>History Low</td><td>$<?= number_format((float)($kpi['lo'] ?? 0), 2) ?></td></tr>
        <tr><td>Total Volume</td><td><?= number_format((float)($kpi['vol_sum'] ?? 0)) ?></td></tr>
        <tr><td>Average Volume</td><td><?= number_format((float)($kpi['vol_avg'] ?? 0)) ?></td></tr>
    </table>

</body>
</html>