<?php
require __DIR__ . '/../includes/config.inc.php';
require __DIR__ . '/../includes/db-classes.inc.php';

$pdo = DatabaseHelper::createConnection([DBCONNSTRING, DBUSER, DBPASS]);
$histDb = new HistoryDB($pdo);

if (isset($_GET['ref'])) {
  $sym  = trim($_GET['ref']);
  $rows = $histDb->getHistoryAsc($sym);
  
  if ($rows) {
    $data = $rows;                  
  } else {
    header("Location: api-error.php");
    exit;
  }
}

header('Content-Type: application/json');
echo json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
