<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
// Obtenir l'identifiant du plugin
$plugin = plugin::byId('sonoffdiy');
// Charger le javascript
sendVarToJS('eqType', $plugin->getId());
//sendVarToJS('serveurtest', 'lionel dans sonoffdiy.php');
// Accéder aux données du plugin
$eqLogics = eqLogic::byType($plugin->getId());
$logicalIdToHumanReadable = array();

?>

<!-- Container global (Ligne bootstrap) -->
<div class="row row-overflow">
  <!-- Container des listes de commandes / éléments -->
  <div class="col-xs-12 eqLogicThumbnailDisplay">
    <legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
    <div class="eqLogicThumbnailContainer">
		<!-- + -->
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
			<i class="fas fa-plus-circle" style="font-size : 5em;color:#48a4a6;"></i>
			<br />
			<span style="color:#48a4a6">{{Ajouter}}</span>
		</div>
		<!-- Bouton d accès à la configuration -->
		<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
			<i class="fas fa-wrench" style="font-size : 5em;color:#48a4a6;"></i>
			<br />
			<span style="color:#48a4a6">{{Configuration}}</span>
		</div>
    </div>
    <!-- Début de la liste des objets -->
    <legend><i class="fas fa-table"></i> {{Mes Sonoff DIY}}</legend>
	<div class="input-group" style="margin-bottom:5px;">
		<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div class="input-group-btn">
			<a id="bt_resetEqlogicSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
		</div>
	</div>	
    <!-- Container de la liste -->
	<div class="panel">
		<div class="panel-body">
			<div class="eqLogicThumbnailContainer prem">
<?php
foreach($eqLogics as $eqLogic) {
	if (($eqLogic->getConfiguration('devicetype') != "Smarthome") && ($eqLogic->getConfiguration('devicetype') != "Player") && ($eqLogic->getConfiguration('devicetype') != "PlayList")) {
		$opacity = ($eqLogic->getIsEnable()) ? '' : ' disableCard';
		echo '<div class="eqLogicDisplayCard cursor prem '.$opacity.'" data-eqLogic_id="'.$eqLogic->getId().'" >';
		echo '<img class="lazy" src="'.$plugin->getPathImgIcon().'" style="min-height:75px !important;" />';
		echo "<br />";
		echo '<span class="name">'.$eqLogic->getHumanName(true, true).'</span>';
		echo '</div>';
	}
}
?>
			</div>
		</div>
    </div>
	
  </div>
  <!-- Container du panneau de contrôle -->
  <div class="col-lg-12 eqLogic" style="display: none;">
    <!-- Bouton sauvegarder -->
    <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
    <!-- Bouton Supprimer -->
    <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
    <!-- Bouton configuration avancée -->
    <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a>
   <!-- Liste des onglets -->
    <ul class="nav nav-tabs" role="tablist">
      <!-- Bouton de retour -->
      <li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
      <!-- Onglet "Equipement" -->
      <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
      <!-- Onglet "Commandes" -->
      <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
    </ul>
    <!-- Container du contenu des onglets -->
    <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
      <div role="tabpanel" class="tab-pane active" id="eqlogictab">
        <br/>
        <div class="row">
          <div class="col-sm-6">
            <form name="formulaire" class="form-horizontal">
              <fieldset>
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Nom de l'équipement Jeedom}}</label>
                  <div class="col-sm-8">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Amazon}}"/>
                  </div>
                </div>
                <!-- Onglet "Objet Parent" -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Objet parent}}</label>
                  <div class="col-sm-6">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;"/>
                    <select class="eqLogicAttr form-control" data-l1key="object_id">
                    <option value="">{{Aucun}}</option>
<?php
foreach (jeeObject::all() as $object)
    echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
?>
                    </select>
                  </div>
                </div>

                <!-- Catégorie" -->
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Catégorie}}</label>
                  <div class="col-sm-8">
<?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value)
{
    echo '<label class="checkbox-inline">';
    echo '  <input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
    echo '</label>';
}
?>
                  </div>
                </div>
                <!-- Onglet "Active Visible" -->
                <div class="form-group">
                  <label class="col-sm-4 control-label"></label>
                  <div class="col-sm-8">
                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                  </div>
                </div><br><br>



				
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Adresse IP}}</label>
                  <div class="col-sm-8">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="adresse_ip" placeholder="{{192.168.0.xx}}"/>
                  </div>
                </div>	
                <div class="form-group">
                  <label class="col-sm-4 control-label">{{Device ID}}</label>
                  <div class="col-sm-8">
                    <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="device_id" placeholder="{{xxxxxxxxxx}}"/>
                  </div>
                </div>					
              </fieldset>
            </form>
          </div>

      </div>
      </div>

      <div role="tabpanel" class="tab-pane" id="commandtab">
        

        <table id="table_cmd" class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th style="width: 40px;">#</th>
              <th style="width: 200px;">{{Nom}}</th>
              <th style="width: 150px;">{{Type}}</th>
              <th style="width: 300px;">{{Commande}}</th>
              <th style="width: 150px;">{{Paramètres}}</th>
              <th style="width: 100px;"></th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
		

		
		<form class="form-horizontal">
          <fieldset>
            <div class="form-actions">
            </div>
          </fieldset>
        </form>
		
      </div>
    </div>
  </div>
</div>
<?php include_file('desktop', 'sonoffdiy', 'js', 'sonoffdiy'); ?>
<?php include_file('desktop', 'sonoffdiy', 'css', 'sonoffdiy'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
<script>
$('#in_searchEqlogic').off('keyup').keyup(function () {
  var search = $(this).value().toLowerCase();
  search = search.normalize('NFD').replace(/[\u0300-\u036f]/g, "")
  if(search == ''){
    $('.eqLogicDisplayCard.prem').show();
    $('.eqLogicThumbnailContainer.prem').packery();
    return;
  }
  $('.eqLogicDisplayCard.prem').hide();
  $('.eqLogicDisplayCard.prem .name').each(function(){
    var text = $(this).text().toLowerCase();
    text = text.normalize('NFD').replace(/[\u0300-\u036f]/g, "")
    if(text.indexOf(search) >= 0){
      $(this).closest('.eqLogicDisplayCard.prem').show();
    }
  });
  $('.eqLogicThumbnailContainer.prem').packery();
});
</script>
