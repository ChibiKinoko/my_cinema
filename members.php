<?php

include("includes/connectBDD.php");

// requete de base, pour affichage si aucun filtre
$query = 'SELECT `tp_membre`.`id_membre`, `tp_fiche_personne`.`nom` AS nom, `tp_fiche_personne`.`prenom` AS prenom , `tp_abonnement`.`nom` AS abo FROM `tp_membre` LEFT JOIN `tp_fiche_personne` ON `tp_membre`.`id_fiche_perso`=`tp_fiche_personne`.`id_perso` LEFT JOIN `tp_abonnement` ON `tp_abonnement`.`id_abo`=`tp_membre`.`id_abo`';

$placeHold = array();

if(!empty($_GET))
{
	if(isset($_GET['nom']) && $_GET['nom'] != "")
	{
		$nom = $_GET['nom'];
		$query .= ' WHERE `tp_fiche_personne`.`nom` LIKE ? ';
		$placeHold[] = "%$nom%";
	}
	if(isset($_GET['prenom']) && $_GET['prenom'] != "")
	{
		$prenom = $_GET['prenom'];
		$query .= ' WHERE `tp_fiche_personne`.`prenom` LIKE ? ';
		$placeHold[] = "%$prenom%";
	}
}

$query .= ' ORDER BY nom';

// execution pour obtenir le nombre total de resultats avant limit
$resultats = $bdd->prepare($query);
$resultats->execute($placeHold);
$pagination = $resultats->fetchAll();

$nbMembre = count($pagination); // compte le nombre de resultats dans le tableau
//var_dump($nbMembre);

$limit = 10;

if(isset($_GET['page'])) // recupration de la page courante
{
	$currentPage = $_GET['page'];
}
else
{
	$currentPage = 1; //remet sur page 1 si aucune page definie
}

if(isset($_GET['limit']))
{
	$limit = $_GET['limit'];
	$query .= ' LIMIT '.(($currentPage - 1)*$limit).", $limit";
}
else
{
	$query .= ' LIMIT '.(($currentPage - 1)*$limit).", $limit";
}

//var_dump($query);
//var_dump($placeHold);

$resultats = $bdd->prepare($query);
$resultats->execute($placeHold);
$avecFiltres = $resultats->fetchAll();

$nbPages = ceil($nbMembre/$limit);
//var_dump($nbPages);

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

		<section class="bloc_left">
			<header><h2>Recherche de membre(s)</h2></header>

			<form method="GET" name="searchMember" action="members.php" >
				<label for="nom">Nom : </label>
				<input type="text" id="nom" name="nom" />

				<label for="prenom">Prénom : </label>
				<input type="text" id="prenom" name="prenom" />

				<label for="limit">Affichage par page :</label>
				<input type="number" id="limit" name="limit" value="15" min="0" />

				<input type="submit" id="valid" name="Validation" value="Go !" />

			</form>
		</section>

		<section id="bloc_right">
			<header>Membres</header>
			<table id="indexMembres">
				<tr>
					<th>Nom</th>
					<th>Prénom</th>
					<th>Abo</th>
					<th>Infos</th>
				</tr>
				<?php
				if(!empty($avecFiltres))
				{
					foreach($avecFiltres as $elem)
					{
						?>
						<tr>
							<td><?php echo $elem['nom']; ?></td>
							<td><?php echo $elem['prenom']; ?></td>
							<td><?php echo $elem['abo']; ?></td>
							<td><a href="ficheMember.php<?php echo "?"."id=".$elem['id_membre']?>">Voir +</a></td>
						</tr>
						<?php
					}
				}
				else
				{
					?>
					<tr><td colspan="4"><?php echo "Aucune correspondance trouvée :("; ?></td></tr>
					<?php
				}
				?>
			</table>
			<?php
			if($currentPage < $nbPages)
			{
				?>
				<a href='<?php echo "?"; if(isset($nom)) echo "nom=" . $nom; if(isset($prenom)) echo "&amp;prenom=" . $prenom; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($currentPage+1); ?>' class="next" title="lien vers la page suivante">Suivant &rarr;</a>
				<?php
			}
			if($currentPage > 1)
			{
				?>
				<a href='<?php echo "?"; if(isset($nom)) echo "nom=" . $nom; if(isset($prenom)) echo "&amp;prenom=" . $prenom; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($currentPage-1); ?>' class="previous" title="lien vers la page précédente">&larr; Précédent</a>
				<?php
			}
			?>

		</section>

		<?php include("includes/footer.php"); ?>
	</div>

</body>
</html>