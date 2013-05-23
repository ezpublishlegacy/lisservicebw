<?php
$Module = array( 'name' => 'lisservicebw' );

$ViewList = array();
$ViewList['lebenslagen'] = array( 'script' => 'lebenslagen.php', 'params' => array( 'type', 'remote_id' ), 'functions' => array( 'lebenslagen' ) );
$ViewList['verwaltungseinheiten'] = array( 'script' => 'verwaltungseinheiten.php', 'params' => array( 'type', 'remote_id' ), 'functions' => array( 'verwaltungseinheiten' ) );
$ViewList['verfahren'] = array( 'script' => 'verfahren.php', 'params' => array( 'type', 'remote_id' ), 'functions' => array( 'verfahren' ) );
$ViewList['dienstleistungen'] = array( 'script' => 'dienstleistungen.php', 'params' => array( 'type', 'remote_id' ), 'functions' => array( 'dienstleistungen' ) );

$FunctionList = array(); 
$FunctionList['lebenslagen'] = array(); 
$FunctionList['verwaltungseinheiten'] = array();
$FunctionList['verfahren'] = array();
$FunctionList['dienstleistungen'] = array();
?>