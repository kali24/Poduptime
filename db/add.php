<!-- /* Copyright (c) 2011, David Morley. This file is licensed under the Affero General Public License version 3 or later. See the COPYRIGHT file. */ -->
<?php
require_once __DIR__ . '/../logging.php';
require_once __DIR__ . '/../config.php';
$log = new Logging();
$log->lfile(__DIR__ . '/../' . $log_dir . '/add.log');
if (!($_domain = $_POST['domain'] ?? null)) {
  $log->lwrite('no domain given');
  die('no pod domain given');
}
if (!($_stats_apikey = $_POST['stats_apikey'] ?? null)) {
  $log->lwrite('no api given ' . $_domain);
  die('no API key for your stats');
}
if (strlen($_stats_apikey) < 14) {
  $log->lwrite('api key too short ' . $_domain);
  die('API key bad needs to be like m58978-80abdb799f6ccf15e3e3787ee');
}
if (!($_email = $_POST['email'] ?? null)) {
  $log->lwrite('no email given ' . $_domain);
  die('no email given');
}
if (!($_terms = $_POST['terms'] ?? null)) {
  $log->lwrite('terms link required ' . $_domain);
  die('no terms link');
}

$dbh = pg_connect("dbname=$pgdb user=$pguser password=$pgpass");
$dbh || die('Error in connection: ' . pg_last_error());

$sql    = 'SELECT domain, stats_apikey FROM pods';
$result = pg_query($dbh, $sql);
$result || die('Error in SQL query: ' . pg_last_error());

while ($row = pg_fetch_array($result)) {
  if ($row['domain'] === $_domain) {
    $log->lwrite('domain already exists ' . $_domain);
    die('domain already exists');
  }
  if ($row['stats_apikey'] === $_stats_apikey) {
    $log->lwrite('API key already exists ' . $_domain);
    die('API key already exists');
  }
}

$chss = curl_init();
curl_setopt($chss, CURLOPT_URL, 'https://' . $_domain . '/nodeinfo/1.0');
curl_setopt($chss, CURLOPT_POST, 0);
curl_setopt($chss, CURLOPT_HEADER, 0);
curl_setopt($chss, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($chss, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($chss, CURLOPT_NOBODY, 0);
$outputssl = curl_exec($chss);
curl_close($chss);

if (stristr($outputssl, 'nodeName')) {
  $log->lwrite('Your pod has ssl and is valid ' . $_domain);
  echo 'Your pod has ssl and is valid<br>';

  $sql    = 'INSERT INTO pods (domain, stats_apikey, email, terms) VALUES ($1, $2, $3, $4)';
  $result = pg_query_params($dbh, $sql, [$_domain, $_stats_apikey, $_email, $_terms]);
  $result || die('Error in SQL query: ' . pg_last_error());

  $to      = $adminemail;
  $subject = 'New pod added to ' . $_SERVER['HTTP_HOST'];
  $headers = ['From: ' . $_email, 'Reply-To: ' . $_email, 'Cc: ' . $_email];

  $message_lines = [
    'https://' . $_SERVER['HTTP_HOST'],
    'Stats Url: https://api.uptimerobot.com/getMonitors?format=json&noJsonCallback=1&customUptimeRatio=7-30-60-90&apiKey=' . $_stats_apikey,
    'Pod: https://' . $_SERVER['HTTP_HOST'] . '/db/pull.php?debug=1&domain=' . $_domain,
    '',
    'Your pod will not show up right away, as it needs to pass a few checks first.',
    'Give it a few hours!',
  ];

  @mail($to, $subject, implode("\r\n", $message_lines), implode("\r\n", $headers));

  echo 'Data successfully inserted! Your pod will be reviewed and live on the list in a few hours!';

} else {
  $log->lwrite('Could not validate your pod, check your setup! ' . $_domain);
  echo 'Could not validate your pod, check your setup!<br>Take a look at <a href="https://' . $_domain . '/nodeinfo/1.0">your /nodeinfo</a>';
}
$log->lclose();
