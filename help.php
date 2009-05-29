<?php
// ================ templates parts ===================
$subtitle = "About webNumbr";
ob_start();
?>
<h3>About webNumber</h3>
webNumber is created by <a href="http://paulisageek.com">Paul Tarjan</a> and <a href="http://yury.name">Yury Lifshits</a>.
<br><br>

<h3>webNumbr API</h3>

<div>
<b>Basics</b> : All commands are seperated by <i>.</i> All parameters are wrapped by <i>()</i>. <a href="/numbrPlugins">Plugin sources</a>. 
</div><div>
Order of operations (all selectors, "SQL", all operations, all formats, "print")
</div>

<?php
function printDoc($dir) {
?>
<tr>
 <th>name</th>
 <th>params</th>
 <th>doc</th>
 <th>examples</th>
</tr>
<?php
    $p = scandir("numbrPlugins/$dir");
    sort($p);

    // Put default at the top
    $key = array_search("default", $p);
    unset($p[$key]);
    array_unshift($p, 'default');
    foreach ($p as $name) {
        if (substr($name, 0, 1) == ".") continue;
        $params = @file_get_contents("numbrPlugins/$dir/$name/params.txt");
        $doc = @file_get_contents("numbrPlugins/$dir/$name/doc.txt");
        $example = @file_get_contents("numbrPlugins/$dir/$name/example.txt");
        $examples = implode("<br/>", explode("\n", $example));
        if (!$doc) continue;
?>
<tr>
 <td><?php print $name ?></td>
 <td><?php print trim($params) ?></td>
 <td><?php print $doc ?></td>
 <td><?php print $examples ?></td>
</tr>
<?php
    }
}
?>

<table class="docs">
<caption>Selectors : These choose which piece of data you want.</caption>
<?php printDoc("selection"); ?>
</table>

<table class="docs">
<caption>Operations : These are evaluated in order and are chained together.</caption>
<?php printDoc("operation"); ?>
</table>

<table class="docs">
<caption>Formats : Output encoding. Can be chained.</caption>
<?php printDoc("format"); ?>
</table>

<?php
$content = ob_get_clean();

//========== template =========================

require ("template.php");
?>