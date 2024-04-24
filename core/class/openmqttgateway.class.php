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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../../../plugins/openmqttgateway/core/php/openmqttgateway.inc.php';


class openmqttgateway extends eqLogic {

    /*     * *************************Attributs****************************** */
    /*
    * Attributs de configuration :
    */
    var $_pre_save_cache;

    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
     */
	public static function cron() {
      $v_list = openmqttgateway::omgGatewayList(['_isEnable'=>true]);
      foreach ($v_list as $v_item) {
        $v_item->omgGatewayCheckOnline();
      }
    
    }
     
     
    public static function cron5() {
        
      // ----- Recalculate mode for each device
      $v_list = openmqttgateway::omgDeviceList(['_isEnable'=>true]);
      foreach ($v_list as $v_device) {
        // TBC
      }

	}


    /*
     * Fonction exécutée automatiquement toutes les 5,10,15 minutes par Jeedom
      public static function cron10() {}
      public static function cron15() {
      }
     */

    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */

    /*
    public static function deamon_info()
    {
    }
     */

    /*
    public static function deamon_start($_debug = false)
    {
    }
     */

    /*
    public static function deamon_stop()
    {
    }
     */

    /*
	public static function dependancy_info() {
	}
     */

    /*
	public static function dependancy_install() {
	}
     */


    /*     * ***********************Methodes specifiques openmqttgateway*************************** */


    /**---------------------------------------------------------------------------
     * Method : omgEqList()
     * Description :
     *   openmqttgateway::omgEqList('device', ['zone'=>'', 'ddd'=>'vvv'])
     *   openmqttgateway::omgEqList('device', ['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgEqList($p_type, $p_filter_list=array()) {
      $v_result = array();
      $eqLogics = eqLogic::byType('openmqttgateway');
      foreach ($eqLogics as $v_eq) {
        if ($v_eq->omgGetConf('type') != $p_type) {
          continue;
        }
        
        // ----- Look for filtering
        if (is_array($p_filter_list)) {
          $v_filter_ok = true;
          foreach ($p_filter_list as $v_key => $v_value) {
            if ($v_key == '_isEnable') {
              if ($v_eq->getIsEnable() != $v_value) {
                $v_filter_ok = false;
                break;
              }
            }
            else if ($v_eq->omgGetConf($v_key) != $v_value) {
              $v_filter_ok = false;
              break;
            }
          }
          if ($v_filter_ok) {
            $v_result[] = $v_eq;          
          }
        }
        else {
          $v_result[] = $v_eq;
        }
      }
      return($v_result);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceList()
     * Description :
     *   openmqttgateway::omgDeviceList(['zone'=>'', 'ddd'=>'vvv'])
     *   openmqttgateway::omgDeviceList(['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgDeviceList($p_filter_list=array()) {
      return(openmqttgateway::omgEqList('device', $p_filter_list));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgEqGetByTopic()
     * Description :
     *   openmqttgateway::omgEqGetByTopic('ZZZZZ', 'device')
     *   openmqttgateway::omgEqGetByTopic('RRRRR', 'gateway')
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgEqGetByTopic($p_topic, $p_type) {
      $eqLogics = eqLogic::byType('openmqttgateway');
      foreach ($eqLogics as $v_eq) {
        if ($v_eq->omgGetConf('type') != $p_type) {
          continue;
        }
        
        if ($v_eq->omgGetConf($p_type.'_mqtt_topic') == $p_topic) {
          return($v_eq);
        }
      }
      return(null);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceGetByTopic()
     * Description :
     *   openmqttgateway::omgDeviceGetByTopic('ZZZZZ')
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgDeviceGetByTopic($p_topic) {
      return(openmqttgateway::omgEqGetByTopic($p_topic, 'device'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayList()
     * Description :
     *   openmqttgateway::omgGatewayList(['zone'=>'', 'ddd'=>'vvv'])
     *   openmqttgateway::omgGatewayList(['_isEnable'=>true]) : pour checker le getIsEnable de jeedom pour l'objet
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgGatewayList($p_filter_list=array()) {
      return(openmqttgateway::omgEqList('gateway', $p_filter_list));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayGetByTopic()
     * Description :
     *   openmqttgateway::omgGatewayGetByTopic('ZZZZZ')
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgGatewayGetByTopic($p_topic) {
      return(openmqttgateway::omgEqGetByTopic($p_topic, 'gateway'));
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayAutoDiscover()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgGatewayAutoDiscover() {
      $v_auto_discover = config::byKey('gateway_auto_discover', 'openmqttgateway', '');
      return ($v_auto_discover==1?true:false);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayCreate()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgGatewayCreate($p_mqtt_topic, $p_properties) {
      openmqttgateway::log('debug', "omgGatewayCreate('".$p_mqtt_topic."')");
      openmqttgatewaylog::log('debug', "  Properties : ".json_encode($p_properties));
      
      $v_jeedom_device = new openmqttgateway();
      
      $v_jeedom_device->setName($p_mqtt_topic);
      $v_jeedom_device->setEqType_name('openmqttgateway');

      $v_jeedom_device->setConfiguration('type', 'gateway');
      $v_jeedom_device->setConfiguration('gateway_mqtt_topic', $p_mqtt_topic);
      $v_jeedom_device->setConfiguration('prop_auto_discover', 1);

      $v_jeedom_device->setIsEnable(1);
      $v_jeedom_device->save();
      
      // ----- Create default online status command
      $v_cmd = $v_jeedom_device->omgCmdCreate('online_status', ['name'=>'online_status',
                                  'type'=>'info',
                                  'subtype'=>'binary', 
                                  'isHistorized'=>1, 
                                  'isVisible'=>1]);
      $v_jeedom_device->checkAndUpdateCmd('online_status', 0);

      return($v_jeedom_device);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionIsOn()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionIsOn() {
      if (config::byKey('scan_on', 'openmqttgateway', '') == 1) return(true);
      
      return(false);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionStart()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionStart() {
      openmqttgatewaylog::log('debug', 'omgInclusionStart()');
      
      config::save('scan_on', 1, 'openmqttgateway');
      
      // ----- Reset and empty list
      config::save('scan_list', array(), 'openmqttgateway');
      
      return(null);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionStop()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionStop() {
      openmqttgatewaylog::log('debug', 'omgInclusionStop()');

      config::save('scan_on', 0, 'openmqttgateway');

      // ----- Get current scan list
      $v_scan_list = config::byKey('scan_list', 'openmqttgateway', '');
      if (($v_scan_list == null) || (!is_array($v_scan_list))) {
         $v_scan_list = array();
      }

      return($v_scan_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionFinish()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionFinish() {
      openmqttgatewaylog::log('debug', 'omgInclusionFinish()');

      config::save('scan_on', 0, 'openmqttgateway');

      // ----- Get current scan list
      $v_scan_list = config::byKey('scan_list', 'openmqttgateway', '');
      if (($v_scan_list == null) || (!is_array($v_scan_list))) {
         $v_scan_list = array();
      }

      // ----- Reset and empty list
      config::save('scan_list', array(), 'openmqttgateway');

      return(null);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionGetList()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionGetList() {
      openmqttgatewaylog::log('debug', 'omgInclusionGetList()');

      // ----- Get current scan list
      $v_scan_list = config::byKey('scan_list', 'openmqttgateway', '');
      if (($v_scan_list == null) || (!is_array($v_scan_list))) {
         $v_scan_list = array();
      }

      return($v_scan_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgInclusionAddDevice()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgInclusionAddDevice($p_id) {
      openmqttgatewaylog::log('debug', 'omgInclusionAddDevice('.$p_id.')');

      // ----- Get current scan list
      $v_scan_list = config::byKey('scan_list', 'openmqttgateway', '');
      if (($v_scan_list == null) || (!is_array($v_scan_list))) {
         $v_scan_list = array();
      }
      
      $v_obj_att = null;
      
      foreach ($v_scan_list as $v_id => $v_att) {
        if ($v_id == $p_id) {
          $v_obj_att = $v_att;
          break;
        }
      }
      
      if ($v_obj_att !== null) {
        // ----- Add device
        openmqttgateway::omgDeviceCreate($p_id, $v_obj_att, null);
        
        // ----- Update scan list be removing added device
        unset($v_scan_list[$p_id]);
        config::save('scan_list', $v_scan_list, 'openmqttgateway');
        openmqttgatewaylog::log('debug', 'omgInclusionAddDevice() save ok');
      }
      else {
        openmqttgatewaylog::log('debug', 'omgInclusionAddDevice('.$p_id.') : no device in list');
      }

      openmqttgatewaylog::log('debug', 'omgInclusionAddDevice() done : '.json_encode($v_scan_list));
      return($v_scan_list);
    }
    /* -------------------------------------------------------------------------*/



    /**---------------------------------------------------------------------------
     * Method : log()
     * Description :
     *   A placeholder to encapsulate log message, and be able do some
     *   troubleshooting locally.
     * ---------------------------------------------------------------------------
     */
    public static function log($p_level, $p_message) {
      
      log::add('openmqttgateway', $p_level, $p_message);

    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceScanLearn()
     * Description :
     * Parameters :
     *   
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgDeviceScanLearn($p_mqtt_id, $p_properties, $p_gateway) {
    
      if (!openmqttgateway::omgInclusionIsOn()) {
        //openmqttgatewaylog::log('debug', "Scaning is off");
        return;
      }
      
      openmqttgatewaylog::log('debug', "Scaning / learn object ".$p_mqtt_id);

      // ----- Get current scan list
      $v_scan_list = config::byKey('scan_list', 'openmqttgateway', '');
      if (($v_scan_list == null) || (!is_array($v_scan_list))) {
         $v_scan_list = array();
      }
      
      //openmqttgatewaylog::log('debug', 'current list : '.json_encode($v_scan_list));
      
      // ----- Update scan list
      //if (!isset($v_scan_list[$p_mqtt_id])) {
      if (!array_key_exists($p_mqtt_id, $v_scan_list)) {
        openmqttgatewaylog::log('debug', "Scaning / adding object ".$p_mqtt_id);
        $v_scan_list[$p_mqtt_id] = $p_properties;
        
        //openmqttgatewaylog::log('debug', 'modif list : '.json_encode($v_scan_list));

        config::save('scan_list', $v_scan_list, 'openmqttgateway');
      }
      
      //openmqttgateway::omgDeviceCreate($p_mqtt_id, $p_properties, $p_gateway);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceCreate()
     * Description :
     * Parameters :
     *   
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgDeviceCreate($p_mqtt_id, $p_properties, $p_gateway=null) {
      openmqttgateway::log('debug', "Create a Jeedom Object for '".$p_mqtt_id."'");
      openmqttgatewaylog::log('debug', "  Properties : ".json_encode($p_properties));
      
      $v_jeedom_device = new openmqttgateway();
      
      $v_name = $p_mqtt_id;
      if (isset($p_properties['name'])) {
        $v_name = $p_properties['name'];
      }
      
      $v_jeedom_device->setName($v_name);
      $v_jeedom_device->setEqType_name('openmqttgateway');

      $v_jeedom_device->setConfiguration('type', 'device');
      $v_jeedom_device->setConfiguration('device_mqtt_topic', $p_mqtt_id);

/*
      if ($v_brand_model != null) {
        $v_jeedom_device->setConfiguration('device_brand_model', $v_brand_model['name']);
        $v_jeedom_device->setConfiguration('device_brand_score', $v_brand_score);
        $v_jeedom_device->setConfiguration('device_icon', $v_brand_model['icon']);
      }
      else {
        $v_jeedom_device->setConfiguration('device_brand_model', 'Generic:Generic');
        $v_jeedom_device->setConfiguration('device_brand_score', 0);
        $v_jeedom_device->setConfiguration('device_icon', '');
      }
*/
      //$v_jeedom_device->batteryStatus(50);
      
      $v_jeedom_device->setIsEnable(1);
      
      $v_jeedom_device->save();
      
      // ----- Change la nature du device
      // TBC: peut-on le faire avant ?
      [$v_brand_model, $v_brand_score] = openmqttgateway::omgDeviceBrandBestMatch($p_properties);
      $v_jeedom_device->omgDeviceBrandChange($v_brand_model, $v_brand_score);
      
      $v_jeedom_device->omgDeviceUpdateAttributes($p_properties, $p_gateway);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceRemoveAll()
     * Description :
     * Parameters :
     *   
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function omgDeviceRemoveAll() {
      openmqttgatewaylog::log('debug', 'Removing all the objects');
      $v_list = openmqttgateway::omgDeviceList();
      foreach ($v_list as $v_objet) {
        $v_objet->remove();
      }
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceBrandSearchList()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    static function omgDeviceBrandSearchList() {
        
      // ----- Inclure la liste des devices
      include dirname(__FILE__) . '/../../core/config/devices/search/device_search.inc.php';

      //openmqttgateway::log('debug', "json:".print_r($v_device_search_json ,true));
      openmqttgateway::log('debug', "json:".$v_device_search_json);

      $v_list = json_decode($v_device_search_json, true);
      if (json_last_error() != JSON_ERROR_NONE) {
       openmqttgateway::log('error', "Erreur dans le format json du fichier 'core/config/search/device_search.inc.php' (".json_last_error_msg().")");
       $v_list = array();
      }
      
      //openmqttgateway::log('debug', "json:".print_r($v_list ,true));
      //openmqttgateway::log('debug', "json:".$v_device_list_json);

      return($v_list);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceBrandList()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    static function omgDeviceBrandList($p_name_only=false) {
        
      // ----- Inclure la liste des devices
      $v_cheminDossier = dirname(__FILE__) . '/../../core/config/devices/models';
      
      $v_fichiers = array();

      // Vérifier si le dossier existe
      if (is_dir($v_cheminDossier)) {
          // Ouvrir le dossier
          if ($v_dh = opendir($v_cheminDossier)) {
              // Parcourir tous les fichiers du dossier
              while (($v_fichier = readdir($v_dh)) !== false) {
                  // Vérifier si le fichier se termine par ".json"
                  if (substr($v_fichier, -5) === '.json') {
                      // Ajouter le fichier à la liste
                      $v_name = str_replace('.json', '', $v_fichier);
                      $v_name = str_replace('__', ':', $v_name);
                      
                      if ($p_name_only) {
                        $v_fichiers[] = $v_name;
                      }
                      else {
                        if (($v_info = openmqttgateway::omgDeviceBrandInfo($v_name)) !== null) {
                          $v_fichiers[] = $v_info;
                        }                        
                      }
                  }
              }
              // Fermer le dossier
              closedir($v_dh);
          }
      }
      else {
        openmqttgateway::log('error', "Le dossier '".$v_cheminDossier."' n'est pas accessible.");
      }

      return($v_fichiers);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceBrandBestMatch()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    static function omgDeviceBrandInfo($p_brand_model_name) {

      $v_brand_model_name = str_replace(':', '__', $p_brand_model_name);
      $v_filename = dirname(__FILE__) . '/../config/devices/models/'.$v_brand_model_name.'.json';
      
      if (($v_content = @file_get_contents($v_filename)) === false) {
        return(null);
      }

      //openmqttgateway::log('debug', "Device brand '".$p_brand_model_name."' content : ".$v_content);
      
      $v_brand_model = json_decode($v_content, true);
      if (json_last_error() != JSON_ERROR_NONE) {
       openmqttgateway::log('error', "Erreur dans le format json du fichier '".$v_filename."' (".json_last_error_msg().")");
       return(null);
      }
      
      openmqttgateway::log('debug', "Device brand '".$p_brand_model_name."' info : ".json_encode($v_brand_model));
      
      return($v_brand_model);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceBrandBestMatch()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    static function omgDeviceBrandBestMatch($p_attributes) {
    
      $v_list = openmqttgateway::omgDeviceBrandSearchList();
      
      $v_best_match_name = '';
      $v_best_match_count = 0;
      
      foreach ($v_list as $v_brand_device) {
        openmqttgateway::log('debug', "look for brand device '".$v_brand_device['name']."'");
        
        // ----- Recherche par attribut et valeur
        if (isset($v_brand_device['search_by_attribute_name_value'])) {

          $v_match_count = 0;
          foreach ($v_brand_device['search_by_attribute_name_value'] as $v_item) {
         
            // S'il n'y a pas d'attribut avec ce nom alors ne match pas on sort de la boucle. 
            if (!isset($p_attributes[$v_item['name']])) break;
            
            // On extrait l'operateur
            if (!isset($v_item['operator']) || ($v_item['operator'] == 'eq')) {
              $v_operator = 'eq';
            }
            else {
              $v_operator = $v_item['operator'];
            }
            
            // On compare en fonction de l'opérateur
            if (($v_operator == 'eq') && ($p_attributes[$v_item['name']] == $v_item['value'])) {
              openmqttgateway::log('debug', "On match (eq) sur l'attribut ".$v_item['name']." ");
              $v_match_count++;
            }
            // On compare en inclusion
            else if (($v_operator == 'inc') && (strpos($p_attributes[$v_item['name']], $v_item['value']) !== False)) {
              openmqttgateway::log('debug', "On match (inc) sur l'attribut ".$v_item['name']." ");
              $v_match_count++;
            }
            // All other 
            else {
            }
          }
          
          if ($v_match_count == sizeof($v_brand_device['search_by_attribute_name_value'])) {
            openmqttgateway::log('debug', "Full match pour '".$v_brand_device['name']."', match count = ".$v_match_count);
            
            if ($v_match_count > $v_best_match_count) {
              openmqttgateway::log('debug', "New best match '".$v_brand_device['name']."', ".$v_match_count." versus ".$v_best_match_count."");
              $v_best_match_name = $v_brand_device['name'];
              $v_best_match_count = $v_match_count;
            }
          }
          else {
            openmqttgateway::log('debug', "'".$v_brand_device['name']."' ne match pas.");
          }
          
        }
        
      }
      
      $v_brand_model = openmqttgateway::omgDeviceBrandInfo($v_best_match_name);
      
      return([$v_brand_model, $v_best_match_count]);
    }
    /* -------------------------------------------------------------------------*/


    /*     * *********************Méthodes d'instance************************* */
    
    /*
      preInsert ⇒ Méthode appellée avant la création de votre objet
      postInsert ⇒ Méthode appellée après la création de votre objet
      preUpdate ⇒ Méthode appellée avant la mise à jour de votre objet
      postUpdate ⇒ Méthode appellée après la mise à jour de votre objet
      preSave ⇒ Méthode appellée avant la sauvegarde (creation et mise à jour donc) de votre objet
      postSave ⇒ Méthode appellée après la sauvegarde de votre objet
      preRemove ⇒ Méthode appellée avant la supression de votre objet
      postRemove ⇒ Méthode appellée après la supression de votre objet    
      
      Lorsque l'on créé un equipement, il demande son nom, puis il fait les fonctions suivantes :
        preSave()
        preInsert()
        postInsert()
        postSave()
      il propose ensuite l'écran des paramètres de configuration :
    */

    public function preInsert() {
      openmqttgatewaylog::log('debug', "preInsert()");
    }

    public function postInsert() {
      openmqttgatewaylog::log('debug', "postInsert()");

      // ----- Vérifier qu'il y a bien un type d'identifié, sinon forcer device
      $v_type = $this->getConfiguration('type', '');
      if (($v_type != 'gateway') && ($v_type != 'device')) {
        openmqttgateway::log('error', "Equipement avec un type non reconnu : '".$v_type."', type 'device' forcé.");
        $this->setConfiguration('type', 'device');
      }
      
      if ($v_type == 'device') {
      
        $v_cmd_order=1;
        // ----- Création des commandes par défaut
        //$this->omgCmdCreate('last_seen', ['name'=>'Dernière Communication', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
      }

      else if ($v_type == 'gateway') {

        $v_cmd_order=1;
        // ----- Création des commandes par défaut
        //$this->omgCmdCreate('last_seen', ['name'=>'Dernière Communication', 'type'=>'info', 'subtype'=>'string', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>$v_cmd_order++]);
      }

      else {
        // TBC : error
      }

    }

    public function preSave() {
      //openmqttgatewaylog::log('debug', "preSave()");
      $v_type = $this->getConfiguration('type', '');
      if ($v_type == 'device') {
        $this->preSaveDevice();
      }
      else if ($v_type == 'gateway') {
        $this->preSaveGateway();
      }
    }

    public function preSaveDevice() {
      openmqttgatewaylog::log('debug', "preSaveDevice()");
      
      // It's time to gather informations that will be used in postSave
      
      // ----- Look for new device
      // The trick is that before the first save the eq is not in the DB so it has not yet a deviceId
      // In my plugin I need to remember I first save the device in javscript with the sub-type 'device', 'gateway' or 'zone'
      if ($this->getId() == '') {
        openmqttgatewaylog::log('debug', "preSaveDevice() : new device, init properties");
        
        // ----- Set default values
        $this->setConfiguration('cmd_auto_discover', 1);
        $this->setConfiguration('best_gateway', '');
        $this->setConfiguration('best_gateway_rssi', -199);
        $this->setConfiguration('best_gateway_ts', time()); // ts : timestamp
        
        $this->setConfiguration('brand_auto_discover', 1);
        $this->setConfiguration('device_brand_model', 'Generic:Generic');
        $this->setConfiguration('device_brand_score', 0);
        //$this->setConfiguration('device_brand', 'Generic');
        //$this->setConfiguration('device_model', 'Generic');
        
        // ----- No data to store for postSave() tasks
        $this->_pre_save_cache = null; // New eqpt => Nothing to collect        
      }
      
      // ----- Look for existing device
      else {
        openmqttgatewaylog::log('debug', "preSaveDevice() : existing device.");

        // ----- Load device (eqLogic) from DB
        // These values will be erased with the save in DB, so keep what is needed to be kept
        // $this : contient donc l'objet PHP avec les nouvelles valeurs, avant leur sauvegarde dans la DB
        // $eqLogic : contient les valeurs dans la DB qui vont être remplacées par la sauvegarde de $this dans la DB
      	$eqLogic = self::byId($this->getId());
        
        $this->_pre_save_cache = array(
          'name'                  => $eqLogic->getName(),
          'device_brand_model'    => $eqLogic->omgGetConf('device_brand_model'),
          'isEnable'              => $eqLogic->getIsEnable()
        );
        
        // ----- Look if something change
        // Doing change before the save
        /*
        $v_nature_fil_pilote = $this->omgGetConf('nature_fil_pilote');
        if (   ($eqLogic->omgGetConf('nature_fil_pilote') != $v_nature_fil_pilote)
            || (   ($v_nature_fil_pilote == 'fp_device') 
                && ($eqLogic->omgGetConf('fp_device_id') != $this->omgGetConf('fp_device_id')))
            || ($eqLogic->omgGetConf('lien_commutateur') != $this->omgGetConf('lien_commutateur'))
            || ($eqLogic->omgGetConf('lien_commutateur_a') != $this->omgGetConf('lien_commutateur_a'))
            || ($eqLogic->omgGetConf('lien_commutateur_b') != $this->omgGetConf('lien_commutateur_b')) ) {
          $this->cpNatureChangeTo($v_nature_fil_pilote);
        }
        */
  
        
      }
      openmqttgatewaylog::log('debug', "preSaveDevice() done");
    }

    public function preSaveGateway() {
      //openmqttgatewaylog::log('debug', "preSave() : gateway ...");
      
      // It's time to gather informations that will be used in postSave
      
      // ----- Look for new device
      // The trick is that before the first save the eq is not in the DB so it has not yet a deviceId
      // In my plugin I need to remember I first save the device in javscript with the sub-type 'device', 'gateway' or 'zone'
      if ($this->getId() == '') {
        openmqttgatewaylog::log('debug', "preSaveGateway() : new gateway, init properties");
        
        // ----- Set default values
        $this->setConfiguration('prop_auto_discover', 1);
        $this->setConfiguration('online_timeout', 2);

        // ----- No data to store for postSave() tasks
        $this->_pre_save_cache = null; // New eqpt => Nothing to collect        
      }
      
      // ----- Look for existing device
      else {
        openmqttgatewaylog::log('debug', "preSaveGateway() : existing gateway.");
        // ----- Load device (eqLogic) from DB
        // These values will be erased with the save in DB, so keep what is needed to be kept
      	$eqLogic = self::byId($this->getId());

        $this->_pre_save_cache = array(
          'name'                  => $eqLogic->getName(),
          'isEnable'              => $eqLogic->getIsEnable()
        );
      }

      openmqttgatewaylog::log('debug', "preSave() end");
    }

    public function postSave() {
      //openmqttgatewaylog::log('debug', "postSave()");
      $v_type = $this->getConfiguration('type', '');
      if ($v_type == 'device') {
        $this->postSaveDevice();
      }
      else if ($v_type == 'gateway') {
        $this->postSaveGateway();
      }
    }

    public function postSaveDevice() {

      //openmqttgatewaylog::log('debug', "postSave() device");

      // ----- Look for new device
      if (is_null($this->_pre_save_cache)) {
        openmqttgatewaylog::log('debug', "postSaveDevice() : new device saved in DB.");
        
        // ----- Create default online status command
        $v_cmd = $this->omgCmdCreate('online_status', ['name'=>'online_status',
                                    'type'=>'info',
                                    'subtype'=>'binary', 
                                    'isHistorized'=>0, 
                                    'isVisible'=>0]);
        $this->checkAndUpdateCmd('online_status', 0);

        // ----- Create default online status command
        $v_cmd = $this->omgCmdCreate('rssi', ['name'=>'rssi',
                                    'type'=>'info',
                                    'subtype'=>'numeric', 
                                    'isHistorized'=>0, 
                                    'isVisible'=>0]);
        $this->checkAndUpdateCmd('rssi', -199);

        
      }
      
      // ----- Look for existing device
      else {
        openmqttgatewaylog::log('debug', "postSaveDevice() : device saved in DB.");

        // ----- Regarde si le device a changé de nature
        if ($this->_pre_save_cache['device_brand_model'] != $this->omgGetConf('device_brand_model')) {
        
          $v_brand_model = openmqttgateway::omgDeviceBrandInfo($this->omgGetConf('device_brand_model'));
          if ($v_brand_model == null) {
            $v_brand_model = openmqttgateway::omgDeviceBrandInfo('Generic:Generic');
          }
          $this->omgDeviceBrandChange($v_brand_model, 10);
          
        }
          
        // ----- Look if device enable is changed
        if ($this->_pre_save_cache['isEnable'] != $this->getIsEnable()) {
        
          // Code à dérouler si changement enable
        }
          
      }
      
      openmqttgatewaylog::log('debug', "postSaveDevice() done");
    }

    public function postSaveGateway() {

      //openmqttgatewaylog::log('debug', "postSave() : gateway");

      // ----- Look for new device
      if (is_null($this->_pre_save_cache)) {
        openmqttgatewaylog::log('debug', "postSaveGateway() : new gateway saved in DB.");

        
      }
      
      // ----- Look for existing device
      else {
        openmqttgatewaylog::log('debug', "postSaveGateway() : gateway saved in DB.");

        // ----- Look if device enable is changed
        if ($this->_pre_save_cache['isEnable'] != $this->getIsEnable()) {
        
        }
  
      }
      
      openmqttgatewaylog::log('debug', "postSaveGateway() : end");
    }

/*
    public function start() {
      openmqttgatewaylog::log('info', "Start plugin OpenMQTTGateway");
            
      // ----- Look for clean start
      if (config::byKey('clean_stop', 'openmqttgateway') != '') {
        openmqttgatewaylog::log('debug', "PlugIn was clean stopped at : ".config::byKey('clean_stop', 'openmqttgateway'));

        // ----- Reset clean stop flag
        config::save('clean_stop', '', 'openmqttgateway');

        return;
      }

      // ----- Not clean
      openmqttgatewaylog::log('debug', "PlugIn was not clean stopped");
      
      
    }

    public function stop() {
      openmqttgatewaylog::log('info', "Stop plugin OpenMQTTGateway");

      // ----- Set clean stop flag
      config::save('clean_stop', date("d-m-Y H:i"), 'openmqttgateway');
    }
    */

    public function preUpdate() {
    }

    public function postUpdate() {
    }

    public function preRemove() {
    }

    public function postRemove() {
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
     */
     /*
    public function toHtml($_version = 'dashboard') {
    
      // ----- Look for use of standard widget or not
      if (config::byKey('standard_widget', 'openmqttgateway') == 1) {
        return parent::toHtml($_version);
      }
      
      if ($this->omgIsType('device')) {
      //$_version = 'mobile'; // dev trick
        if ($_version == 'dashboard') {
          return $this->toHtml_device($_version);
        }
        else if ($_version == 'mobile') {
//          return $this->toHtml_mobile_device($_version);
          return $this->toHtml_device($_version);
        }
        else {
          return parent::toHtml($_version);
        }
      }
      else {
        return $this->toHtml_gateway($_version);
      }


      
    }
*/

    /**---------------------------------------------------------------------------
     * Method : toHtml_gateway()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
/*     
    public function toHtml_gateway($_version = 'dashboard') {
      //openmqttgateway::log('debug',  "Call toHtml_gateway()");

      $replace = $this->preToHtml($_version);

      if (!is_array($replace)) {
        return $replace;
      }      
      $version = jeedom::versionAlias($_version);
  

      //$replace['#name_display#'] = 'La Gateway Pilote';
     


      // postToHtml() : fait en fait le remplacement dans template + le cache du widget
      return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'openmqttgateway-gateway.template', __CLASS__)));  
    }
*/    
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : toHtml_device()
     * Description :
     *   
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
/*     
    public function toHtml_device($_version = 'dashboard') {
      //openmqttgateway::log('debug',  "Call toHtml_device()");

      $replace = $this->preToHtml($_version);

      if (!is_array($replace)) {
        return $replace;
      }      
      $version = jeedom::versionAlias($_version);
      
      //$replace['#cmd_confort_style#'] = '';

      // postToHtml() : fait en fait le remplacement dans template + le cache du widget
      return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'openmqttgateway-device.template', __CLASS__)));  
    }
*/    
    /* -------------------------------------------------------------------------*/


    /*
     * Non obligatoire permet d'associer une icone custom pour l'objet
     */
	public function getImage() {
      if ($this->omgIsType('gateway')) {
        return 'plugins/openmqttgateway/plugin_info/openmqttgateway_icon.png';
      }
      
      $v_type = $this->omgGetType();
      $v_icon = $this->omgGetConf('device_icon');
      $file = 'plugins/openmqttgateway/core/config/devices/'.$v_icon;
      if (($v_icon == '') || (!file_exists(__DIR__.'/../../../../'.$file))) {
        return 'plugins/openmqttgateway/plugin_info/openmqttgateway_icon.png';
      }
      return $file;
	}

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>($_value) {
      return($_value);
    }
    public static function postConfig_<Variable>($_value) {
    }
     */

    public static function preConfig_mqtt_base_topic($_value = null) {
      openmqttgatewaylog::log('debug', 'Pre Changement topic : '.config::byKey('mqtt_base_topic', __CLASS__).', value = '.$_value);
      
      if (config::byKey('mqtt_base_topic', __CLASS__) == $_value) {
        openmqttgatewaylog::log('debug', 'Pas de mise à jour dans plugIn MQTT2 nécessaire');
        return($_value);
      }
      
      openmqttgatewaylog::log('debug', 'Change topic "'.$_value.'" dans plugIn MQTT2 ');

      if (!class_exists('mqtt2')) {
        throw new Exception(__("Plugin Mqtt Manager (mqtt2) non installé, veuillez l'installer avant de pouvoir continuer", __FILE__));
      }
      if(method_exists('mqtt2','removePluginTopicByPlugin')){
         mqtt2::removePluginTopicByPlugin(__CLASS__);
      }
      mqtt2::addPluginTopic(__CLASS__, $_value);
      
      return($_value);
    }
    
    /*
    public static function postConfig_mqtt_base_topic($_value = null) {
      openmqttgatewaylog::log('debug', 'Changement topic : '.config::byKey('mqtt_base_topic', __CLASS__).', value = '.$_value);

      if (!class_exists('mqtt2')) {
        throw new Exception(__("Plugin Mqtt Manager (mqtt2) non installé, veuillez l'installer avant de pouvoir continuer", __FILE__));
      }
      if(method_exists('mqtt2','removePluginTopicByPlugin')){
         mqtt2::removePluginTopicByPlugin(__CLASS__);
      }
      mqtt2::addPluginTopic(__CLASS__, config::byKey('mqtt_base_topic', __CLASS__));
      
    }
    */

    



    /**---------------------------------------------------------------------------
     * Method : handleMqttMessage()
     * Description :
     * Parameters :
     *   
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public static function handleMqttMessage($p_datas) {
      openmqttgatewaylog::log('debug', json_encode($p_datas));
        
      $v_topic = config::byKey('mqtt_base_topic', __CLASS__);
      if (!isset($p_datas[$v_topic])) {
        openmqttgatewaylog::log('debug', 'Il manque le topic "'.$v_topic.'" dans les datas !');
        return;
      }
      
      foreach ($p_datas[$v_topic] as $v_key => $v_values) {
        openmqttgatewaylog::log('debug', 'Received data for gateway "'.$v_key.'"');
        
        $v_gateway = openmqttgateway::omgGatewayGetByTopic($v_key);
        if ($v_gateway === null) {
          openmqttgatewaylog::log('debug', 'No gateway with this topic "'.$v_key.'"');
          
          if (openmqttgateway::omgGatewayAutoDiscover()) {
            $v_gateway = openmqttgateway::omgGatewayCreate($v_key, $v_values);
          }
          
          // TBC : Create new gateway en attendant next
          if ($v_gateway === null) continue;
        }
        
        $v_gateway->omgGatewayFlagRcvMqttMsg();
        
        if (isset($v_values['BTtoMQTT'])) {
          foreach ($v_values['BTtoMQTT'] as $v_id => $v_properties) {
            if (is_array($v_properties)) {
              openmqttgatewaylog::log('debug', 'Object Id "'.$v_id.'"');
              
              if (($v_object = openmqttgateway::omgDeviceGetByTopic($v_id)) !== null) {
                //openmqttgatewaylog::log('debug', 'Is in the list -----------');
                $v_object->omgDeviceUpdateAttributes($v_properties, $v_gateway);
              }
              else {
                //openmqttgatewaylog::log('debug', 'Not in the list !');
                //openmqttgateway::omgDeviceCreate($v_id, $v_properties, $v_gateway);
                
                //if (openmqttgateway::omgInclusionIsOn()) {
                  openmqttgateway::omgDeviceScanLearn($v_id, $v_properties, $v_gateway);
                //}
              }
              
            }
            else {
              openmqttgatewaylog::log('debug', 'BLE Attribut "'.$v_id.'" = "'.$v_properties.'"');
              $v_gateway->omgGatewayUpdateBleAttribut($v_id, $v_properties);
            }
          }
        }
        
        if (isset($v_values['SYStoMQTT'])) {
          if ($v_gateway->omgGetConf('prop_auto_discover')) {
            $v_gateway->omgGatewayUpdateSysAttribut($v_values['SYStoMQTT']);
          }
        }
        
        if (isset($v_values['LWT'])) {
          if ($v_values['LWT'] == 'offline') {
            $v_gateway->omgGatewayChangeToOffline();
          }
          elseif  ($v_values['LWT'] == 'online') {
            // Déjà fait par l'update à la reception du message
          }
        }
      }

    }
    /* -------------------------------------------------------------------------*/




    /*     * **********************Getteur Setteur*************************** */


    /**---------------------------------------------------------------------------
     * Method : omgGetConf()
     * Description :
     *   Récupère la valeur stockée pour un attribut de configuration.
     *   Ou la valeur par defaut si absent.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	function omgGetConf($p_key) {
	  return $this->getConfiguration($p_key, '');
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGetType()
     * Description :
     *   Retourne l'un des 3 types majeurs d'équipement : 'gateway', 'device' ou 'zone'.
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	function omgGetType() {
	  return $this->getConfiguration('type', '');
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgIsType()
     * Description :
     *   example : if ($this->omgIsType(array('device','zone')))
     *   ou if ($this->omgIsType('device'))
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	function omgIsType($p_value) {
      $v_type = $this->omgGetType();
      if (is_array($p_value)) {
        foreach ($p_value as $v_item) {
          if ($v_type == $v_item) {
            return(true);
          }
        }
      }
      else if ($v_type == $p_value) {
        return(true);
      }
      return(false);
	}
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgCmdCreate()
     * Description :
     *   omgCmdCreate('confort', ['name'=>'Confort', 'type'=>'action', 'subtype'=>'other', 'isHistorized'=>0, 'isVisible'=>1, 'order'=>1]);
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgCmdCreate($p_cmd_id, $p_att_list=array()) {

      // ----- Look for existing command
      $v_cmd = $this->getCmd(null, $p_cmd_id);

      // ----- Look if command already exists in device
      if (is_object($v_cmd)) {
        openmqttgateway::log('debug', "Command '".$p_cmd_id."' already defined in device.");
        return($v_cmd);
      }

      // ----- Create Command

      openmqttgatewaylog::log('debug', "Create Cmd '".$p_cmd_id."' for device '".$this->getName()."'.");
      $v_cmd = new openmqttgatewayCmd();
      $v_cmd->setLogicalId($p_cmd_id);
      $v_cmd->setEqLogic_id($this->getId());
      
      foreach ($p_att_list as $v_key => $v_value) {
        if ($v_key == 'name') {
          $v_cmd->setName($v_value);
        }
        else if ($v_key == 'type') {
          $v_cmd->setType($v_value);
        }
        else if ($v_key == 'subtype') {
          $v_cmd->setSubType($v_value);
        }
        else if ($v_key == 'isHistorized') {
          $v_cmd->setIsHistorized($v_value);
        }
        else if ($v_key == 'isVisible') {
          $v_cmd->setIsVisible($v_value);
        }
        else if ($v_key == 'order') {
          $v_cmd->setOrder($v_value);
        }
        else if ($v_key == 'Unite') {
          $v_cmd->setUnite($v_value);
        }
        else if ($v_key == 'icon') {
          //$v_cmd->setDisplay('icon', '<i class="'.$v_value.'"></i>');
          $v_cmd->setDisplay('icon', $v_value);
          $v_cmd->setDisplay('showIconAndNamedashboard', "1");          
        }
      }


      /* Parametres de display des commandes :
      {"showStatsOnmobile":0,"showStatsOndashboard":0,"icon":"<i class=\"fab fa-hotjar \"><\/i>","showNameOndashboard":"1","showNameOnmobile":"1","showIconAndNamedashboard":"1","showIconAndNamemobile":"1","forceReturnLineBefore":"0","forceReturnLineAfter":"0","parameters":[]}
      fas fa-power-off
      fas fa-leaf
      far fa-snowflake
      */

/*
        if (isset($v_cmd_info['max_value'])) {
          $v_cmd->setConfiguration('maxValue', $v_cmd_info['max_value']);
        }
        if (isset($v_cmd_info['min_value'])) {
          $v_cmd->setConfiguration('minValue', $v_cmd_info['min_value']);
        }

        if (isset($v_cmd_info['generic_type']) && ($v_cmd_info['generic_type'] != '')) {
          $v_cmd->setGeneric_type($v_cmd_info['generic_type']);
        }
*/

      $v_cmd->save();
      
      return($v_cmd);
    }
    /* -------------------------------------------------------------------------*/




    /**---------------------------------------------------------------------------
     * Method : omgCmdGetValue()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
	public function omgCmdGetValue($p_cmd_logicalId) {
      if (!is_object($v_cmd = $this->getCmd(null, $p_cmd_logicalId))) {
        openmqttgateway::log('debug',  "Missing command '".$p_cmd_logicalId."' for equipement '".$this->getName()."'.");
        return('');
      }
      $v_value = $v_cmd->execCmd();
      return($v_value);
    }
    /* -------------------------------------------------------------------------*/    




    /**---------------------------------------------------------------------------
     * Method : omgGatewayUpdateBleAttribut()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayUpdateBleAttribut($p_name, $p_value) {

    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayUpdateSysAttribut()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayUpdateSysAttribut($p_attributes) {
      openmqttgateway::log('debug', "Update system properties for '".$this->getName()."' with ".json_encode($p_attributes));

      foreach ($p_attributes as $v_key => $v_value) {
        openmqttgateway::log('debug', "  Attribute '".$v_key."' = ".$v_value."");
        
        // ----- On regarde les attributs que l'on ne veut pas voir comme des commandes
        if (in_array($v_key, ['___att1','___att2'])) {
          // aucun pour l'intant
        }
        
        else {
          // ----- Look if command exists
          $v_cmd = $this->getCmd(null, $v_key);
          if (!is_object($v_cmd) && $this->omgGetConf('prop_auto_discover')) {            
            $v_subtype = 'string';
            if (is_string($v_value)) $v_subtype = 'string';
            if (is_numeric($v_value)) $v_subtype = 'numeric';
            if (is_bool($v_value)) $v_subtype = 'binary';
            if (is_array($v_value)) $v_subtype = 'string';
            $v_is_visible = 0;
            if (in_array($v_key, ['SSID','ip'])) {$v_is_visible = 1;}
            
            $v_cmd = $this->omgCmdCreate($v_key, ['name'=>$v_key,
                                        'type'=>'info',
                                        'subtype'=>$v_subtype, 
                                        'isHistorized'=>0, 
                                        'isVisible'=>$v_is_visible]);
          }
          
          // ----- Update value
          if (is_object($v_cmd)) {
            if (is_array($v_value)) $v_value = json_encode($v_value);
            $this->checkAndUpdateCmd($v_key, $v_value);
          }
        }
      }

    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayFlagRcvMqttMsg()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayFlagRcvMqttMsg() {    
      $this->setStatus('last_rcv_mqtt', time());
      $this->omgGatewayChangeToOnline();
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayChangeToOnline()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayChangeToOnline() {
      if (($v_online_status = $this->omgCmdGetValue('online_status')) == 1) {
        return;
      }
      openmqttgatewaylog::log('info', 'Gateway "'.$this->getName().'" passe en mode connectée.');
      $this->checkAndUpdateCmd('online_status', 1);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayChangeToOffline()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayChangeToOffline() {
      if (($v_online_status = $this->omgCmdGetValue('online_status')) == 0) {
        return;
      }
      openmqttgatewaylog::log('warning', 'Gateway "'.$this->getName().'" passe en mode déconnectée.');
      $this->checkAndUpdateCmd('online_status', 0);
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgGatewayCheckOnline()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgGatewayCheckOnline() {    
      openmqttgatewaylog::log('debug', 'omgGatewayCheckOnline()');
      if (($v_online_status = $this->omgCmdGetValue('online_status')) == 0) {
        return;
      }
      
      $v_last_ts = $this->getStatus('last_rcv_mqtt');
      $v_timeout = 60*$this->omgGetConf('online_timeout');
      
      //openmqttgatewaylog::log('debug', 'omgGatewayCheckOnline() last_rcv_mqtt : '.$v_last_ts);
      //openmqttgatewaylog::log('debug', 'omgGatewayCheckOnline() timeout : '.$v_timeout);
      //openmqttgatewaylog::log('debug', 'omgGatewayCheckOnline() time() : '.time());
      
      if (($v_last_ts + $v_timeout) < time()) {
        $this->omgGatewayChangeToOffline();
      }
    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceBrandChange()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgDeviceBrandChange($p_brand_model, $p_brand_score) {
    
      if ($p_brand_model == null) {
        $this->setConfiguration('device_brand_model', 'Generic:Generic');
        $this->setConfiguration('device_brand_score', 0);
        $this->setConfiguration('device_icon', '');
        $this->save();
        return;
      }
      
      openmqttgateway::log('debug', "omgDeviceBrandChange('".$p_brand_model['name']."')");
      
      // ----- Fixe les propriétés
      $this->setConfiguration('device_brand_model', $p_brand_model['name']);
      $this->setConfiguration('device_brand_score', $p_brand_score);
      $this->setConfiguration('device_icon', $p_brand_model['icon']);
      $this->save();
      
      // ----- Créé les commandes si besoin
      foreach ($p_brand_model['attributes'] as $v_att_name => $v_att) {
        if (isset($v_att['type']) && isset($v_att['cmd']) && ($v_att['type'] == 'cmd')) {

          // ----- Look if command exists
          $v_cmd = $this->getCmd(null, $v_att['cmd']['logicalId']);
          if (!is_object($v_cmd)) {            
            /*
            $v_cmd = $this->omgCmdCreate($v_att['cmd']['logicalId'], 
                                         ['name'=>$v_att['cmd']['name'],
                                          'type'=>$v_att['cmd']['type'],
                                          'subtype'=>$v_att['cmd']['subtype'], 
                                          'Unite'=>$v_att['cmd']['Unite'], 
                                          'isHistorized'=>$v_att['cmd']['isHistorized'], 
                                          'isVisible'=>$v_att['cmd']['isVisible']], 
                                          'icon'=>$v_att['cmd']['icon']]);
                                          */
            $v_cmd = $this->omgCmdCreate($v_att['cmd']['logicalId'], $v_att['cmd']);
          }

        }
      }

    }
    /* -------------------------------------------------------------------------*/

    /**---------------------------------------------------------------------------
     * Method : omgDeviceUpdateAttributes()
     * Description :
     * Parameters :
     * Returned value : 
     * ---------------------------------------------------------------------------
     */
    public function omgDeviceUpdateAttributes($p_attributes, $p_gateway=null) {
      openmqttgateway::log('debug', "Update attributs for '".$this->getName()."' with ".json_encode($p_attributes));
      
      $v_best_gateway = $this->omgGetConf('best_gateway');
      $v_best_gateway_rssi = $this->omgGetConf('best_gateway_rssi');
      $v_best_gateway_ts = $this->omgGetConf('best_gateway_ts');
      $v_rssi = -199;

      // ----- Regarde s'il y a un rssi
      if (isset($p_attributes['rssi']) && ($p_gateway !== null)) {
        // ----- Stock dans la liste des gateways qui voient l'objet
        // TBC
        
        // ----- Recupérer les valeurs du cycle d'hystérésis
        $v_rssi_hysteresis = config::byKey('multi_gw_rssi_hysteresis', 'openmqttgateway', '');
        $v_retention_time = config::byKey('multi_gw_retention_time', 'openmqttgateway', '');
        
        // ----- Regarde si c'est une nouvelle gateway avec un meilleur rssi
        $v_bgw_name = $p_gateway->omgGetConf('gateway_mqtt_topic');
        $v_rssi = $p_attributes['rssi'];
        if ($v_bgw_name != $v_best_gateway) {
          if (($v_best_gateway_rssi - $v_rssi_hysteresis) > $p_attributes['rssi']) {
            // ----- Si l'ancien message date de plus de 1 minute alors on prend en compte le nouveau quand même
            // Normalement l'émission BLE est reçue par toutes les gateways, donc si celle qui était la plus proche n'a rien
            // reçue depuis 1 minutes alors certainement l'objet a bougé et il faut prendre l'autre valeur. Ou la gateway
            // n'emet plus pour cet objet.
            if ((time() - $v_best_gateway_ts) > $v_retention_time) {
              openmqttgateway::log('debug', "Message venant d'une Gateway (".$v_bgw_name.") avec un moins bon rssi (".$v_rssi."/".$v_best_gateway_rssi."). Mais plus de message de la precedente.");
            }
            else {
              openmqttgateway::log('debug', "Message venant d'une Gateway (".$v_bgw_name.") avec un moins bon rssi (".$v_rssi."/".$v_best_gateway_rssi."). Ignore.");
              return;
            }
          }
          else {
            openmqttgateway::log('debug', "Message venant d'une Gateway (".$v_bgw_name.") avec un meilleur rssi (".$v_rssi.").");
          }
        }
        else {
          openmqttgateway::log('debug', "Message venant de la même gateway (".$v_bgw_name.") avec le rssi (".$v_rssi."/".$v_best_gateway_rssi.").");
        }
      }
      
      $v_current_brand_model_name = $this->omgGetConf('device_brand_model');
      $v_current_brand_score = $this->omgGetConf('device_brand_score');
      $v_brand_auto_discover = $this->omgGetConf('brand_auto_discover');
      $v_brand_model = null;
      
      if ($v_brand_auto_discover) {
      
        [$v_brand_model, $v_brand_score] = openmqttgateway::omgDeviceBrandBestMatch($p_attributes);
        //openmqttgateway::log('debug', "Auto-discover brand_model : '".$v_brand_model['name']."','".$v_brand_model['icon']."'"); 
        
        if (($v_brand_model != null) 
             && ($v_current_brand_model_name != $v_brand_model['name']) 
             && ($v_current_brand_score < $v_brand_score))  {
        
            openmqttgateway::log('debug', "Swap de brand_model '".$v_current_brand_model_name."' à '".$v_brand_model['name']."'"); 
            
            $this->omgDeviceBrandChange($v_brand_model, $v_brand_score);
            /*
            $this->setConfiguration('device_brand_model', $v_brand_model['name']);
            $this->setConfiguration('device_brand_score', $v_brand_score);
            $this->setConfiguration('device_icon', $v_brand_model['icon']);
            $this->save();
            */
            
        }
        else if ($v_brand_model != null) {
          openmqttgateway::log('debug', "Swap de brand_model ? non pas mieux (new='".$v_brand_model['name']."','".$v_brand_score."')"); 
        }
        else {
          openmqttgateway::log('debug', "Swap de brand_model ? non pas de proposition."); 
        }
        
      }
      
      if ($v_brand_model == null) {
        // TBC : load brand model by name
      }

      
      foreach ($p_attributes as $v_key => $v_value) {
        openmqttgateway::log('debug', "  Attribute '".$v_key."' = ".$v_value."");
        
        // ----- Look for not cmd attributes
        if (in_array($v_key, ['xxxxname','xxxxid'])) {
        }
        
        else {
          // ----- Look if command exists
          $v_cmd = $this->getCmd(null, $v_key);
          if (!is_object($v_cmd) && $this->omgGetConf('cmd_auto_discover')) {            
            $v_subtype = 'string';
            if (is_string($v_value)) $v_subtype = 'string';
            if (is_numeric($v_value)) $v_subtype = 'numeric';
            $v_is_visible = 0;
            
            if (($v_brand=='Xiaomi') && ($v_model == 'TH Sensor') ) {
              if (in_array($v_key, ['tempc','hum'])) {$v_is_visible=1;}
            }
            
            $v_cmd = $this->omgCmdCreate($v_key, ['name'=>$v_key,
                                        'type'=>'info',
                                        'subtype'=>$v_subtype, 
                                        'isHistorized'=>0, 
                                        'isVisible'=>$v_is_visible]);
          }
          
          // ----- Update value
          if (is_object($v_cmd)) {
            $this->checkAndUpdateCmd($v_key, $v_value);
          }
        }
      }
      
      openmqttgateway::log('debug', "Best rssi : ".$v_best_gateway_rssi." new rssi=".$v_rssi);
      
      if (($p_gateway !== null) && ($v_best_gateway_rssi < $v_rssi)) {
        $v_best = $p_gateway->omgGetConf('gateway_mqtt_topic');
        openmqttgateway::log('debug', "Best Gateway is now : ".$v_best." with rssi=".$v_rssi);
        $this->setConfiguration('best_gateway', $v_best);
        $this->setConfiguration('best_gateway_rssi', $v_rssi);
        $this->setConfiguration('best_gateway_ts', time());
        $this->save();
      }

      openmqttgateway::log('debug', "Update attributs done");
    }
    /* -------------------------------------------------------------------------*/




}

class openmqttgatewayCmd extends cmd {
    /*     * *************************Attributs****************************** */
    /* Commandes pour un device :
    *  De type'info' :
    *  mode : 'Confort', ...
    *  pilotage : 'manuel' ou 'prog'
    *  
    *  De type 'action' :
    *  
    */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        if ($this->getType() != 'action') {
			return;
		}                
        
        // ----- Get associated equipment
		$eqLogic = $this->getEqlogic();
                
        // ----- Get command logical id
        $v_logical_id = $this->getLogicalId();
        
        // ---- Look fos specific cmds per object type
        if ($eqLogic->omgIsType('gateway')) {
          return($this->execute_gateway($eqLogic, $v_logical_id, $_options));
        }
        

		if ($v_logical_id == 'auto') {        
          //$eqLogic->cpPilotageChangeTo($v_logical_id);
		  return;
		}

        openmqttgatewaylog::log('error', 'Unknown command '.$v_logical_id.' !');        
    }
    
    public function execute_gateway($p_gateway, $p_logical_id, $_options) {


     // $p_gateway->refreshWidget();
      

    }

    /*     * **********************Getteur Setteur*************************** */
}


