<?php

include("includes/connectBDD.php");

$idFilm = $_GET['idFilm'];

// recuperations informations film
$sql = $bdd->query("SELECT `tp_distrib`.`nom` AS distrib, `tp_genre`.`nom` AS genre, `tp_film`.titre AS titre, `tp_film`.`annee_prod` AS annee, `tp_film`.`duree_min` AS duree, `tp_film`.`resum` AS resume FROM `tp_film` LEFT JOIN `tp_genre` ON `tp_film`.`id_genre`=`tp_genre`.`id_genre` LEFT JOIN `tp_distrib` ON `tp_film`.`id_distrib`=`tp_distrib`.`id_distrib` WHERE `tp_film`.`id_film`=$idFilm");
$infoFilm = $sql->fetch();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="css/style.css" />

	<title>My_Cinema</title>
</head>
<body>

	<div id="main">
		<?php 
		include("includes/header.php");
		include("includes/nav.php"); ?>

		<section id="infosFilm">
			<header><h2><?php echo $infoFilm['titre']; ?></h2></header>

			<p>Durée : <?php echo $infoFilm['duree']; ?> minutes</p>
			<p>Année : <?php if($infoFilm['annee'] != 0) echo $infoFilm['annee']; else echo "inconnue"; ?></p>
			<p>Genre : <?php if($infoFilm['genre'] != NULL) echo $infoFilm['genre']; else echo "non renseigné"; ?></p>
			<p>Distributeur : <?php if($infoFilm['distrib'] != NULL) echo $infoFilm['distrib']; else echo "non renseigné"; ?></p>
			<p>Résumé : </p>
			<?php echo $infoFilm['resume']; ?>
		

		</section>

		<?php include("includes/footer.php"); ?>
	</div>

</body>
</html>