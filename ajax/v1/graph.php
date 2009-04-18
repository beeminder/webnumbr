<?php
require ("../../db.inc");
header("Content-type: application/json");

// Don't let them be whitespace
foreach ($_REQUEST as $var => $_) 
    if (strlen(trim($_REQUEST[$var])) == 0) unset($_REQUEST[$var]);

function isGood($str) {
    return isset($_REQUEST[$str]) && is_numeric($_REQUEST[$str]) && ((int) $_REQUEST[$str]) >= 0;
}

if ($date = strtotime($_REQUEST['from'])) {
    $_REQUEST['from'] = $date ;
}
if ($date = strtotime($_REQUEST['to'])) {
    $_REQUEST['to'] = $date ;
}

if (!isGood('days') && (!isGood('from') || !isGood('to'))) {
    $_REQUEST['days'] = -1;    
}

if (!isGood('to')) 
    $_REQUEST['to'] = time(); 

if (isGood('days')) {
    $_REQUEST['from'] = $_REQUEST['to'] - $_REQUEST['days'] * 24 * 60 * 60;
} else {
    if (!isGood('from')) {
        if ($_REQUEST['days'] < 0) {
            $_REQUEST['from'] = 0;
        } else {
            $_REQUEST['from'] = time() - $_REQUEST['days'] * 24 * 60 * 60;
        }
    }
}

$_REQUEST['days'] = ((int) $_REQUEST['to'] - (int) $_REQUEST['from']) / 60 / 60 / 24;

if (!isset($_REQUEST['format'])) 
    $_REQUEST['format'] = "json";


function dieError($pdo) {
    die(json_encode(array("error" => array("msg" => "Prepare error", "errorInfo" => $pdo->errorInfo(), "errorCode" => $pdo->errorCode()))));
}

if (! is_array($_REQUEST['id'])) {
    if (strpos($_REQUEST['id'], ",") !== FALSE)
        $_REQUEST['id'] = explode(",", $_REQUEST['id']);
    else 
        $_REQUEST['id'] = array($_REQUEST['id']);
}

$_REQUEST['id'] = implode(",", $_REQUEST['id']);
$output = array(
    "request" => $_REQUEST,
);
$_REQUEST['id'] = explode(",", $_REQUEST['id']);

$graphs = array();

foreach ($_REQUEST['id'] as $id) {
    $stmt = $PDO->prepare("
SELECT
UNIX_TIMESTAMP(timestamp) AS unix_timestamp, 
data 

FROM graph_data WHERE 

graph_id = :id 
AND 
timestamp >= FROM_UNIXTIME(:from) 
AND 
timestamp <= FROM_UNIXTIME(:to) 

GROUP BY unix_timestamp 
ORDER BY unix_timestamp
    ");
    if (!$stmt) dieError($PDO);
    $ret = $stmt->execute(array("id" => $id, "from" => $_REQUEST['from'], "to" => $_REQUEST['to']));
    if (!$ret) dieError($stmt);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $data = array();
    foreach ($result as $row) {
        $data[] = array($row['unix_timestamp'], $row['data']);
    }

    $stmt = $PDO->prepare("SELECT id, name, url, xpath, frequency, goodFetches, badFetches, UNIX_TIMESTAMP(createdTime) AS createdTime, UNIX_TIMESTAMP(modifiedTime) AS 'modifiedTime' FROM graphs WHERE id = :id");
    if (!$stmt) dieError($PDO);
    $ret = $stmt->execute(array("id" => $id));
    if (!$ret) dieError($stmt);
    $graph = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $graph = $graph[0];

    $graphs[] = array(
        "meta" => $graph,
        "data" => $data,
    );
}

$output["graphs"] = $graphs;

if (isset($_REQUEST['callback'])) 
    print $_REQUEST['callback'] . "(" . json_encode($output) . ")";
else
    print json_encode($output);