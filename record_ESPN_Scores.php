<?php
/**
* NFL GAME GRADER USING ESPN API
* CREATED: AUG 26TH, 2021
*/

require __DIR__ . '/../config/config.php';
use Curl\Curl;

function uploadScore($gid, $winners, $vs, $newDate, $homeScore, $awayScore, $total, $totalFH, $FHWinners, $diffFH, $diff, $homeTM, $awayTM, $winLoc, $sport) {
    global $dbKey;
    global $win;
$record = array('id' => $gid[$win], 'gameid' => $gid[$win], 'bookieGID' => $gid[$win], 'winner' => $winners[$win], 'game' => $vs[$win], 'start' => $newDate[$win], 'homescore'=>$homeScore[$win], 'awayscore' => $awayScore[$win], 'total' => $total[$win], 'F5total'=> $totalFH[$win], 'F5winner'=> $FHwinners[$win], 'FHdiff'=> $diffFH[$win], 'FGdiff'=> $diff[$win], 'homeTM'=> $homeTM[$win], 'awayTM'=>$awayTM[$win], 'winLoc'=>$winLoc[$win], 'sport' => $sport);
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $dbKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/db/Create', [
    'record' => $record,
    'table' => 'scores'
    
]);
var_dump($curl->response);
var_dump($record);
$curl->close();
}



$getnfl = "https://site.api.espn.com/apis/site/v2/sports/football/nfl/scoreboard?seasontype=2&week=2";
$fetchnfl = file_get_contents($getnfl);
$nfl = json_decode($fetchnfl, true); 
$endNFL = count($nfl['events']);

for ($win = 0; $win < $endNFL; ++$win) {

$nflGames[$win] = array (
  'week'=>$nfl['week']['number'],
  'start'=>$nfl['events'][$win]['date'],
  'id'=>$nfl['events'][$win]['id'],
  'homeScore'=>$nfl['events'][$win]['competitions'][0]['competitors'][0]['score'],
  'awayScore'=>$nfl['events'][$win]['competitions'][0]['competitors'][1]['score'],
  'homeWinner'=>$nfl['events'][$win]['competitions'][0]['competitors'][0]['winner'],
  'awayWinner'=>$nfl['events'][$win]['competitions'][0]['competitors'][1]['winner'],
  'homeColor'=>$nfl['events'][$win]['competitions'][0]['competitors'][0]['team']['color'],
  'awayColor'=>$nfl['events'][$win]['competitions'][0]['competitors'][1]['team']['color'],
  'away'=>$nfl['events'][$win]['competitions'][0]['competitors'][1]['team']['displayName'],
  'home'=>$nfl['events'][$win]['competitions'][0]['competitors'][0]['team']['displayName']);

$searchPKs = $nflGames[$win]['id'];
$RLGetData = "https://site.api.espn.com/apis/site/v2/sports/football/nfl/scoreboard/$searchPKs"; 
$RLD = file_get_contents($RLGetData);
$RLData = json_decode($RLD, true);
$countPK = count($nflGames);


$nflGamesC[$win] = array (
  'week'=>$RLData['week']['number'],
  'start'=>$RLData['events'][$win]['date'],
  'id'=>$RLData['id'],
  'homeScore'=>$RLData['competitions'][0]['competitors'][0]['score'],
  'awayScore'=>$RLData['competitions'][0]['competitors'][1]['score'],
  'homeWinner'=>$RLData['competitions'][0]['competitors'][0]['winner'],
  'awayWinner'=>$RLData['competitions'][0]['competitors'][1]['winner'],
  'homeColor'=>$RLData['competitions'][0]['competitors'][0]['team']['color'],
  'awayColor'=>$RLData['competitions'][0]['competitors'][1]['team']['color'],
  'away'=>$RLData['competitions'][0]['competitors'][1]['team']['displayName'],
  'home'=>$RLData['competitions'][0]['competitors'][0]['team']['displayName']);
$winnerH = array_column($nflGamesC, 'home');
$winnerA = array_column($nflGamesC, 'away');
$gid = array_column($nflGamesC, 'id');
$scoreH = array_column($nflGamesC, 'homeScore');
$scoreA = array_column($nflGamesC, 'awayScore');
$total[$win] = $scoreH[$win] + $scoreA[$win];
  
  for ($win = 0; $win < $countPK; ++$win) {
if ($nflGamesC[$win]['homeWinner'] == '1') {
  $diff[] = $scoreH[$win] - $scoreA[$win];
  uploadScore($gid[$win], $winnerH[$win], $vs, $newDate, $scoreH[$win], $scoreA[$win], $total[$win], $diff[$win], $homeTM[$win], $awayTM[$win], $winLoc[$win], "NFL");
} elseif($nflGamesC[$win]['awayWinner'] == '1') {
    $diff[] = $scoreA[$win] - $scoreH[$win];
  uploadScore($gid[$win], $winnerA[$win], $vs, $newDate, $scoreH[$win], $scoreA[$win], $total[$win], $diff[$win], $homeTM[$win], $awayTM[$win], $winLoc[$win], "NFL") 
} else {
    $inp = file_get_contents('../logs/scoringErrors.json');
    $tempArray = json_decode($inp);
    array_push($tempArray, $data);
    $jsonData = json_encode($tempArray);
    file_put_contents('../logs/scoringErrors.json', $nflGamesC[$win]);
}
}
