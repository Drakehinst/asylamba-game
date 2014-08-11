<?php
if (DEVMODE) {
include_once ZEUS;

$S_PAM1 = ASM::$pam->getCurrentSession();
ASM::$pam->newSession(FALSE);
ASM::$pam->load();

echo '<!DOCTYPE html>';
echo '<html lang="fr">';

echo '<head>';
	echo '<title>' . CTR::getTitle() . ' — ' . APP_SUBNAME . ' — Expansion</title>';

	echo '<meta charset="utf-8" />';
	echo '<meta name="description" content="' . APP_DESCRIPTION . '" />';

	echo '<link rel="icon" type="image/png" href="' . MEDIA . '/favicon/color1.png" />';
echo '</head>';
?>

<style type="text/css">
* {
	font-family: 'Trebuchet MS';
}
body {
	margin: 0; padding: 0;
	background: url('http://asylamba.com/public/css/src/global/black.jpg');
}

h1 {
	margin: 0; padding: 50px 0;
	text-align: center;
}

.content {
	width: 1000px;
	padding: 25px; margin: auto;
	background: #efefef;
	border-radius: 5px;
}

.content a {
	position: relative;
	text-decoration: none;
	display: inline-block;
	width: 192px;
	color: black;
	margin: 0 0 20px 0;
}
.content a em {
	display: block;
	padding: 0 0 0 25px;
}
.content a strong {
	display: block;
	padding: 0 0 10px 25px;
	font-size: 20px;
	line-height: 22px;
	font-weight: bold;
}
.content a img {
	display: block;
	padding: 8px;
	width: 200px; height: 200px;
	background: #0a0a0a;
	border-radius: 100%;
	border: solid 6px black;
	box-shadow: 0 0 5px 4px black;
}
.content a.color1 img { border-color: #b01e2d; }
.content a.color2 img { border-color: #2f23c0; }
.content a.color3 img { border-color: #ffdb0f; }
.content a.color4 img { border-color: #a935c7; }
.content a.color5 img { border-color: #57c632; }
.content a.color6 img { border-color: #05bed7; }
.content a.color7 img { border-color: #ac5832; }

.content a .number {
	position: absolute;
	width: 45px; height: 45px; line-height: 45px;
	font-size: 20px; color: white;
	border-radius: 100%;
	background: #0a0a0a;
	text-align: center;
	bottom: 5px; right: -10px;
	border: solid 1px #202020;
}
</style>

<body>
	<h1><img src="http://asylamba.com/public/media/asylamba.png" alt="test" /></h1>

	<div class="content">
<?php
		echo '<a href="' . APP_ROOT . 'inscription/bindkey-' . Utils::generateString(10) . '">';
			echo '<em>Créer un</em>';
			echo '<strong>Personnage</strong>';
			echo '<img src="' . MEDIA . 'avatar/big/000-0.png" alt="" />';
		echo '</a>';

		for ($i = 0; $i < ASM::$pam->size(); $i++) {
			$player = ASM::$pam->get($i);

			echo '<a class="color' . $player->rColor . '" href="' . APP_ROOT . 'connection/bindkey-' . $player->bind . '">';
				echo '<em>Grade ' . $player->level . '</em>';
				echo '<strong>' . $player->name . '</strong>';
				echo '<img src="' . MEDIA . 'avatar/big/' . $player->avatar . '.png" alt="" />';
				echo '<span class="number">' . $player->level . '</span>';
			echo '</a>';
		}
?>
	</div>
</body>

<?php
ASM::$pam->changeSession($S_PAM1);
}