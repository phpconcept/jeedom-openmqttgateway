<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$plugin = plugin::byId('openmqttgateway');
sendVarToJS('eqType', $plugin->getId());

$eqLogics = openmqttgateway::omgGatewayList(array('_isEnable' => true));

?>

<div class="row row-overflow">
	<div class="col-xs-12" style="margin-bottom:10px;">
		<a class="btn btn-default btn-sm" href="index.php?v=d&p=openmqttgateway&m=openmqttgateway"><i class="fas fa-arrow-circle-left"></i> {{Retour}}</a>
	</div>

	<div class="col-xs-12" id="div_displayEquipement">
<?php
if (count($eqLogics) == 0) {
?>
		<div class="col-xs-12">
			<label class="alert alert-info">{{Aucune gateway configurée. Pour en ajouter une, retournez sur la page de gestion du plugin.}}</label>
		</div>
<?php
}
else {
	foreach ($eqLogics as $v_gateway) {
		echo $v_gateway->toHtml('dashboard');
	}
}
?>
	</div>
</div>

<script>

	if (typeof jeedomUtils !== 'undefined') { jeedomUtils.positionEqLogic(); } else { positionEqLogic(); }

	setTimeout(function () {
		$('#div_displayEquipement').packery({
			itemSelector: ".eqLogic-widget",
			gutter: 4
		});
	}, 2);

</script>

<?php include_file('core', 'plugin.template', 'js'); ?>
