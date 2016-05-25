<?php
mysql_connect("db.cchits.net", "cchits_net", "qebHOXdiv008");
mysql_select_db("cchits");
$period = 30;
$count = 100;
$aperiod = array('30' => '', '90' => '', '120' => '', '150' => '', '180' => '');
if (isset($_GET['period'])) {
  switch ($_GET['period']) {
  case '30':
  case '90':
  case '120':
  case '150':
  case '180':
    $period = $_GET['period'];
    break;
  default:
    $period = 30;
  }
}
if (isset($_GET['rows'])) {
  $count = $_GET['rows'];
}

$aperiod[$period] = ' selected';
if (isset($_GET['count']) && 0 + $_GET['count'] > 0) {
  $count = 0 + $_GET['count'];
}
$date = date("Ymd", strtotime("-1 day"));
$pdate = date("Ymd", strtotime("-{$period} days"));
$sql = "
SELECT t.intTrackID, t.intArtistID, t.strTrackName, strArtistName, t.intChartPlace, count( t.intTrackID ) * ( ( 100 - ( IFNULL( MAX( intShowCount ) , 0 ) *5 ) ) /100 ) AS decVotes
FROM tracks AS t
LEFT JOIN (
  SELECT vt.intTrackID
  FROM votes AS vt
  WHERE vt.datTimeStamp <= '$date'
  AND vt.datTimeStamp >= '$pdate'
) AS v ON v.intTrackID = t.intTrackID
LEFT JOIN (
  SELECT st.intTrackID, count( st.intTrackID ) AS intShowCount
  FROM showtracks AS st, shows AS s
  WHERE (
    (
      s.enumShowType = 'weekly' AND s.intShowUrl <= '" . $date . "'
      AND s.intShowUrl > '20000000'
    ) OR (
      s.enumShowType = 'monthly' AND s.intShowUrl <= '" . substr($date, 0, 6) . "'
      AND s.intShowUrl > '200000'
    )
  )
  AND s.intShowID = st.intShowID
  GROUP BY intTrackID
) AS s ON s.intTrackID = t.intTrackID
LEFT JOIN (
  SELECT ar.strArtistName as strArtistName, ar.intArtistID
  FROM artists AS ar
) AS a ON a.intArtistID = t.intArtistID
WHERE t.isApproved =1
GROUP BY t.intTrackID
ORDER BY decVotes DESC , intTrackID ASC
LIMIT 0 , " . $count;
//echo $sql . "<br/>";
$qry = mysql_query($sql);
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>New Chart - for review</title></head><body>";
echo '<form action="" method="get">
<select name="period">
<option value=30' . $aperiod['30'] . '>30 days</option>
<option value=90' . $aperiod['90'] . '>90 days</option>
<option value=120' . $aperiod['120'] . '>120 days</option>
<option value=150' . $aperiod['150'] . '>150 days</option>
<option value=180' . $aperiod['180'] . '>180 days</option>
</select>
Rows: <input type="text" name="rows" value="' . $count . '"> <input type="submit" value="Go">
</form>';
if (mysql_errno() > 0 || mysql_num_rows($qry) == 0) {
  die("Errors occurred " . mysql_error());
}
echo "<table>";
$row = 0;
while ($data = mysql_fetch_array($qry)) {
  if ($row++ % 20 == 0) {
    echo "<tr><th>Track name</th><th>Artist Name</th><th>Current Chart Place</th><th>New Chart Place</th><th>Votes</th></tr>\r\n";
  }
  echo "<tr><td><a href=\"/track/{$data['intTrackID']}\">{$data['strTrackName']}</a></td><td>{$data['strArtistName']}</td><td>{$data['intChartPlace']}</td><td>$row</td><td>{$data['decVotes']}</td></tr>\r\n";
}
echo "</table>
</body>
</html>";
