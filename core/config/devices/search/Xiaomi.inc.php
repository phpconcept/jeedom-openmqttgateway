<?php 
/* Jeedom "OpenMQTTGateway"
 *
 * Ce fichier contient la description des models d'objets supportés ativement pas le plugin.
 * L'objectif étant de simplement avoir à les sélectionner sans être un expert 
 * de comment fonctionnent les commandes.
 * 
*/


/*
{"id":"A4:C1:38:C6:F3:6C",
"name":"ATC_C6F36C",
"rssi":-69,
"brand":"Xiaomi",
"model":"TH Sensor",
"model_id":"LYWSD03MMC/MJWSD05MMC_ATC",
"type":"THB",
"tempc":21.5,
"tempf":70.7,
"hum":65,
"batt":77,
"volt":2.901,
"mac":"A4:C1:38:C6:F3:6C"}
*/

  $v_device_json = <<<MYTEXT

    {
      "name" : "Xiaomi:LYWSD03MMC_ATC",
      "search_by_attribute_name_value" : [
        {"name":"brand", "value":"Xiaomi", "operator":"eq"},
        {"name":"model", "value":"TH Sensor", "operator":"eq"},
        {"name":"model_id", "value":"MMC_ATC", "operator":"inc"}
      ]               
    },

    {
      "name" : "Xiaomi:LYWSD03MMC_ATC",
      "search_by_attribute_name_value" : [
        {"name":"brand", "value":"Xiaomi", "operator":"eq"},
        {"name":"model", "value":"TH Sensor", "operator":"eq"},
        {"name":"model_id", "value":"MMC_ATC", "operator":"inc"},
        {"name":"model_id", "value":"LYWSD03MMC", "operator":"inc"},
        {"name":"type", "operator":"present"}
      ]               
    },

    {
      "name" : "Xiaomi:LYWSD03MMC_ATC",
      "search_by_attribute_name_value" : [
        {"name":"brand", "value":"Xiaomi", "operator":"eq"},
        {"name":"model", "value":"TH Sensor", "operator":"eq"},
        {"name":"model_id", "value":"MMC_ATC", "operator":"inc"},
        {"name":"model_id", "value":"LYWSD03MMC", "operator":"inc"},
        {"name":"type", "value":"THB", "operator":"eq"}
      ]               
    },

    {
      "name" : "Xiaomi:LYWSD03MMC",
      "search_by_attribute_name_value" : [
        {"name":"name", "value":"LYWSD03MMC", "operator":"eq"}
      ]               
    }

  MYTEXT;

?>


