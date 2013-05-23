<?php
/*
 * @copyright Copyright (C) 2010-2013 land in sicht AG All rights reserved.
*  @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
*/
class ServicebwOperators
{
    /*!
     Constructor
    */
    function ServicebwOperators()
    {
        $this->Operators = array('getLLDetails', 'getVerfahrenByLebenslagekey', 'getVerfahren', 'getVerfahrenByMandantenIdsAndChangeDateAndSprache', 'getLebenslageTree', 'getVBDetails');
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list
    exists per operator type, this is needed for operator classes
    that have multiple operators.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     The first operator has two parameters, the other has none.
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 
                        'getLLDetails' => array('llid' => array( 'type' => 'string',
                                                                 'required' => true,
                                                                  'default' => '' )),
                        'getVerfahrenByLebenslagekey' => array( 'llid' => array('type'=> 'string',
                                                                'required' => true,
                                                                 'default' => '' ) ),
                        'getVerfahren' => array('id_verfahren' => array( 'type' => 'string',
                                                                 'required' => true,
                                                                  'default' => '' )),
                        'getVBDetails' => array('id_verfahren' => array( 'type' => 'string',
                                                                 'required' => true,
                                                                  'default' => '' )),
                                                                  
                        'getVerfahrenByMandantenIdsAndChangeDateAndSprache' => array(),
                        'getLebenslageTree' => array('llid' => array('type'=> 'string',
                                                                'required' => false,
                                                                 'default' => '' ) ),
                    );
        
                         
    }

    /*!
     Executes the needed operator(s).
     Checks operator names, and calls the appropriate functions.
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace,
                     &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'getLLDetails':
            {
                $operatorValue = $this->getLLDetails( $namedParameters['llid']);
            } break;
            case 'getVerfahrenByLebenslagekey':
            {
                $operatorValue = $this->getVerfahrenByLebenslagekey( $namedParameters['llid']);
            } break;
            case 'getVerfahren':
            {
                $operatorValue = $this->getVerfahren( $namedParameters['id_verfahren']);
            } break;
             case 'getVBDetails':
            {
                $operatorValue = $this->getVBDetails( $namedParameters['id_verfahren']);
            } break;
             case 'getVerfahrenByMandantenIdsAndChangeDateAndSprache':
            {
                $operatorValue = $this->getVerfahrenByMandantenIdsAndChangeDateAndSprache();
            } break;
             case 'getLebenslageTree':
            {
                $operatorValue = $this->getLebenslageTree( $namedParameters['llid']);
            } break;
        }
    }


    public function getLebenslageTree($id=''){
        
        $client= new lisServicebwClient(); 
        $client->init('lebenslage');
        
        $profilData = new stdClass();  //eigentlich new ProfilDataWs()
        $profilData->ags= $client->ags;
        $profilData->plz = $client->plz; 
        $profilData->sprache = $client->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new PrimaryKeyWs();
        $pkey->mandantenId = $client->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $client->lang;  
        
        $root = $client->execute("getRoot", $profilData ); //8
      
        if(isset($id)){
            $pkey->id = $id;
        }else{
             $pkey->id = $root->return; 
        }
        
        $param = new getLebenslageTree();
        $param->lebenslageKey = $pkey;
        if($client->man_id=='0'){
            $param->mandantenIds  = array($client->man_id);
        }else{
            $param->mandantenIds  = array('0',$client->man_id);
        }
       
        $param->operatorAnd   = false;
        $param->profilData = $profilData;
      
        $result = $client->execute("getLebenslageTree", $param );
        $result = $client->ConvertToArray($result);
        //($result = $client->linkConvert($result['return']);
        return $result;
        
    }


    function getLLDetails($llid)
    {
         
        $client= new lisServicebwClient(); 
        $client->init(lebenslage);
        
        $profilData = new stdClass();  //eigentlich new ProfilDataWs()
        $profilData->ags= $client->ags;
        $profilData->plz = $client->plz; 
        $profilData->sprache = $client->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new PrimaryKeyWs();
        $pkey->mandantenId = $client->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $client->lang;  
        
        if(isset($llid)){
            $pkey->id = $llid;
        }else{
             $pkey->id = $client->pkeyID;
        }
        
        $param = new getLLDetails();
        $param->lebenslageKey = $pkey;
        $param->mandantenIds  = array(0);
        $param->operatorAnd   = false;
        $param->profilData = $profilData;
        
        $result = $client->execute("getLLDetails", $param );
        
        $result = $client->ConvertToArray($result->return);
        $result = $client->linkConvert($result);
        
        return $result;
    }


    public function getVerfahrenByLebenslagekey($llid)
    {
        $client = new lisServicebwClient(); 
        $client->init(lebenslage);
        
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $client->ags;
        $profilData->plz = $client->plz; 
        $profilData->sprache = $client->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new stdClass();
        $pkey->mandantenId = $client->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $client->lang;  
        
        if(isset($llid)){
            $pkey->id = $llid;
        }else{
             $pkey->id = $client->pkeyID;
        }
        
        $param = new getVerfahrenByLebenslagekey();
        $param->lebenslageKey = $pkey;
        $param->mandantenIds = array(0);
        $param->operatorAnd  = false;
        $param->profilData   = $profilData;
        
        $result = $client->execute("getVerfahrenByLebenslagekey", $param );
        
        //var_dump($param);
        $result = $client->ConvertToArray($result->return);
        $result = $client->linkConvert($result);
        return $result;
       
    }





    public function getVerfahrenByMandantenIdsAndChangeDateAndSprache(){
            $client = new lisServicebwClient(); 
            $client->init('mandant');
            
            $res = $client->getMandantenIdsByPlzAndAgs();
          
          
            $client1 = new lisServicebwClient(); 
            $client1->init('verfahren');
            
           
            $verf = new getLebenslagenByMandantenIdsAndChangeDateAndSprache();
            $verf->sprachid =  $client1->lang; 
            $verf->mandantenIds  = array($client1->man_id);
          
            $result = $client1->execute("getVerfahrenByMandantenIdsAndChangeDateAndSprache", $verf );
            $result = $client1->ConvertToArray($result);
            
            return $result;
    }

      public function getVBDetails($id=''){
            
            $client = new lisServicebwClient(); 
            $client->init('verfahren');
            
            $profilData = new stdClass();  //new ProfilDataWs()
            $profilData->ags= $client->ags;
            $profilData->plz = $client->plz; 
            $profilData->sprache = $client->lang;
            $profilData->kategorienUndVerknuepfung = false;
            
            $pkey = new stdClass();
            $pkey->mandantenId = $client->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
            $pkey->sprachId = $client->lang;  
            
            if(isset($id)){
                $pkey->id = $id;
            }else{
                 $pkey->id = $client->pkeyID;
            }
            
            $param = new getVBDetails();
            $param->verfahrenKey = $pkey;
            $param->profilData   = $profilData;
          
            $result = $client->execute("getVBDetails", $param );
            $result = $client->ConvertToArray($result);
            
            return $result;
           
        }


 public function getVerfahren($id_verfahren)
    {
        
        $client = new lisServicebwClient(); 
        $client->init('dienstleistung');
        
        $profilData = new stdClass();  //new ProfilDataWs()
        $profilData->ags= $client->ags;
        $profilData->plz = $client->plz; 
        $profilData->sprache = $client->lang;
        $profilData->kategorienUndVerknuepfung = false;
        
        $pkey = new stdClass();
        $pkey->mandantenId = $client->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
        $pkey->sprachId = $client->lang;  
        
        if(isset($id_verfahren)){
            $pkey->id = $id_verfahren;
        }else{
             $pkey->id = $client->pkeyID;
        }
        
        $param = new getVerfahren();
        $param->dienstleistungKey = $pkey;
        $param->mandantenIds = array(0);
        $param->operatorAnd  = false;
        $param->profilData   = $profilData;
        
        $result = $client->execute("getVerfahren", $param );
        
        
        $tmp_id = $result->return->id;
        
        if(count($result->return)>0){
                
            $client = new lisServicebwClient(); 
            $client->init('verfahren');
            
            $profilData = new stdClass();  //new ProfilDataWs()
            $profilData->ags= $client->ags;
            $profilData->plz = $client->plz; 
            $profilData->sprache = $client->lang;
            $profilData->kategorienUndVerknuepfung = false;
            
            $pkey = new stdClass();
            $pkey->mandantenId = $client->man_id;    //Bei Lebenslagen Landesversion=0 abgeänderte Version=MandantenID
            $pkey->sprachId = $client->lang;  
            
            if(isset($tmp_id)){
                $pkey->id = $tmp_id;
            }else{
                 $pkey->id = $client->pkeyID;
            }
            
            $param = new stdClass();
            $param->verfahrenKey = $pkey;
            $param->mandantenIds = array(0);
            $param->operatorAnd  = false;
            $param->profilData   = $profilData;
            
            $result = $client->execute("getVBDetails", $param );
          
        }
        $result = $client->ConvertToArray($result->return);
        $result = $client->linkConvert($result);
        return $result;
       
    }


    /// \privatesection
    var $Operators;
}

?>