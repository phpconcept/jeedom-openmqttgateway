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
require_once dirname(__FILE__) . '/../../../plugins/openmqttgateway/core/php/openmqttgateway_const.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>


<form class="form-horizontal">

    <fieldset>

        <div class="form-group">
            <div class="col-sm-9">
                   <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Paramètres MQTT}}</div>
            </div>
        </div>

        <div class="row form-group">
            <label class="col-lg-4 control-label">{{MQTT Topic : }}</label>
            <div class="col-lg-5">
                <input type="text" class="configKey form-control" data-l1key="mqtt_base_topic">
            </div>
        </div>

        <div class="row form-group">
            <label class="col-lg-4 control-label">{{Auto-découverte des Gateways : }}</label>
            <div class="col-lg-5">
                <input type="checkbox" class="configKey form-control" data-l1key="gateway_auto_discover" checked/>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-9">
                   <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">{{Calcul de proximité en mode multi-Gateways}}</div>
            </div>
        </div>

        <div class="row form-group">
            <label class="col-lg-4 control-label">{{RSSI Interval Cycle d'hyst&eacute;r&eacute;sis (dBm) : }}
            <sup><i class="fa fa-question-circle tooltips" title="{{Nombre de dBm supérieur à prendre en compte pour changer de gateway la plus proche. Recommandé 5.}}"></i></sup>
            </label>
            <div class="col-lg-5">
                <input type="text" class="configKey form-control" data-l1key="multi_gw_rssi_hysteresis">
            </div>
        </div>

        <div class="row form-group">
            <label class="col-lg-4 control-label">{{Temps de rétention (sec) : }}
            <sup><i class="fa fa-question-circle tooltips" title="{{Temps minimum avant de prendre la valeur d'une autre gateway avec un moins bon RSSI.}}"></i></sup>
            </label>
            <div class="col-lg-5">
                <input type="text" class="configKey form-control" data-l1key="multi_gw_retention_time">
            </div>
        </div>

        <div class="row form-group">
            <label class="col-lg-4 control-label"></label>
            <label class="col-lg-8 control-label center-right"><i>{{Github Version}} : <?php echo OMG_VERSION;?></i></label>
        </div>


</fieldset>
</form>

<?php include_file('desktop', 'openmqttgateway_configuration', 'js', 'openmqttgateway'); ?>

