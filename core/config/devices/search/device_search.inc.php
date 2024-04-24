<?php 
/* Jeedom "OpenMQTTGateway"
 *
 * Ce fichier contient la description des models d'objets supportés ativement pas le plugin.
 * L'objectif étant de simplement avoir à les sélectionner sans être un expert 
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


