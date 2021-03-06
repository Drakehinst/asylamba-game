<?php

use Asylamba\Classes\Worker\ASM;
use Asylamba\Classes\Worker\CTR;
use Asylamba\Modules\Demeter\Resource\LawResources;
use Asylamba\Modules\Demeter\Resource\ColorResource;
use Asylamba\Classes\Library\Format;
use Asylamba\Modules\Demeter\Model\Law\Law;

echo '<div class="component profil player">';
	echo '<div class="head"></div>';
	echo '<div class="fix-body">';
		echo '<div class="body">';
			echo '<div class="build-item base-type">';
				echo '<div class="name">';
					echo '<img src="' . MEDIA . 'faction/law/common.png" alt="">';
					echo '<strong>' . LawResources::getInfo($governmentLaw_id, 'name') . '</strong>';
				echo '</div>';

				echo '<p class="desc">' . LawResources::getInfo($governmentLaw_id, 'shortDescription') . '</p>';

				if (LawResources::getInfo($governmentLaw_id, 'bonusLaw')) {
					echo '<form action="' . Format::actionBuilder('createlaw', ['type' => $governmentLaw_id]) . '" method="post">';
						echo '<input type="text" placeholder="Nombre de relèves d\'activité" name="duration" />';

						echo '<button class="button">';
							echo '<span class="text">';
								echo 'Soumettre au vote<br />';
								echo 'Coûte ' . Format::number(LawResources::getInfo($governmentLaw_id, 'price') * $nbPlayer) . ' <img class="icon-color" src="' . MEDIA . 'resources/credit.png" alt="crédits"> par relève à la faction';
							echo '</span>';
						echo '</button>';
					echo '</form>';
				} else {
					echo '<form action="' . Format::actionBuilder('createlaw', ['type' => $governmentLaw_id]) . '" method="post">';
						if ($governmentLaw_id == Law::SECTORTAX) {
							echo '<input type="text" placeholder="Nouvel impôt en pourcent" name="taxes" />';
							
							$S_SEM_T = ASM::$sem->getCurrentSession();
							ASM::$sem->changeSession($S_SEM_LAW);

							echo '<select name="rsector">';
								echo '<option value="-1">Choisissez un secteur</option>';
								for ($j = 0; $j <ASM::$sem->size(); $j++) {
									echo '<option value="' . ASM::$sem->get($j)->id . '">' . ASM::$sem->get($j)->name . ' (taxe ' . ASM::$sem->get($j)->tax . '%)</option>';
								}
							echo '</select>';

							ASM::$sem->changeSession($S_SEM_T);
						} elseif ($governmentLaw_id == Law::SECTORNAME) {
							echo '<input type="text" placeholder="Nouveau nom du secteur" name="name" />';
							
							$S_SEM_T = ASM::$sem->getCurrentSession();
							ASM::$sem->changeSession($S_SEM_LAW);

							echo '<select name="rsector">';
								echo '<option value="-1">Choisissez un secteur</option>';
								for ($j = 0; $j < ASM::$sem->size(); $j++) {
									echo '<option value="' . ASM::$sem->get($j)->id . '">' . ASM::$sem->get($j)->name . ' (#' . ASM::$sem->get($j)->id . ')</option>';
								}
							echo '</select>';

							ASM::$sem->changeSession($S_SEM_T);
						} elseif ($governmentLaw_id == Law::NEUTRALPACT) {

							echo '<select name="rcolor">';
								echo '<option value="-1">Choisissez une faction</option>';
								foreach ($faction->colorLink as $j => $k) {
									if ($j != 0 && $j != $faction->id) {
										echo '<option value="' . ColorResource::getInfo($j, 'id') . '">' . ColorResource::getInfo($j, 'officialName') . '</option>';
									}
								}
							echo '</select>';
						} elseif ($governmentLaw_id == Law::PEACEPACT) {

							echo '<select name="rcolor">';
								echo '<option value="-1">Choisissez une faction</option>';
								foreach ($faction->colorLink as $j => $k) {
									if ($j != 0 && $j != $faction->id) {
										echo '<option value="' . ColorResource::getInfo($j, 'id') . '">' . ColorResource::getInfo($j, 'officialName') . '</option>';
									}
								}
							echo '</select>';
						} elseif ($governmentLaw_id == Law::WARDECLARATION) {

							echo '<select name="rcolor">';
								echo '<option value="-1">Choisissez une faction</option>';
								foreach ($faction->colorLink as $j => $k) {
									if ($j != 0 && $j != $faction->id) {
										echo '<option value="' . ColorResource::getInfo($j, 'id') . '">' . ColorResource::getInfo($j, 'officialName') . '</option>';
									}
								}
							echo '</select>';
						} elseif ($governmentLaw_id == Law::TOTALALLIANCE) {

							echo '<select name="rcolor">';
								echo '<option value="-1">Choisissez une faction</option>';
								foreach ($faction->colorLink as $j => $k) {
									if ($j != 0 && $j != $faction->id) {
										echo '<option value="' . ColorResource::getInfo($j, 'id') . '">' . ColorResource::getInfo($j, 'officialName') . '</option>';
									}
								}
							echo '</select>';
						} elseif (in_array($governmentLaw_id, array(Law::COMTAXEXPORT, Law::COMTAXIMPORT))) {
							echo '<input type="text" placeholder="Nouvelle taxe en pourcent" name="taxes" />';
							echo '<select name="rcolor">';
								echo '<option value="-1">Choisissez une faction</option>';
								foreach ($faction->colorLink as $j => $k) {
									if ($j != 0) {
										echo '<option value="' . ColorResource::getInfo($j, 'id') . '">' . ColorResource::getInfo($j, 'popularName') . '</option>';
									}
								}
							echo '</select>';
						} elseif ($governmentLaw_id == Law::PUNITION) {
							echo '<input type="text" placeholder="Montant de l\'amende" name="credits" />';

							$S_PAM_LAW = ASM::$pam->getCurrentSession();
							ASM::$pam->newSession(FALSE);
							ASM::$pam->load(
								['rColor' => CTR::$data->get('playerInfo')->get('color'), 'statement' => [PAM_ACTIVE, PAM_INACTIVE, PAM_HOLIDAY]], 
								['name', 'ASC']
							);

							echo '<select name="rplayer">';
								echo '<option value="-1">Choisissez un joueur</option>';
								for ($j = 1; $j < ASM::$pam->size(); $j++) {
									echo '<option value="' . ASM::$pam->get($j)->id . '">' . ASM::$pam->get($j)->name . '</option>';
								}
							echo '</select>';

							ASM::$pam->changeSession($S_PAM_LAW);
						}

						echo '<button class="button ' . ($faction->credits >= LawResources::getInfo($governmentLaw_id, 'price') ? NULL : 'disable') . '">';
							echo '<span class="text">';
								if (LawResources::getInfo($governmentLaw_id, 'department') == 6) {
									echo 'Appliquer<br />';
								} else {
									echo 'Soumettre au vote<br />';
								}
								echo 'Coûte ' . Format::number(LawResources::getInfo($governmentLaw_id, 'price')) . ' <img class="icon-color" src="' . MEDIA . 'resources/credit.png" alt="crédits"> à la faction';
							echo '</span>';
						echo '</button>';
					echo '</form>';
				}
			echo '</div>';

			echo '<p class="info">' . LawResources::getInfo($governmentLaw_id, 'longDescription') . '</p>';
		echo '</div>';
	echo '</div>';
echo '</div>';