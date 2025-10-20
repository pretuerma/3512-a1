<?php
require __DIR__ . '/../includes/config.inc.php';
require __DIR__ . '/../includes/db-classes.inc.php';

$pdo   = DatabaseHelper::createConnection([DBCONNSTRING, DBUSER, DBPASS]);
$comps = new CompaniesDB($pdo);
$port  = new PortfolioDB($pdo, $comps);


if (isset($_GET['ref'])) {
  $userId = (int) $_GET['ref'];
  $rows = $port->getRowsForUser($userId);

  if ($rows) {
    $data = $rows;                  
  } else {
    header("Location: api-error.php");
    exit;
  }
}

header('Content-Type: application/json');
echo json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
