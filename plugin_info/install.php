<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';


// Fonction exécutée automatiquement après l'installation ou activation du plugin 
function openmqttgateway_install() {

  log::add('openmqttgateway', 'info', "Start installation/activation of plugin 'openmqttgateway' version ".OMG_VERSION);

  // ----- Vérifie la configuration d'un topic de base
  $v_mqtt_topic = config::byKey('mqtt_base_topic', 'openmqttgateway', '');
  if ($v_mqtt_topic == '') {
    $v_mqtt_topic = 'openmqttgateway';
    config::save('mqtt_base_topic', $v_mqtt_topic, 'openmqttgateway');
  }
  
  if (config::byKey('scan_on', 'openmqttgateway', '') == '') {
    config::save('scan_on', 0, 'openmqttgateway');
  }
  
  if (config::byKey('scan_list', 'openmqttgateway', '') == '') {
    $v_list = array();
    config::save('scan_list', $v_list, 'openmqttgateway');
  }
  
  if (config::byKey('gateway_auto_discover', 'openmqttgateway', '') == '') {
    config::save('gateway_auto_discover', 1, 'openmqttgateway');
  }
  
  if (config::byKey('multi_gw_retention_time', 'openmqttgateway', '') == '') {
    config::save('multi_gw_retention_time', 60, 'openmqttgateway');
  }
  
  if (config::byKey('multi_gw_rssi_hysteresis', 'openmqttgateway', '') == '') {
    config::save('multi_gw_rssi_hysteresis', 5, 'openmqttgateway');
  }
  
  // ----- Fixe le topic dans le plugIn Mqtt2
  if (class_exists('mqtt2')) {
    mqtt2::addPluginTopic('openmqttgateway', $v_mqtt_topic);
  }
  
  // ----- Save current version
  config::save('version', OMG_VERSION, 'openmqttgateway');

  log::add('openmqttgateway', 'info', "Finished installation/activation of plugin 'openmqttgateway'");
}

// Fonction exécutée automatiquement après la mise à jour du plugin
function openmqttgateway_update() {
    
  $v_version = config::byKey('version', 'openmqttgateway', '');
  log::add('openmqttgateway', 'info', "Update plugin 'openmqttgateway' from version ".$v_version." to ".OMG_VERSION);

//  if ($v_version < '1.2') openmqttgateway_update_v_1_2($v_version);
    
  // ----- Save current version
  config::save('version', OMG_VERSION, 'openmqttgateway');

  log::add('openmqttgateway', 'info', "Finished update of plugin 'openmqttgateway' to ".OMG_VERSION);  
}

/*
function openmqttgateway_update_v_1_3($v_from_version='') {

  log::add('openmqttgateway', 'info', "Update devices to version 1.3 of plugin 'openmqttgateway'");
  
  // ----- Look for each equip
  $eqLogics = eqLogic::byType('openmqttgateway');
  
  foreach ($eqLogics as $v_eq) {
    $v_flag_save = false;
    
    if (!$v_eq->omgIsType(array('device','zone'))) {
      continue;
    }
    
    $v_type = $v_eq->omgGetType();
    
    // ----- Ajout des configurations de temperature cible par device
    if ($v_eq->getConfiguration($v_type.'_temperature_confort', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_confort', '');
      $v_flag_save = true;
    }
    if ($v_eq->getConfiguration($v_type.'_temperature_confort_1', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_confort_1', '');
      $v_flag_save = true;
    }
    if ($v_eq->getConfiguration($v_type.'_temperature_confort_2', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_confort_2', '');
      $v_flag_save = true;
    }
    if ($v_eq->getConfiguration($v_type.'_temperature_eco', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_eco', '');
      $v_flag_save = true;
    }
    if ($v_eq->getConfiguration($v_type.'_temperature_horsgel', '') == '') {
      $v_eq->setConfiguration($v_type.'_temperature_horsgel', '');
      $v_flag_save = true;
    }

    if ($v_flag_save) {
      $v_eq->save();
    }
    
  }
  
  
}
*/



// Fonction exécutée automatiquement après la suppression ou la désactivation du plugin
function openmqttgateway_remove() {

  if (class_exists('mqtt2')) {
    if(method_exists('mqtt2','removePluginTopicByPlugin')){
       mqtt2::removePluginTopicByPlugin('openmqttgateway');
       mqtt2::removePluginTopicByPlugin('');
    }
  }

  log::add('openmqttgateway', 'info', "Plugin 'openmqttgateway' removed");
}

?>


