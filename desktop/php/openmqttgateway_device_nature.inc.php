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
    <div class="col-sm-8">

      <select id="omg_device_select_nature" class="cp_attr_device eqLogicAttr form-control" data-l1key="configuration" data-l2key="device_brand_model">
          <option value="Generic:Generic">Generic - Generic</option>
          <?php
            $v_brand = '';
            foreach (openmqttgateway::omgDeviceBrandList() as $v_item) {
              if ($v_item['brand'] != $v_brand) {
                echo '<option style="background-color: #039be5  !important; color: white !important; font-style: normal !important;" disabled>'.$v_item['brand'].'</option>';
                $v_brand = $v_item['brand'];
              }
              
              echo '<option value="'.$v_item['name'].'" >&nbsp;&nbsp;'.$v_item['brand'].' - '.$v_item['model'].'</option>';
            }
          ?>
      </select>

    </div>
  </div>

  <div class="row form-group">
    <label class="col-sm-4 control-label">{{Auto-découverte du fabriquant et du modèle :}}</label>
    <div class="col-sm-8">
      <input type="checkbox" class="cp_attr_device eqLogicAttr form-control" data-l1key="configuration" data-l2key="brand_auto_discover" checked/>      
    </div>
  </div>


