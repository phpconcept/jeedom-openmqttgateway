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
      "name" : "Generic:iBeacon",
      "search_by_attribute_name_value" : [
        {"name":"brand", "value":"GENERIC", "operator":"eq"},
        {"name":"model", "value":"iBeacon", "operator":"eq"},
        {"name":"model_id", "value":"IBEACON", "operator":"eq"}
      ]               
    }

  MYTEXT;

?>


