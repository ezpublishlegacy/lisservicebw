<?
require_once( "extension/lisservicebw/classes/lisservicebw.php" );
require_once( "kernel/common/template.php" );
$tpl = eZTemplate::factory();
$c = new lisServicebwClient();

if(isset($_REQUEST['id'])){

    $result = $c->getLLDetails($_REQUEST['id']);

    $verfahren_by_lebenslage = $c->getVerfahrenByLebenslagekey($_REQUEST['id']);

    $result = $c->ConvertToArray($result->return);
  

} else{

    $result = $c->getLebenslageTree();
    $result = $c->ConvertToArray($result);
 
}


$verfahren_by_lebenslage = $c->ConvertToArray($verfahren_by_lebenslage->return);

$tpl->setVariable('type', $_REQUEST['type']);
$tpl->setVariable('verfahren_by_lebenslage', $verfahren_by_lebenslage);
$tpl->setVariable('result', $result);
$tpl->setVariable('error', $c->error);

$Result = array();
$Result['content']   = $tpl->fetch( 'design:lebenslagen.tpl');
$Result['path'] = array( array( 'text' => 'lisservicebw/lebenslagen',
                                'url' => false ) );

?>
