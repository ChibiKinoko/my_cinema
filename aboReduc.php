<?php

include("includes/connectBDD.php");

// tableau abonnements sans abo id 5 == pas d'abo
$query = 'SELECT `id_abo`, `nom`, `resum`, `prix`, `duree_abo` AS duree FROM `tp_abonnement` WHERE `id_abo` != 5 ORDER BY `prix`';

$abos = $bdd->query($query);
$abos = $abos->fetchAll();

// tableau reductions
$query = 'SELECT `nom`, `date_debut` AS debut, `date_fin` AS fin, `pourcentage_reduc` AS pourcentage FROM `tp_reduction` ORDER BY pourcentage';

$reduc = $bdd->query($query);
$reduc = $reduc->fetchAll();

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

		<section id="abo">
			<header><h2>Abonnements</h2></header>
			<table>
				<tr>
					<th>Nom</th>
					<th>Détails</th>
					<th>Prix</th>
					<th>Durée</th>
				</tr>
				<?php
				foreach ($abos as $elem) 
				{
					?>
					<tr>
						<td><?php echo $elem['nom']; ?></td>
						<td><?php echo $elem['resum']; ?></td>
						<td><?php echo $elem['prix']; ?></td>
						<td><?php echo $elem['duree']; ?></td>
					</tr>
					<?php
				}
				?>
			</table>

		</section>

		<section id="reduc">
			<header><h2>Réductions</h2></header>
			<table>
				<tr>
					<th>Nom</th>
					<th>Début</th>
					<th>Fin</th>
					<th>Pourcentage</th>
				</tr>
				<?php
				foreach ($reduc as $elem) 
				{
					?>
					<tr>
						<td><?php echo $elem['nom']; ?></td>
						<td><?php 
						if($elem['debut'] == "")
						{
							echo "-";
						}
						else
						{
							echo $elem['debut'];
						} ?>
					</td>
					<td>
						<?php
						if($elem['fin'] == "")
						{
							echo "-";
						}
						else
						{
							echo $elem['fin'];
						} ?>
					</td>
					<td><?php echo $elem['pourcentage']; ?></td>
				</tr>
				<?php
			}
			?>
		</table>

	</section>



	<?php include("includes/footer.php"); ?>
</div>

</body>
</html>