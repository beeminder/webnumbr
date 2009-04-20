<?php
print '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>webGraphr - Search Results for <?php print htmlspecialchars($_REQUEST['query']) ?></title>
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/reset/reset-min.css" />
    <link rel="stylesheet" href="/style.css" type='text/css' />  
    <link rel="stylesheet" href="style.css" type='text/css' />  

    <link rel="shortcut icon" href="images/webGraphr-favicon.png" type="image/x-icon" />
    <link rel="icon" href="images/webGraphr-favicon.png" type="image/x-icon" />

  </head>
  <body>
    <div id='container'>
      <div id='header'>
        <a href='.'><img id='smalllogo' src="images/webGraphr-banner-100.png" alt="logo" /></a>
      </div>

      <div class="content">

        <h1>Search Results</h1>

        <form action=''>
          <div>
              <input name='query' value='<?php print htmlspecialchars($_REQUEST['query']) ?>' style='width:90%' />
              <input type='submit' value='Search' />
          </div>
        </form>
        <div id='searchResults'>
          <ul class='searchresults'>
<?php
require("db.inc");
$stmt = $PDO->prepare("SELECT name, id, url FROM graphs WHERE name LIKE CONCAT('%', :query, '%') OR url LIKE CONCAT('%', :query, '%')");
$stmt->execute(array("query" => $_REQUEST['query']));

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$ids = array();
foreach ($data as $row) {
    $url = substr($row['url'], 0, 30);
    if (strlen($row['url']) > 30) $url .= "...";
    $ids[] = $row['id'];

    print "            <li><a href='graph?id=" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</a> (" . htmlspecialchars($url) . ")</li>\n";
}
?>
          </ul>
        </div>

        <div>
          Number of Results : <span id='numResults'><?php print $stmt->rowCount() ?></span>. 
        </div>
        
        <div>
          See these <a href="graph?id=<?php print htmlspecialchars(urlencode(implode(",", $ids))) ?>">all on the same graph</a>.
        </div>
      </div>
    </div>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-149816-4");
pageTracker._trackPageview();
} catch(err) {}
</script>

  </body>
</html>
