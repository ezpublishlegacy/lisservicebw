<?
/*
 * @copyright Copyright (C) 2010-2013 land in sicht AG All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
*/
class lisServicebwClient 
{
   
    
    function lisServicebwClient () 
    {
        $ini = eZINI::instance('lisservicebw.ini'); 
        $this->ags = $ini->variable('LisServiceBWMandantConfig','ags');
        $this->plz = $ini->variable('LisServiceBWMandantConfig','plz');
        $this->man_id = $ini->variable('LisServiceBWMandantConfig','mandantenID');
        $this->wsType = $ini->variable('LisServiceBWMandantConfig','wstype');
        $this->pkeyID = $ini->variable('LisServiceBWMandantConfig','pkey_id');
        $this->authentifizierung = $ini->variable('LisServiceBWMandantConfig','authentifizierung');
        $this->wsUsername = $ini->variable('LisServiceBWMandantConfig','username');
        $this->wsPassword = $ini->variable('LisServiceBWMandantConfig','pswd');
        $this->lang = $ini->variable('LisServiceBWMandantConfig','lang');
        $this->server_url = $ini->variable('LisServiceBWMandantConfig','server_url');
        $this->wsClient = false;
        $this->error = '';
        
        $this->demo_url_with_auth = $ini->variable('LisServiceBWMandantConfig','demo_url_with_auth');
        $this->demo_url_without_auth = $ini->variable('LisServiceBWMandantConfig','demo_url_without_auth');
        $this->live_url_with_auth = $ini->variable('LisServiceBWMandantConfig','live_url_with_auth');
        $this->live_url_without_auth = $ini->variable('LisServiceBWMandantConfig','live_url_without_auth');
        
    }
    
    public function execute($function, $data)
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        eZDebug::writeError( "START: executing: $function", 'ServiceBW-Webservice' );
        try {
                $result = $this->wsClient->$function($data);
                     
                eZDebug::writeError( $data, 'ServiceBW-Webservice' );
                return $result;
            } 
            catch (SoapFault $soapFault) { 
            	        
                $this->error = 'Dieser Bereich befindet sich noch im Aufbau';
                
                eZDebug::writeError( "REQUEST:". $this->wsClient->__getLastRequest() , 'ServiceBW-Webservice' );
                eZDebug::writeError( $soapFault, 'ServiceBW-Webservice' );
                eZDebug::writeError( "END: error executing: $function", 'ServiceBW-Webservice' );
            }
            return false ;
    }
    
    
    public function init($port) //verfahren, lebenslage, dienstleistung, mandant, verwaltungseinheit
    {
        if($this->wsType=='demo'){
            if($this->authentifizierung=='true'){
                $url = $this->demo_url_with_auth.$port.'?wsdl'; //link Abnahmesystem mit Authentifizierung
            }else{
                $url = $this->demo_url_without_auth.$port.'?wsdl'; //link Abnahmesystem ohne Authentifizierung
            }
        }else{
            if($this->authentifizierung=='true'){
                $url = $this->live_url_with_auth.$port.'?wsdl';   //link Produktivsystem mit Authentifizierung
            }
            else{
                $url = $this->live_url_without_auth.$port.'?wsdl';   //link Produktivsystem ohne Authentifizierung
            }      
        }
        switch ($port)
        {
            case 'lebenslage':
                $class = "LebenslageWebService";
                break;
            case 'verfahren':
                $class= "VerfahrenWebService";       
                break;
            case 'dienstleistung':
                $class= "DienstleistungWebService";       
                break;
            case 'mandant':
                $class= "MandantenWebService";       
                break;
            case 'verwaltungseinheit':
                $class= "VerwaltungseinheitWebService";       
                break;
            case 'region':
                $class= "RegionWebService";       
                break;
        }
        
         $options = array( "uri" => $url,
                          "location" => $url,
                          'use' => SOAP_ENCODED,
                          'style' => SOAP_RPC,
                          "trace" => 1 );
        
        $this->wsClient = new $class($url, $options);
        
        if($this->authentifizierung =='true'){
            
              //Authentifizierung
              $sec = new Security();
              $auth = new AuthenticationHeader();

              $nsHeader = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
              $elementName = 'Security';
               
              $auth->Username = new SoapVar($this->wsUsername, XSD_STRING, null, null, null, $nsHeader);
              $auth->Password = new SoapVar($this->wsPassword, XSD_STRING, null, null, null, $nsHeader);
                 
              $sec->UsernameToken = new SoapVar($auth, SOAP_ENC_OBJECT, null, null, null, $nsHeader);
               
              $soapHeader = new SoapHeader($nsHeader, $elementName, $sec, true);
              $soapHeaders[] = $soapHeader;
              $this->wsClient->__setSoapHeaders($soapHeaders);

       
        }
        
        
    }
    
    public function linkConvert($result){

        if(count($result)){
                        
                        //vom lebenslagen object
                        $result["beschreibung"] = preg_replace('/href="[^"]*llpreview\.do\?llid=(\d+)[^"]*"/', 'href="lebenslagen?id=$1&type=detail"', $result["beschreibung"]);
                        $result["beschreibung"] = preg_replace("/\&(\w+)\=/s", "&amp;$1=", $result["beschreibung"]);
                
                        $result["untertitel"] = preg_replace('/href="[^"]*llpreview\.do\?llid=(\d+)[^"]*"/', 'href="lebenslagen?id=$1&type=detail"', $result["untertitel"]);
                        $result["untertitel"] = preg_replace("/\&(\w+)\=/s", "&amp;$1=", $result["untertitel"]);    
              
              
                        //vom Verfahren object
                        $result["informationen"] = preg_replace('/href="[^"]*llpreview\.do\?llid=(\d+)[^"]*"/', 'href="lebenslagen?id=$1&type=detail"', $result["informationen"]);
                        $result["informationen"] = preg_replace("/\&(\w+)\=/s", "&amp;$1=", $result["informationen"]); 
                        
                        $result["informationen"] = preg_replace('/href="[^"]*vbpreview\.do\?id=(\d+)[^"]*"/', 'href="verfahren?id=$1&type=detail"', $result["informationen"]);
                        $result["informationen"] = preg_replace("/\&(\w+)\=/s", "&amp;$1=", $result["informationen"]); 
                  
                        $result["ablauf"] = preg_replace('/href="[^"]*vbpreview\.do\?id=(\d+)[^"]*"/', 'href="verfahren?id=$1&type=detail"', $result["ablauf"]);
                        $result["ablauf"] = preg_replace("/\&(\w+)\=/s", "&amp;$1=", $result["ablauf"]); 
                        
                        $result["unterlagen"] = preg_replace('/href="[^"]*vbpreview\.do\?id=(\d+)[^"]*"/', 'href="verfahren?id=$1&type=detail"', $result["unterlagen"]);
                        $result["unterlagen"] = preg_replace("/\&(\w+)\=/s", "&amp;$1=", $result["unterlagen"]); 
                        
                        $result["zustaendigkeit"] = preg_replace('/href="[^"]*llpreview\.do\?llid=(\d+)[^"]*"/', 'href="lebenslagen?id=$1&type=detail"', $result["zustaendigkeit"]);
                        $result["zustaendigkeit"] = preg_replace("/\&(\w+)\=/s", "&amp;$1=", $result["zustaendigkeit"]); 
           
                        $result["voraussetzungen"] = preg_replace('/href="[^"]*vbpreview\.do\?id=(\d+)[^"]*"/', 'href="verfahren?id=$1&type=detail"', $result["voraussetzungen"]);
                        $result["voraussetzungen"] = preg_replace("/\&(\w+)\=/s", "&amp;$1=", $result["voraussetzungen"]); 
        }
        
        return $result;
    }   
    
    
      
    public function ConvertToArray($obj)
    {
        $arr = array();    
        if (is_object($obj))
        {
            foreach ($obj as $k => $o)
            {
                if (is_object($o))
                {
                    $arr[$k] = $this->ConvertToArray($o);
                }elseif (is_array($o))
                {
                    foreach($o as $k1 => $a)
                    {
                        $arr[$k][$k1] = $this->ConvertToArray($a);
                    }
                }
                 else  
                {
                   $arr[$k] = $o;     
                }
            }
        }
        return $arr;
        
    }
    
    public function GetVersion()
    {
        $this->init('lebenslage');
         
        $result = $this->execute("getVersion");
        
        return $result;
    }

    
    public function getLebenslageTree($id=''){
        
        $this->init('lebenslage');
        
        $profilData = new stdClass();  // new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new PrimaryKeyWs();
        $pkey->mandantenId = $this->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $this->lang;  
        
        $root = $this->execute("getRoot", $profilData ); //8
      
        if(isset($id)){
            $pkey->id = $id;
        }else{
             $pkey->id = $root->return; 
        }
        
        $param = new getLebenslageTree();
        $param->lebenslageKey = $pkey;
        if($this->man_id=='0'){
            $param->mandantenIds  = array($this->man_id);
        }else{
            $param->mandantenIds  = array('0',$this->man_id);
        }
       
        $param->operatorAnd   = false;
        $param->profilData = $profilData;
      
        $result = $this->execute("getLebenslageTree", $param );
        return $result;
        
    }
    

    public function getLLDetails($id=''){
        
        $this->init('lebenslage');
        
        $profilData = new stdClass();  // new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new PrimaryKeyWs();
        $pkey->mandantenId = $this->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $this->lang;  
        
        if(isset($id)){
            $pkey->id = $id;
        }else{
             $pkey->id = $this->pkeyID;
        }
        
        $param = new getLLDetails();
        $param->lebenslageKey = $pkey;
        $param->operatorAnd   = false; 
        $param->profilData = $profilData;
 
 
        //linkConfig  statt eigener Funktion linkConvert()
        $link = new LinkConfigWs();
        $ll_url = new LinkUrlWs();
        $ll_url->url = 'lebenslagen?id={0}&type=detail';
        $ll_url->mandantenId = $this->man_id;
        
        
        $vb_url = new LinkUrlWs();
        $vb_url->url = 'verfahren?id={0}&type=detail&from=list';
        $vb_url->mandantenId = $this->man_id;
        
        $link->lebenslageUrls[] = $ll_url;
        $link->applicationUrl= $this->server_url;
        $link->glossarUrl = '';
        $link->verfahrenUrls[]=$vb_url;
        
        $param->linkConfig = $link;
        //END
        
        $result = $this->execute("getLLDetails", $param );
        return $result;
        
    }
    
    
    public function getVBDetails($id=''){
        
        $this->init('verfahren');
        
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new stdClass();
        $pkey->mandantenId = $this->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $this->lang;  
        
        if(isset($id)){
            $pkey->id = $id;
        }else{
             $pkey->id = $this->pkeyID;
        }
        
        $param = new getVBDetails();
        $param->verfahrenKey = $pkey;
        $param->profilData   = $profilData;
        
        
        //linkConfig  statt eigener Funktion linkConvert()
        $link = new LinkConfigWs();
        $ll_url = new LinkUrlWs();
        $ll_url->url = 'lebenslagen?id={0}&type=detail';
        $ll_url->mandantenId = $this->man_id;
        
        
        $vb_url = new LinkUrlWs();
        $vb_url->url = 'verfahren?id={0}&type=detail&from=list';
        $vb_url->mandantenId = $this->man_id;
        
        $link->lebenslageUrls[] = $ll_url;
        $link->applicationUrl= $this->server_url;
        $link->glossarUrl = '';
        $link->verfahrenUrls[]=$vb_url;
        
        $param->linkConfig = $link;
        //END
        
        $result = $this->execute("getVBDetails", $param );
        return $result;
       
    }
 
     
     public function getVerfahren($id=''){
        
        $this->init('dienstleistung');
        
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new stdClass();
        $pkey->mandantenId = $this->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $this->lang;  
        
        if(isset($id)){
            $pkey->id = $id;
        }else{
             $pkey->id = $this->pkeyID;
        }
        
        $param = new getVerfahren();
        $param->dienstleistungKey = $pkey;
        
        if($this->man_id=='0'){
            $param->mandantenIds  = array($this->man_id);
        }else{
            $param->mandantenIds  = array('0',$this->man_id);
        }
        $param->profilData   = $profilData;
        
        $result = $this->execute("getVerfahren", $param );
        return $result;
       
    }
     
     public function getVerfahrenByLebenslagekey($id=''){
        
        $this->init('lebenslage');
        
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new stdClass();
        $pkey->mandantenId = $this->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $this->lang;  
        
        if(isset($id)){
            $pkey->id = $id;
        }else{
             $pkey->id = $this->pkeyID;
        }
        
        $param = new getVerfahrenByLebenslagekey();
        $param->lebenslageKey = $pkey;
        $param->profilData   = $profilData;
        
        $result = $this->execute("getVerfahrenByLebenslagekey", $param );
        return $result;
       
    }
     
     
    public function getAgsByPlz($plz=''){
        
         $this->init('region');
         
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $param = new getAgsByPlz();
        $param->plz = $plz;
        $param->profilData = $profilData;
        
        $result = $this->execute("getAgsByPlz", $param );
        return $result;
        
    }
    
    
    public function getAgsDataByAgsPlz($ags='', $plz=''){
        
        $this->init('region');
         
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $param = new getAgsDataByAgsPlz();
        $param->ags = $ags;
        $param->plz = $plz;
        $param->profilData = $profilData;
        
        $result = $this->execute("getAgsByPlz", $param );
        return $result;
        
    }
    
    public function getMandantenIdsByPlzAndAgs(){
        $this->init('mandant');
        
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $mand = new getMandantenIdsByPlzAndAgs();
        $mand->profilData = $profilData;
        $result = $this->execute("getMandantenIdsByPlzAndAgs", $mand );
        return $result;
        
    }


    public function getVerwaltungseinheitenByVerfahrenAndProfil($id_verfahren=''){
        
        $this->init('verwaltungseinheit');
        
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new PrimaryKeyWs();
        $pkey->mandantenId = $this->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $this->lang;  
        $pkey->id = $id_verfahren;
        
        $mand = new getVerwaltungseinheitenByVerfahrenAndProfil();
        $mand->profilData = $profilData;
        $mand->verfahrenKey = $pkey;
        $result = $this->execute("getVerwaltungseinheitenByVerfahrenAndProfil", $mand );
        return $result;
        
    }
    
    
     public function getVerwaltungseinheit($id=''){
        
        $this->init('verwaltungseinheit');
        
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $this->ags;
        $profilData->plz = $this->plz; 
        $profilData->sprache = $this->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new PrimaryKeyWs();
        
        $pkey->mandantenId = $this->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $this->lang;  
        $pkey->id = $id;
        
        $mand = new getVerwaltungseinheit();
        $mand->profilData = $profilData;
        $mand->verwaltungseinheitKey = $pkey;
        $result = $this->execute("getVerwaltungseinheit", $mand );
        return $result;
        
    }
     
     
    public function getVerfahrenByMandantenIdsAndChangeDateAndSprache(){
         
            $res = $this->getMandantenIdsByPlzAndAgs();
          
            $this->init('verfahren');
            $verf = new getLebenslagenByMandantenIdsAndChangeDateAndSprache();
            $verf->sprachid =  $this->lang; 
         
            if($this->man_id=='0'){
                $verf->mandantenIds  = array($this->man_id);
            }else{
                $verf->mandantenIds  = array('0', $this->man_id);
            }
            
            $result = $this->execute("getVerfahrenByMandantenIdsAndChangeDateAndSprache", $verf );
            return $result;
    }
     

    public function executeThemeQuery(){
         
          
            $this->init('verwaltungseinheit');
            
            $profilData = new stdClass();  //new ProfilDataWs()
            $profilData->ags= $this->ags;
            $profilData->plz = $this->plz; 
            $profilData->sprache = $this->lang;
            $profilData->kategorienUndVerknuepfung = false;
            
            $ex = new executeThemeQuery();
            $seaParam = new searchParameterObjectWs();
        
            $ex->searchParameter = $seaParam;
            $ex->profilData=$profilData;
            
            $result = $this->execute("executeThemeQuery", $ex );
            return $result;
    }

}
?>