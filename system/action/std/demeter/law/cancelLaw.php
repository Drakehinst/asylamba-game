<?php
include_once DEMETER;
include_once ZEUS;

#rlaw	id de la loi

$rLaw = Utils::getHTTPData('rlaw');


if ($rLaw !== FALSE) {
	if (CTR::$data->get('playerInfo')->get('status') == LawResources::getInfo($type, 'department')) {
		$_LAM = ASM::$lam->getCurrentSession();
		ASM::$lam->newSession();
		ASM::$lam->load(array('id' => $rLaw));

		if (ASM::$lam->size() > 0) {
		} else {
			CTR::$alert->add('Cette loi n\'existe pas.', ALERT_STD_ERROR);
		}

		ASM::$cam->changeSession($_LAM);
	} else {
		CTR::$alert->add('Vous n\'avez pas le droit d\'annauler cette loi.', ALERT_STD_ERROR);
	}
} else {
	CTR::$alert->add('Informations manquantes.', ALERT_STD_ERROR);
}