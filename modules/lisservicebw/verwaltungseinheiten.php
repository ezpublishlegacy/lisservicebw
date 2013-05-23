<?
require_once( "kernel/common/template.php" );
$tpl = eZTemplate::factory();

$c = new lisServicebwClient();

if($_REQUEST['id']){
            
    $result = $c->getVerwaltungseinheit($_REQUEST['id']);
   
    $result = $c->ConvertToArray($result->return);
  
}else{
    
    $result = $c->executeThemeQuery();
 
    $result = $c->ConvertToArray($result);
 
} 

$llid = $_REQUEST['llid'];
$type = $_REQUEST['type'];
$id_verfahren = $_REQUEST['id_verfahren'];
$tpl->setVariable('llid', $llid);
$tpl->setVariable('id_verfahren', $id_verfahren);
$tpl->setVariable('type', $type);
$tpl->setVariable('result', $result);
$tpl->setVariable('error', $c->error);

$Result = array();
$Result['content']   = $tpl->fetch( 'design:verwaltungseinheiten.tpl');
$Result['path'] = array( array( 'text' => 'lisservicebw/verwaltungseinheiten',
                                'url' => false ) );
                                
?>