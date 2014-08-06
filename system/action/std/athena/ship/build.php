<?php
include_once ATHENA;
include_once PROMETHEE;
# build ship action

# int baseid 		id (rPlace) de la base orbitale
# int ship 			id du vaisseau
# int quantity 		nombre de vaisseaux à construire

for ($i=0; $i < CTR::$data->get('playerBase')->get('ob')->size(); $i++) { 
	$verif[] = CTR::$data->get('playerBase')->get('ob')->get($i)->get('id');
}

$baseId = Utils::getHTTPData('baseid');
$ship = Utils::getHTTPData('ship');
$quantity = Utils::getHTTPData('quantity');

if ($baseId !== FALSE AND $ship !== FALSE AND $quantity !== FALSE AND in_array($baseId, $verif) AND $quantity != 0) { 
	if (ShipResource::isAShip($ship)) {
		$S_OBM1 = ASM::$obm->getCurrentSession();
		ASM::$obm->newSession(ASM_UMODE);
		ASM::$obm->load(array('rPlace' => $baseId));
		$ob  = ASM::$obm->get();
		if (OrbitalBaseResource::isAShipFromDock1($ship)) {
			$dockType = 1;
		} elseif (OrbitalBaseResource::isAShipFromDock2($ship)) {
			$dockType = 2;
			$quantity = 1;
			if (CTR::$data->get('playerInfo')->get('color') == ColorResource::EMPIRE) {
				# bonus if the player is from the Empire
				$price -= round($price * ColorResource::BONUS_CARDAN_COLO / 100);
			}
		} else {
			$dockType = 3;
			$quantity = 1;
		}
		$S_SQM1 = ASM::$sqm->getCurrentSession();
		ASM::$sqm->newSession(ASM_UMODE);
		ASM::$sqm->load(array('rOrbitalBase' => $baseId, 'dockType' => $dockType), array('dEnd'));
		$technos = new Technology(CTR::$data->get('playerId'));
		if (ShipResource::haveRights($ship, 'resource', $ob->getResourcesStorage(), $quantity)
			AND ShipResource::haveRights($ship, 'queue', ASM::$sqm->size())
			AND ShipResource::haveRights($ship, 'shipTree', $ob)
			AND ShipResource::haveRights($ship, 'pev', $ob, $quantity)
			AND ShipResource::haveRights($ship, 'techno', $technos)) {

			# tutorial
			if (CTR::$data->get('playerInfo')->get('stepDone') == FALSE) {
				include_once ZEUS;
				switch (CTR::$data->get('playerInfo')->get('stepTutorial')) {
					case TutorialResource::BUILD_SHIP0:
						if ($ship == ShipResource::PEGASE) {
							TutorialHelper::setStepDone();
						}
						break;
				}
			}

			// construit le(s) nouveau(x) vaisseau(x)
			$sq = new ShipQueue();
			$sq->rOrbitalBase = $baseId;
			$sq->dockType = $dockType;
			$sq->shipNumber = $ship;
			$sq->quantity = $quantity;

			$time = ShipResource::getInfo($ship, 'time') * $quantity;
			switch ($dockType) {
				case 1:
					$playerBonus = PlayerBonus::DOCK1_SPEED;
					break;
				case 2:
					$playerBonus = PlayerBonus::DOCK2_SPEED;
					break;
				case 3:
					$playerBonus = PlayerBonus::DOCK3_SPEED;
					break;
			}
			$bonus = $time * CTR::$data->get('playerBonus')->get($playerBonus) / 100;
			if (ASM::$sqm->size() == 0) {
				$sq->dStart = Utils::now();
			} else {
				$sq->dStart = ASM::$sqm->get(ASM::$sqm->size() - 1)->dEnd;
			}
			$sq->dEnd = Utils::addSecondsToDate($sq->dStart, round($time - $bonus));
			ASM::$sqm->add($sq);
			// débit des ressources au joueur
			$resourcePrice = ShipResource::getInfo($ship, 'resourcePrice') * $quantity;
			if ($ship == ShipResource::CERBERE || $ship == ShipResource::PHENIX) {
				if (CTR::$data->get('playerInfo')->get('color') == ColorResource::EMPIRE) {
					# bonus if the player is from the Empire
					$resourcePrice -= round($resourcePrice * ColorResource::BONUS_EMPIRE_CRUISER / 100);
				}
			}
			$ob->decreaseResources($resourcePrice);

			// ajout de l'event dans le contrôleur
			CTR::$data->get('playerEvent')->add($sq->dEnd, EVENT_BASE, $baseId);

			// alerte
			if ($quantity == 1) {
				CTR::$alert->add('Construction d\'' . (ShipResource::isAFemaleShipName($ship) ? 'une ' : 'un ') . ShipResource::getInfo($ship, 'codeName') . ' commandée', ALERT_STD_SUCCESS);
			} else {
				CTR::$alert->add('Construction de ' . $quantity . ' ' . ShipResource::getInfo($ship, 'codeName') . Format::addPlural($quantity) . ' commandée', ALERT_STD_SUCCESS);
			}
		} else {
			CTR::$alert->add('les conditions ne sont pas remplies pour construire ce vaisseau', ALERT_STD_ERROR);
		}
		ASM::$obm->changeSession($S_OBM1);
		ASM::$sqm->changeSession($S_SQM1);
	} else {
		CTR::$alert->add('construction de vaisseau impossible - vaisseau inconnu', ALERT_STD_ERROR);
	}
} else {
	CTR::$alert->add('pas assez d\'informations pour construire un vaisseau', ALERT_STD_FILLFORM);
}
?>