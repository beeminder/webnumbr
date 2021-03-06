<?php
function getEmbed($name) {
    $cache = @file_get_contents("/var/tmp/webnumbr/embed_code_$name");
    // $cache = apc_get("embed_code_$name");
    if ($cache) return $cache;

    $link = "/$name";
    $ch = curl_init("http://" . $_SERVER['HTTP_HOST'] . "$link.latest.embed");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $embed = (curl_exec($ch));
   
    file_put_contents("/var/tmp/webnumbr/embed_code_$name", $embed);
    // apc_store("embed_code_$name", $embed, 60*60*24); // 1 day
    return $embed;
}

// ================ templates parts ===================

$subtitle = "Track Numbers from Anywhere on the Web";
ob_start();
?>
                <div class="slogan">
                    <?php print $subtitle ?>
                </div>


                <div id="examples">
                        <table cellpadding="5" >
                            <tr>
                                <td>
                                    <a href="/webnumbrs">Number of Web Numbrs</a>
                                </td>
                                <td class="leftpadding">
                                    <?php print getEmbed("webnumbrs") ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/yhoo">Yahoo stock price</a>
                                </td>
                                <td class="leftpadding">
                                    <?php print getEmbed("yhoo") ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/temperature-sanjose">Temperature in San Jose</a>
                                </td>
                                <td class="leftpadding">
                                    <?php print getEmbed("temperature-sanjose") ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/paul-tarjan-facebook-subscribers">Paul's Facebook subscribers</a>
                                </td>
                                <td class="leftpadding">
                                    <?php print getEmbed("paul-tarjan-facebook-subscribers") ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/okcupid.last(100)">OkCupid Online Users</a>
                                </td>
                                <td class="leftpadding">
                                    <?php print getEmbed("okcupid") ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/wiki-en-pages">Pages in English Wikipedia</a>
                                </td>
                                <td class="leftpadding">
                                    <?php print getEmbed("wiki-en-pages") ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/xkcdusrs.last(1000)">xkcd forum users</a>
                                </td>
                                <td class="leftpadding">
                                    <?php print getEmbed("xkcdusrs") ?>
                                </td>
                            </tr>
                        </table>
                <iframe src="http://webnumbr.com/webnumbrs.last(1000).sample.graph" style="width: 100%; height: 200px;" allowtransparency="true" frameborder="0"></iframe>

                <br/>

                </div>
                <div id="onsite">Extract any number from <b>any webpage</b> and graph how it changes over time
                                  
                <br/><br/><a href="/search">Search</a> numbrs, see a <a href="/random">random</a> one, browse <a href="/all">all</a>
                </div>

                <div id="create">
                <a class="action-btn" href="/create">Create a Numbr</a>
                </div>

                <div id="embedcodes">Embed <b>near real-time values</b> of any number on your page. 
                Create the one you need, grab the <b>embed</b> code and place it on your website.
                You can embed <b>graphs</b> too.
                
                </div>
                            
<?php
/*
require("db.inc");
$stmt = $PDO->prepare("
SELECT name, title FROM numbrs WHERE is_fetching = TRUE ORDER BY createdTime DESC LIMIT 1
");
$ret = $stmt->execute();
if (!$ret) {
    $name = NULL;
}
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count ($data) == 1) {
    $name = $data[0]['name'];
    $title = $data[0]['title'];
} else {
    $name = NULL;
}

if ($name) {
    $link = "/$name";
    $embed = getEmbed($name);
    if (strlen($title) > 33)
        $title = substr($title, 0, 30) . "...";
?>
                <div id="newest">
                    Newest: 
                    <a href="<?php print $link ?>" title="<?php print $data[0]['title']; ?>"><?php print $title ?></a>
                    <?php print $embed . "\n" ?>
                </div>
<?php } ?>
<?php
require("db.inc");
$stmt = $PDO->prepare("
SELECT name, title FROM numbrs WHERE is_fetching = TRUE ORDER BY rand() LIMIT 1
");
$ret = $stmt->execute();
if (!$ret) {
    $name = NULL;
}
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count ($data) == 1) {
    $name = $data[0]['name'];
    $title = $data[0]['title'];
} else {
    $name = NULL;
}

if ($name) {
    $link = "/$name";
    $embed = getEmbed($name);
    if (strlen($title) > 30)
        $title = substr($title, 0, 27) . "...";
?>
                <div id="random">
                    Random: 
                    <a href="<?php print $link ?>" title="<?php print $data[0]['title']; ?>"><?php print $title ?></a>
                    <?php print $embed . "\n" ?>
                </div>
<?php } */ ?>

                <div class="clear">
                </div>
                        
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" ></script>
<script>
$(function() {
    $("input").focus(function() {
        $(this).select();
    });
});
</script>


<?php 
$content = ob_get_clean();
$footer = <<<END
<!--
<span id="comments"></span>
<script src="http://platform.twitter.com/anywhere.js?id=guJ54lAsrWlx72B8bjnCcA&v=1" type="text/javascript"></script>
<script type="text/javascript">
  twttr.anywhere("1", function (twitter) {
    //  Any of the default options can be modified by passing an
    //  object literal to the tweetBox method.

    twitter("#comments").tweetBox({
      counter: false,
      height: 100,
      width: 400,
      label: "What do you think about @webnumbr?",
      defaultContent: "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} is awesome!",
    });

  });
</script>
-->
                        <br/>
                        <a id="tos" href="tos">Terms of Service</a>
END;

//========== template =========================

include ("template.php");
?>
