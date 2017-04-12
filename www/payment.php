<?php
//
// Instantiate Class and variables
//
include_once('classes/classes.inc.php');
include_once('classes/tbs_class.php');
include_once('classes/tbs_plugin_html.php'); // Plug-in for selecting HTML items.
include_once('classes/tbs_plugin_bypage.php'); // Plug-in for selecting HTML items.
include_once('classes/tbs_plugin_navbar.php'); // Plug-in for selecting HTML items.

$data = array();

// database
//
if (!file_exists(DATABASE_FILE)) die("run InitScript");
$db   = new SQLite3(DATABASE_FILE);

// Filters
//

// Pagination
$pageNum  = (!empty($_GET["pageNum"])) ? $_GET["pageNum"] : 1;
$recCnt   = (!empty($_GET["recCnt"]))  ? intval($_GET["recCnt"]) : -1;
$pageSize = 16;

// Build condition
//
$where = '';
$other_prms = '';

// prepare select
//

// Build card list from existing PayID records
$rPayId = $db->query("SELECT * FROM payment $where ORDER by ts DESC");


//
// Prepare array for TBS template
//
while ($rslt = $rPayId->fetchArray()) {
  $wrk = json_decode($rslt['answer']);
  $rslt['answer'] = json_encode($wrk, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE);


  $data[] = $rslt;
} //endWhile


//
// Ask TBS to display data
//
$TBS = new clsTinyButStrong;
$TBS->LoadTemplate('tpl/tpl_payment.html');

// Merge ByPage block
$TBS->PlugIn(TBS_BYPAGE,$pageSize,$pageNum,$recCnt);
$recCnt = $TBS->MergeBlock('list',$data);

// Merge  NavBar
$TBS->PlugIn(TBS_NAVBAR,'nv','',$pageNum,$recCnt,$pageSize);

// TBS splash 
$TBS->Show();

// END
