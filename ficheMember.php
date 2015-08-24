<?php

include("includes/connectBDD.php");

$membre = $_GET['id'];

// liste deroulante film
$reponse = $bdd->query('SELECT `id_film`, `titre` FROM `tp_film` ORDER BY `titre`');
$listFilm = $reponse->fetchAll();

// update fiche perso membre
if(isset($_GET['validation']))
{
	$updateInfos = "UPDATE `tp_fiche_personne` SET `nom`='" . $_GET['nom'] . "', `prenom`='" . $_GET['prenom'] . "', `date_naissance`='" . $_GET['birth'] . "', `email`='" . $_GET['email'] . "', `adresse`='" . $_GET['adresse'] . "', `cpostal`='" . $_GET['cpostal'] . "', `ville`='" . $_GET['ville'] . "', `pays`='" . $_GET['pays']."' WHERE `tp_fiche_personne`.`id_perso`=". $_GET['id_fp'];
	$bdd->query($updateInfos);

	if($_GET['abo'] == "aucun") // suppression abonnement
	{
		$updateAbo = "UPDATE `tp_membre` SET `id_abo`= '' WHERE `tp_membre`.`id_membre`=" . $membre;
	}
	else
	{
		$updateAbo = "UPDATE `tp_membre` SET `id_abo`='" . $_GET['abo'] . "' WHERE `tp_membre`.`id_membre`=" . $membre;
	}
		$bdd->query($updateAbo);
}

// update historique membre film
if(isset($_GET['addFilmValid']) && $_GET['addFilm'] != "--")
{
	if(isset($_GET['addDate'])) // si une date est entree
	{
		$addFilm = $_GET['addFilm'];
		$addDate = $_GET['addDate'];

		$updateHistoriqueFilm = "INSERT INTO `tp_historique_membre` (`id_membre`, `id_film`, `date`) VALUES ('$membre', '$addFilm', '$addDate')";
		var_dump($updateHistoriqueFilm);
		$bdd->query($updateHistoriqueFilm);

	}
}

// update historique membre avis
if(isset($_GET['addAvisValid']))
{
	if($_GET['addAvis'] != "--" && !empty($_GET['avis']))
	{
		$updateHistoriqueAvis = "UPDATE `tp_historique_membre` SET `avis`='" . $_GET['avis'] . "' WHERE `tp_historique_membre`.`id_membre` = $membre AND `tp_historique_membre`.`id_film`=" . $_GET['addAvis'];
		$bdd->query($updateHistoriqueAvis);
	}
}

// historique film
$his = "SELECT `tp_film`.`id_film` AS idFilm, `tp_film`.`titre` AS titre, `tp_historique_membre`.`date` AS jour, `tp_historique_membre`.`avis` AS avis FROM `tp_membre` LEFT JOIN `tp_historique_membre` ON `tp_historique_membre`.`id_membre`=`tp_membre`.`id_membre` LEFT JOIN `tp_film` ON `tp_film`.`id_film`=`tp_historique_membre`.`id_film` WHERE `tp_membre`.`id_membre` = $membre ORDER BY jour DESC ";

// execution pour obtenir le nombre total de resultats avant limit
$resultats = $bdd->query($his);
$historique = $resultats->fetchAll();

$nbFilmHistorique = count($historique); // compte le nombre de resultats dans le tableau
//var_dump($nbFilmHistorique);

$limit = 10;

if(isset($_GET['page'])) // recupration de la page courante
{
	$currentPage = $_GET['page'];
}
else
{
	$currentPage = 1; //remet sur page 1 si aucune page definie
}

if(isset($_GET['limitHistoriqueValid']))
{
	$limit = $_GET['limit'];
	$his .= ' LIMIT '.(($currentPage - 1)*$limit).", $limit";
}
else
{
	$his .= ' LIMIT '.(($currentPage - 1)*$limit).", $limit";
}

$reponse = $bdd->query($his);
$historique = $reponse->fetchAll();

// menu deroulant abo
$reponse = $bdd->query('SELECT `nom`, `id_abo` FROM `tp_abonnement` ORDER BY `nom`');
$listAbo = $reponse->fetchAll();

// requete pour infos membre pré rempli
$sql = "SELECT `tp_membre`.`id_fiche_perso` AS idFichePerso, `tp_fiche_personne`.`nom` AS nom, `tp_fiche_personne`.`prenom` AS prenom, `tp_fiche_personne`.`date_naissance` AS birth, `tp_fiche_personne`.`email` AS email, `tp_fiche_personne`.`adresse` AS adresse, `tp_fiche_personne`.`cpostal` AS cpostal, `tp_fiche_personne`.`ville` AS ville, `tp_fiche_personne`.`pays` AS pays, `tp_membre`.`id_abo` AS abo FROM `tp_fiche_personne` LEFT JOIN `tp_membre` ON `tp_membre`.`id_fiche_perso`=`tp_fiche_personne`.`id_perso` WHERE `tp_membre`.`id_membre` = $membre";
$infos = $bdd->query($sql);
$infos = $infos->fetch();

$nbPages = ceil($nbFilmHistorique/$limit);
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

		<section class="bloc_left info">
			<header><h2>Infos Membre</h2></header>

			<form method="GET" name="ficheMember" action="ficheMember.php" >

				<label for="nom">Nom : </label>
				<input type="text" id="nom" name="nom" value="<?php echo $infos['nom']; ?>"/>

				<label for="prenom">Prénom : </label>
				<input type="text" id="prenom" name="prenom" value="<?php echo $infos['prenom']; ?>"/>

				<label for="birth">Date de naissance : </label>
				<input type="text" id="birth" name="birth" value="<?php echo $infos['birth']; ?>"/>

				<label for="email">Email : </label>
				<input type="text" id="email" name="email" value="<?php echo $infos['email']; ?>"/>

				<label for="adresse">Adresse : </label>
				<input type="text" id="adresse" name="adresse" value="<?php echo $infos['adresse']; ?>"/>

				<label for="cpostal">Code postal : </label>
				<input type="text" id="cpostal" name="cpostal" value="<?php echo $infos['cpostal']; ?>"/>

				<label for="ville">Ville : </label>
				<input type="text" id="ville" name="ville" value="<?php echo $infos['ville']; ?>"/>

				<label for="pays">Pays : </label>
				<input type="text" id="pays" name="pays" value="<?php echo $infos['pays']; ?>"/>

				<input type="hidden" name="id" value="<?php echo $membre; ?>"/>

				<input type="hidden" name="id_fp" value="<?php echo $infos['idFichePerso']; ?>"/>

				<label for="abo">Abonnement : </label>
				<select id="abo" name="abo">
					
					<?php
					foreach ($listAbo as $elem) 
					{
						if($elem['id_abo'] != $infos['abo'])
						{
							?>
							<option value="<?= $elem['id_abo']; ?>"><?php echo $elem['nom']; ?></option>
							<?php
						}
						else
						{
							?>
							<option value="<?php echo $elem['id_abo']; ?>" selected><?php echo $elem['nom']; ?></option>
							<?php
						}

					}
					?>
				</select>

				<input type="submit" id="valid" name="validation" value="Modifier" />
			</form>
		</section>

		<section id="add_film_historique">

			<form method="GET" name="addFilm" action="ficheMember.php">
				<input type="hidden" name="id" value="<?php echo $membre; ?>"/>

				<label for="addFilm">Ajouter un film : </label>
				<select id="addFilm" name="addFilm">
					<option>--</option>
					<?php
					foreach ($listFilm as $elem) 
					{
						?>
						<option value="<?php echo $elem['id_film']; ?>"><?php echo $elem['titre']; ?></option>
						<?php
					}
					?>
				</select>

				<label for="addDate">Date : </label>
				<input type="date" id="addDate" name="addDate" />

				<input type="submit" id="addFilmValid" name="addFilmValid" value="Ajouter" />
			</form>			
		</section>

		<section id="add_avis_film">
			<form method="GET" name="addAvis" action="ficheMember.php">
				<input type="hidden" name="id" value="<?php echo $membre; ?>"/>

				<label for="addAvis">Choisir un film : </label>
				<select id="addAvis" name="addAvis">
					<option>--</option>
					<?php
					foreach ($historique as $elem) 
					{
						?>
						<option value="<?php echo $elem['idFilm']; ?>"><?php echo $elem['titre']; ?></option>
						<?php
					}
					?>
				</select>

				<label for="avis">Avis : </label>
				<input type="text" id="avis" name="avis" />

				<input type="submit" id="addAvisValid" name="addAvisValid" value="Ajouter" />

			</form>
		</section>

		<section id="films_vus">
			<header><h2>Film(s) Vu(s)</h2></header>

			<form method="GET" name="limitHistorique" id="limitHistorique" action="ficheMember.php">
				<input type="hidden" name="id" value="<?php echo $membre; ?>"/>

				<label for="limit">Affichage :</label>
				<input type="number" id="limit" name="limit" value="10" min="0" />

				<input type="submit" id="limitHistoriqueValid" name="limitHistoriqueValid" value="Ok" />
			</form>

			<table id="historique">
				<tr>
					<th>Film</th>
					<th>Date</th>
					<th>Avis</th>
				</tr>
				<?php
				foreach ($historique as $elem) 
				{
					?>
					<tr>
						<td><?php echo $elem['titre']; ?></td>
						<td><?php echo $elem['jour']; ?></td>
						<td><?php echo $elem['avis']; ?></td>
					</tr>
					<?php
				}
				?>

			</table>

			<?php
			if($currentPage < $nbPages)
			{
				?>
				<a href='<?php echo "?"; echo "&amp;id=" . $membre; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($currentPage+1); ?>' class="next" title="lien vers la page suivante">Suivant &rarr;</a>
				<?php
			}
			if($currentPage > 1)
			{
				?>
				<a href='<?php echo "?"; echo "&amp;id=" . $membre; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($currentPage-1); ?>' class="previous" title="lien vers la page précédente">&larr; Précédent</a>
				<?php
			}
			?>

		</section>

		<?php include("includes/footer.php"); ?>
	</div>

</body>
</html>