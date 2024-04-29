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

try {
    //require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    require_once dirname(__FILE__) . "/../../../../plugins/openmqttgateway/core/php/openmqttgateway.inc.php";
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }
    
    ajax::init();

	if (init('action') == 'omgDeviceRemoveAll') {
		$v_val = openmqttgateway::omgDeviceRemoveAll();
		ajax::success("{}");
	}
    
	if (init('action') == 'omgInclusionStart') {
		$v_val = openmqttgateway::omgInclusionStart();
		ajax::success("{}");
	}
    
	if (init('action') == 'omgInclusionStop') {
		$v_val = openmqttgateway::omgInclusionStop();
		ajax::success(json_encode($v_val, JSON_FORCE_OBJECT));
	}
    
	if (init('action') == 'omgInclusionFinish') {
		$v_val = openmqttgateway::omgInclusionFinish();
		ajax::success("{}");
	}
    
	if (init('action') == 'omgInclusionAddDevice') {
        $v_id = init('id');
        //openmqttgatewaylog::log('debug', 'ajax id='.$v_id);
        
        // TBC : j'ai du encadrer cette fonction de ob_start/ob_end_clean, 
        // sinon j'avais des erreurs javascript ... vraiment strange
        ob_start();
		$v_val = openmqttgateway::omgInclusionAddDevice($v_id);
        //$out2 = ob_get_contents();
        ob_end_clean();

		ajax::success(json_encode($v_val, JSON_FORCE_OBJECT));
	}
    
	if (init('action') == 'omgInclusionGetList') {
		$v_val = openmqttgateway::omgInclusionGetList();
		ajax::success(json_encode($v_val, JSON_FORCE_OBJECT));
	}
    
    

    throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayException($e), $e->getCode());
}

