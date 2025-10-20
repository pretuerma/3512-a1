<?php 
require __DIR__ . '/includes/config.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>APIs - Portfolio Project</title>
  <link rel="stylesheet" href="/3512-a1/pretu315/css/style.css?v=<?= time() ?>">
</head>

<body>

<header>
  <h1>Portfolio Project</h1>
  <nav>
    <a href="index.php">Home</a> 
    <a href="about.php">About</a> 
    <a href="api.php">APIs</a>
  </nav>
</header>

<hr>


<body>
  <main class="api-section">
  <h2>Available API Endpoints</h2>
  <table border="1" cellpadding="6" cellspacing="0">
    <tr><th>URL</th><th>Description</th></tr>

    <tr>
      <td><a href="api/company.php?" target="_blank">/api/companies.php?</a></td>
      <td>Returns all the companies</td>
    </tr>

    <tr>
      <td><a href="api/company.php?ref=AAPL" target="_blank">/api/companies.php?ref=AAPL</a></td>
      <td>Returns just the specified company</td>
    </tr>

    <tr>
      <td><a href="api/portfolio.php?ref=8" target="_blank">/api/portfolio.php?ref=8</a></td>
      <td>Returns the portfolio info for userID 8</td>
    </tr>

    <tr>
      <td><a href="api/history.php?ref=AAPL" target="_blank">/api/history.php?ref=AAPL</a></td>
      <td>Returns the history information for the AAPL sorted by ascending date</td>
    </tr>
  </table>
  </main>
</body>
</html>
