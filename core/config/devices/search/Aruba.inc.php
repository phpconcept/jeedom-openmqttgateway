<?php 
/* Jeedom "OpenMQTTGateway"
 *
 * Ce fichier contient la description des models d'objets supportés ativement pas le plugin.
 * L'objectif étant de simplement avoir à les sélectionner sans être un expert 
 * de comment fonctionnent les commandes.
 * 
*/


  $v_device_json = <<<MYTEXT

    {
      "name" : "Aruba:AssetTag",
      "search_by_attribute_name_value" : [
        {"name":"id", "value":"54:6C:0E", "operator":"inc"}
      ]               
    },

    {
      "name" : "Aruba:AssetTag",
      "search_by_attribute_name_value" : [
        {"name":"id", "value":"B0:91:22", "operator":"inc"}
      ]               
    }

  MYTEXT;

?>

