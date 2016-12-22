<?php
$tt = 0;
require_once __DIR__ . '/config.php';

//Cloudflare country code pull
$country_code = $_SERVER['HTTP_CF_IPCOUNTRY'];

$dbh = pg_connect("dbname=$pgdb user=$pguser password=$pgpass");
$dbh || die('Error in connection: ' . pg_last_error());

$hidden = isset($_GET['hidden']) ? $_GET['hidden'] : null;
if ($hidden == 'true') {
  $sql = "SELECT * FROM pods WHERE hidden <> 'no' ORDER BY uptimelast7 DESC";
} else {
  $sql = "SELECT * FROM pods WHERE adminrating <> -1 AND hidden <> 'yes' AND signup = 1 ORDER BY uptimelast7 DESC";
}
$result = pg_query($dbh, $sql);
$result || die('Error in SQL query: ' . pg_last_error());

$numrows = pg_num_rows($result);
?>

<meta property="og:title" content="<?php echo $numrows; ?> Federated Pods listed, Come see the privacy aware social networks."/>
<div class="hidden-sm-up">Scroll right or rotate device for more</div>
<table class="table table-striped table-sm tablesorter table-hover" id="myTable">
  <thead class="thead-inverse">
  <tr>
    <th><a data-toggle="tooltip" data-placement="bottom" title="A pod is a site for you to set up your account.">Pod</a></th>
    <th><a data-toggle="tooltip" data-placement="bottom" title="Percent of the time the pod is online for <?php echo date('F') ?>.">Uptime %</a></th>
    <th><a data-toggle="tooltip" data-placement="bottom" title="Number of users active last 6 months on this pod.">Active Users</a></th>
    <th><a data-toggle="tooltip" data-placement="bottom" title="Pod location, based on IP Geolocation">Location</a></th>
    <th><a data-toggle="tooltip" data-placement="bottom" title="External Social Networks this pod can post to">Services Offered</a></th>
  </tr>
  </thead>
  <tbody>


  <?php
  while ($row = pg_fetch_array($result)) {
    $tt = $tt + 1;
    if ($row['secure'] == 'true') {
      $method = 'https://';
      $class  = 'text-success';
//$tip="This pod uses SSL encryption for traffic.";
    } else {
      $method = 'http://';
      $class  = 'red';
//$tip="This pod does not offer SSL";
    }
    $verdiff  = str_replace('.', '', $row['masterversion']) - str_replace('.', '', $row['shortversion']);
    $pod_name = htmlentities($row['name'], ENT_QUOTES);
    $tip      = sprintf(
      'This %1$s pod %2$s has been watched for %3$s months and with an uptime of %4$s%% this month. On a scale of 100 this pod is a %5$s right now',
      $row['softwarename'],
      $pod_name,
      $row['monthsmonitored'],
      $row['uptimelast7'],
      $row['score']
    );
    echo '<tr><td><div title="' . $tip . '" data-toggle="tooltip" data-placement="bottom"><a class="' . $class . ' url" target="_self" href="' . $method . $row['domain'] . '">' . $row['domain'] . '</a></div></td>';

    echo '<td>' . $row['uptimelast7'] . '%</td>';
    echo '<td data-toggle="tooltip" data-placement="bottom" title="active six months: ' . $row['active_users_halfyear'] . ', active one month: ' . $row['active_users_monthly'] . '">' . $row['active_users_halfyear'] . '</td>';
    if ($country_code == $row['country']) {
      echo '<td class="text-success" data-toggle="tooltip" data-placement="bottom" title="' . $row['whois'] . '"><b>' . $row['country'] . '</b></td>';
    } else {
      echo '<td data-toggle="tooltip" data-placement="bottom" title="' . $row['whois'] . '">' . $row['country'] . '</td>';
    }
    echo '<td>';
    if ($row['service_facebook'] === 't') {
      echo '<div class="smlogo smlogo-facebook"></div>';
    }
    if ($row['service_twitter'] === 't') {
      echo '<div class="smlogo smlogo-twitter"></div>';
    }
    if ($row['service_tumblr'] === 't') {
      echo '<div class="smlogo smlogo-tumblr"></div>';
    }
    if ($row['service_wordpress'] === 't') {
      echo '<div class="smlogo smlogo-wordpress"></div>';
    }
    if ($row['xmpp'] === 't') {
      echo '<div class="smlogo smlogo-xmpp"><img src="/images/icon-xmpp.png" width="16" height="16" title="XMPP chat server" alt="XMPP chat server"></div>';
    }
    echo '</td></tr>';
  }
  pg_free_result($result);
  pg_close($dbh);
  ?>
  </tbody>
</table>
