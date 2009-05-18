<?php
print '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>webNumbr: Can I get your Numbr?</title>
    <link rel="stylesheet" href="style.css" type='text/css' />  
    
  </head>
  <body>
    <div id='container'>
    <?php include ("tweet.inc"); ?>
      <div id='header'>
        <a href='.'><img id='logo' src="images/webNumbr-banner-100.png" alt="logo" /></a>
      </div>

      <div class="content">

        <p class="center">
        Numbers are all over the web, but they are hard to access. webNumbr does 3 things : Extracts numbers from any webpage, gives you a short name for them, and keeps a history of them.
        </p>
        <p class="center">
<?php
require ("db.inc");
$stmt = $PDO->prepare("SELECT COUNT(name) as count FROM numbrs");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = (int) $data[0]['count'];
?>
        There are <b id="numbrCount"><?php print $count ?></b> numbrs and counting.
        </p>

        <h1 id="start">
          Start a Numbr
        </h1>

        <form action='selectNode'>
          <div id="startForm"> 
            <label id="urlLabel" for="url">URL:</label>
            <input id="url" type="text" name='url' value="http://" />
            <input id="submitURL" type='submit' value='Pick the Numbr on the Page' />
          </div>
        </form>

        <h1>Search All Numbrs</h1>

        <form action="search">
          <div id="searchForm">
            <input id="query" type="text" name='query' />
            <input id="submitQuery" type="submit" value='Search' />
          </div>
        </form>

        <h1>See a Numbr</h1>

        <form action="numbr">
          <div id="numbrForm">
            <input id="name" type="text" name='name' />
            <input id="submitNumbr" type="submit" value='Get Numbr' />
          </div>
        </form>

        <h1>Last 10 Numbrs</h1>

        <ul>
<?php
$stmt = $PDO->prepare("SELECT name, short(title, 50) as shorttitle, title, description, url, short(url, 30) as shorturl, is_fetching FROM numbrs ORDER BY createdTime DESC LIMIT 10");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($data as $row) {
?>
            <li>
              <a href="<?php print htmlspecialchars($row['name']) ?>" title="<?php print htmlspecialchars($row['description']) ?>">
                <?php print htmlspecialchars($row['name']) ?></a>
                : <a title="<?php print htmlspecialchars($row['title']) ?>"><?php print htmlspecialchars($row['shorttitle']) ?> </a>
              <a title="<?php print htmlspecialchars($row['url']) ?>">(<?php print htmlspecialchars($row['shorturl']) ?>)</a>
<?php if (!$row['is_fetching']) { ?>
              <span class="error">Not fetching due to errors.</span>
<?php } ?>
            </li>
<?php
}
?>
        </ul>

        <h1>Most Popular 10 <a href="allhosts">Hosts</a></h1>

        <ul>
<?php
$stmt = $PDO->prepare('SELECT COUNT(*) AS count, domain FROM numbrs GROUP BY domain ORDER BY count DESC LIMIT 10');
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($data as $row) {
    print "            <li><a href='search?query=" . htmlspecialchars($row['domain']) . "'>" . htmlspecialchars($row['domain']) . "</a> (" . htmlspecialchars($row['count']) . ")</li>\n";
}
?>
        </ul>
          
        <h1>News</h1>
        <ul><li>
        <span class="date">May 16, 2009</span> : With an idea from my friend <a href="http://yury.name">Yury</a>, <a href="http://paulisageek.com/webGraphr/">webGraphr</a> has now been split into two pieces. And so <a href="http://webnumbr.com">webNumbr</a> is born.
        </li></ul>

      </div>
    </div>

<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript">
// Load jQuery
google.load("jquery", "1");
google.setOnLoadCallback(function() {

var resizeURL = function() {
    $("#url").width($("#startForm").width() - $("#urlLabel").outerWidth(true) - $("#submitURL").outerWidth(true) - 20);
};
$("#startForm").ready(function() {
    $(window).resize(resizeURL);
    resizeURL();
});

var resizeSearch = function() {
    $("#query").width($("#searchForm").width() - $("#submitQuery").outerWidth() - 20);
};
$("#searchForm").ready(resizeSearch);
$(window).resize(resizeSearch);

var resizeNumbr = function() {
    $("#name").width($("#numbrForm").width() - $("#submitNumbr").outerWidth() - 20);
};
$("#numbrForm").ready(resizeNumbr);
$(window).resize(resizeNumbr);

});
</script>

<?php include("ga.inc") ?>

  </body>
</html>
