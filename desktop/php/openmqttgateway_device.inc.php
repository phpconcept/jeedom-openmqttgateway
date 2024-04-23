<?php

/*
* Ce fichier est automatiquement inclu dans le fichier openmqttgateway.php
* 
* Il contient la partie configuration de l'équipement de type "device"
*/
?>

  <div class="row">
    <div class="col-sm-12">
      <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Propriétés de l'objet}}</div>
    </div>
  </div>




  <div class="row"><div class="col-sm-12">&nbsp;</div></div>



  <div class="row form-group">
    <label class="col-sm-2 control-label">{{MQTT Topic :}}</label>
    <div class="col-sm-7">
      <input type="text" class="cp_attr_device eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="device_mqtt_topic" placeholder="{{Nom du topic pour cet objet}}"/>      
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-2 control-label">{{Auto-découverte des commandes :}}</label>
    <div class="col-sm-7">
      <input type="checkbox" class="cp_attr_device eqLogicAttr form-control" data-l1key="configuration" data-l2key="cmd_auto_discover" checked/>      
    </div>
  </div>





  <div class="row form-group">
    <label class="col-sm-2 control-label">&nbsp;</label>
  </div>


