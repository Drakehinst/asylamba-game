<?php
# rankVictory component
# in rank package

# liste les joueurs aux meilleures victoires

# require
	# _T PRM 		PLAYER_RANKING_FRONT

include_once ZEUS;

ASM::$prm->changeSession($PLAYER_RANKING_FRONT);
$p = ASM::$prm->get(0);
$status = ColorResource::getInfo($p->color, 'status');

# display
echo '<div class="component profil">';
	echo '<div class="head">';
		echo '<h1>Joueur</h1>';
	echo '</div>';
	echo '<div class="fix-body">';
		echo '<div class="body">';
			echo '<div class="center-box">';
				echo '<span class="value">Meilleur joueur</span>';
			echo '</div>';

			echo '<div class="center-box">';
				echo '<span class="label">' . $status[$p->status - 1] . ' de ' . ColorResource::getInfo($p->color, 'popularName') . '</span>';
				echo '<span class="value">' . $p->name . '</span>';
			echo '</div>';

			echo '<div class="profil-flag color-' . $p->color . '">';
				echo '<img ';
					echo 'src="' . MEDIA . '/avatar/big/' . $p->avatar . '.png" ';
					echo 'alt="avatar de ' . $p->name . '" ';
				echo '/>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';