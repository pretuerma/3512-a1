<?php
require __DIR__ . '/includes/config.inc.php';
require __DIR__ . '/includes/db-classes.inc.php';

$userId = (int)($_GET['userId'] ?? 0);

try {
  $pdo   = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS)); 
  $users = new UsersDB($pdo);
  $comps = new CompaniesDB($pdo);           
  $port  = new PortfolioDB($pdo, $comps);    

  $user = $users->getById($userId);
  $rows = $port->getRowsForUser($userId);

  $companies = count($rows);
  $shares = 0;
  $total  = 0.0;
  foreach ($rows as $r) {
    $shares += (float)$r['amount'];
    $total  += (float)$r['amount'] * (float)$r['last_close'];
  }
} catch (Exception $e) 
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
  <h1>Portfolio Summary — <?= $user['firstname'] . ' ' . $user['lastname'] ?></h1>

  <table border="1" cellpadding="6" cellspacing="0" width="100%">
    <tr><th>Companies</th><th># Shares</th><th>Total Value</th></tr>
    <tr>
      <td><?= $companies ?></td>
      <td><?= $shares ?></td>
      <td>$<?= number_format($total, 2) ?></td>
    </tr>
  </table>

  <br>

  <table border="1" cellpadding="6" cellspacing="0" width="100%">
    <tr>
      <th>Symbol</th><th>Name</th><th>Sector</th>
      <th>Amount</th><th>Last Close</th><th>Value</th>
    </tr>
    <?php foreach ($rows as $r): $val = (float)$r['amount'] * (float)$r['last_close']; ?>
      <tr>
        <td><a href="company.php?symbol=<?= $r['symbol'] ?>" target="rightFrame"><?= $r['symbol'] ?></a></td>
        <td><?= $r['name'] ?></td>
        <td><?= $r['sector'] ?></td>
        <td><?= $r['amount'] ?></td>
        <td>$<?= number_format($r['last_close'], 2) ?></td>
        <td>$<?= number_format($val, 2) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
