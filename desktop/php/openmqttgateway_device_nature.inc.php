<?php

/*
* Ce fichier est automatiquement inclu dans le fichier openmqttgateway.php
* 
* Il contient la partie configuration de l'équipement de type "device"
*/
?>

  <div class="form-group">
      <div class="col-sm-12">
             <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Nature de l'objet}}</div>
      </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-4 control-label">{{Fabriquant et Modèle :}}</label>
    <div class="col-sm-5">
      <input type="text" class="cp_attr_device eqLogicAttr form-control" style="width: 100%;" data-l1key="configuration" data-l2key="device_brand_model"/>      
    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-4 control-label">{{Auto-découverte du fabriquant et du modèle :}}</label>
    <div class="col-sm-5">
      <input type="checkbox" class="cp_attr_device eqLogicAttr form-control" data-l1key="configuration" data-l2key="brand_auto_discover" checked/>      
    </div>
  </div>


