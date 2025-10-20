<?php
require __DIR__ . '/includes/config.inc.php';
require __DIR__ . '/includes/db-classes.inc.php';

try {
  $pdo   = DatabaseHelper::createConnection(array(DBCONNSTRING, DBUSER, DBPASS)); 
  $users = (new UsersDB($pdo))->getAll();
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
  <title>Portfolio Project</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Portfolio Project</h1>
<p>
  <a href="index.php">Home</a> |
  <a href="about.php">About</a> |
  <a href="api.php">APIs</a>
</p>
<hr>

<table width="100%" cellspacing="0">
  <tr>
    <td width="35%" valign="top" style="border:2px solid #000; ">
      <h2>Customers</h2>
      <table width="100%">
        <tr><th align="left">Name</th><th align="left">Portfolio</th></tr>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= $u['lastname'] . ', ' . $u['firstname'] ?></td> 
            <td><a href="portfolio.php?userId=<?= (int)$u['id'] ?>" target="rightFrame">View Portfolio</a></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </td>

    <td valign="top">
      <iframe
        name="rightFrame"
        width="100%"
        height="700"
        frameborder="2"
        srcdoc="<p style='text-align:center; padding-top:2em;'>Select a customer to view their portfolio.</p>">
      </iframe>
    </td>
  </tr>
</table>
</body>
</html>
