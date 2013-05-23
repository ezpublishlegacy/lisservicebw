<?php
/*
 * @copyright Copyright (C) 2010-2013 land in sicht AG All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
*/

// Operator autoloading
$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] =
  array( 'script' => 'extension/lisservicebw/autoloads/servicebwoperators.php',
         'class' => 'ServicebwOperators',
         'operator_names' => array( 'getLLDetails','getVerfahrenByLebenslagekey', 'getVerfahren' , 'getVerfahrenByMandantenIdsAndChangeDateAndSprache', 'getLebenslageTree', 'getVBDetails') );
      

?>