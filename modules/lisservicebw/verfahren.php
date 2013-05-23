<?
require_once( "extension/lisservicebw/classes/lisservicebw.php" );
require_once( "kernel/common/template.php" );
$tpl = eZTemplate::factory();

$c = new lisServicebwClient();

if($_REQUEST['id']){
    
    if($_REQUEST['from'] == 'list'){
        
        $result = $c->getVBDetails($_REQUEST['id']);
        $verw = $c->getVerwaltungseinheitenByVerfahrenAndProfil($_REQUEST['id']);  
     
    }else{
        
        $result = $c->getVerfahren($_REQUEST['id']); //getVBDetails
        $tmp_id = $result->return->id;
        if(count($result->return)>0){      
            $result = $c->getVBDetails($result->return->id);
           
        }else{
            $c->error='Dieser Bereich befindet sich noch im Aufbau';
        }
        if(isset($tmp_id))
            $verw = $c->getVerwaltungseinheitenByVerfahrenAndProfil($tmp_id);
    }
     
 
    $result = $c->ConvertToArray($result->return);
  
}else{ //alle Verfahren

    $result = $c->getVerfahrenByMandantenIdsAndChangeDateAndSprache();
    
    $result = $c->ConvertToArray($result);

}



$verw = $c->ConvertToArray($verw);

$id_verfahren = $_REQUEST['id'];
$id_lebenslage = $_REQUEST['llid'];
$tpl->setVariable('from', $_REQUEST['from']);
$tpl->setVariable('type', $_REQUEST['type']);
$tpl->setVariable('id_verfahren', $id_verfahren);
$tpl->setVariable('tmpid', $tmp_id);
$tpl->setVariable('verfahren', $verf);
$tpl->setVariable('llid', $id_lebenslage);
$tpl->setVariable('result', $result);
$tpl->setVariable('error', $c->error);
$tpl->setVariable('verw', $verw['return']);
             
$Result = array();
$Result['content']   = $tpl->fetch( 'design:verfahren.tpl');
$Result['path'] = array( array( 'text' => 'lisservicebw/verfahren',
                                'url' => false ) );
                                

?>
