<?php 
/* Jeedom "OpenMQTTGateway"
 *
 * Ce fichier contient la description des models d'objets support�s ativement pas le plugin.
 * L'objectif �tant de simplement avoir � les s�lectionner sans �tre un expert 
 * de comment fonctionnent les commandes.
 * 
*/

  static $v_device_search_json = '[';

  include dirname(__FILE__) . '/Xiaomi.inc.php';
  $v_device_search_json .= $v_device_json;

  $v_device_search_json .= ',';
  include dirname(__FILE__) . '/iTag.inc.php';
  $v_device_search_json .= $v_device_json;

  $v_device_search_json .= ',';
  include dirname(__FILE__) . '/Generic.inc.php';
  $v_device_search_json .= $v_device_json;

/*
  $v_device_search_json .= ',';
  include dirname(__FILE__) . '/brand_search_SAMPLE.inc.php';
  $v_device_search_json .= $v_device_json;
*/

  $v_device_search_json .= ']';

?>


