<?php

include("includes/connectBDD.php");

// menu deroulant genre
$reponse = $bdd->query('SELECT `nom`, `id_genre` FROM `tp_genre` ORDER BY `nom`');
$listGenre = $reponse->fetchAll();

// menu deroulant distributeur
$reponse = $bdd->query('SELECT `nom`, `id_distrib` FROM `tp_distrib` ORDER BY `nom`');
$listDistrib = $reponse->fetchAll();

// requete de base, pour affichage si aucun filtre
$query = 'SELECT `id_film`, `titre`, `annee_prod` AS annee, `tp_genre`.`nom` AS genre FROM `tp_film` LEFT JOIN `tp_genre` ON `tp_film`.`id_genre` = `tp_genre`.`id_genre` LEFT JOIN `tp_distrib` ON `tp_distrib`.`id_distrib` = `tp_film`.`id_distrib` WHERE 1=1';

$placeHold = array();

if(!empty($_GET))
{
	if(isset($_GET['genre']) && $_GET['genre'] != "--")
	{
		$genre = $_GET['genre'];
		$query .= " AND `tp_genre`.`id_genre` = ? ";
		$placeHold[] = $genre;
	}
	if(isset($_GET['distrib']) && $_GET['distrib'] != "--")
	{
		$distrib = $_GET['distrib'];
		$query .= ' AND `tp_distrib`.`id_distrib` = ? ';
		$placeHold[] = $distrib;
	}
	if(isset($_GET['titre']) && $_GET['titre'] != "")
	{
		$titre = $_GET['titre'];
		$query .= ' AND `titre` LIKE ? ';
		$placeHold[] = "%$titre%";
	}
}

$query .= ' ORDER BY `titre`';

// execution pour obtenir le nombre total de resultats avant limit
$resultats = $bdd->prepare($query);
$resultats->execute($placeHold);
$pagination = $resultats->fetchAll();

$nbFilm = count($pagination); // compte le nombre de resultats dans le tableau

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

$nbPages = ceil($nbFilm/$limit);
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
			<header><h2>Recherche de film(s)</h2></header>

			<form method="GET" name="searchFilm" action="index.php" >
				<label for="titre">Titre : </label>
				<input type="text" id="titre" name="titre" />

				<label for="genre">Genre : </label>
				<select id="genre" name="genre">

					<option>--</option>
					<?php include("includes/connectBDD.php"); ?>

					<?php
					foreach ($listGenre as $elem)
					{
						?>

						<option <?php if(isset($_GET['genre']) && $elem['id_genre'] == $_GET['genre']) echo "selected"; ?> value="<?= $elem['id_genre']; ?>"><?php echo $elem['nom']; ?></option>
						<?php
					}
					?>
				</select>

				<label for="distrib">Distributeur : </label>
				<select id="distrib" name="distrib">

					<option>--</option>
					<?php 

					foreach ($listDistrib as $elem)
					{
						?>
						<option <?php if(isset($_GET['distrib']) && $elem['id_distrib'] == $_GET['distrib']) echo "selected"; ?> value="<?= $elem['id_distrib']; ?>"><?php echo $elem['nom']; ?></option>
						<?php
					}
					?>
				</select>

				<label for="limit">Affichage par page :</label>
				<input type="number" id="limit" name="limit" value="10" min="0" />

				<input type="submit" id="valid" name="Validation" value="Go !" />


			</form>
		</section>

		<section id="bloc_right">
			<header>Films</header>
			<table id="indexFilm">
				<tr>
					<th>Titre</th>
					<th>Annee</th>
					<th>Genre</th>
					<th>Infos</th>
				</tr>
				<?php

				if(!empty($avecFiltres))
				{
					foreach($avecFiltres as $elem)
					{
						?>
						<tr>
							<td><?php echo $elem['titre']; ?></td>
							<td>
								<?php 
								if($elem['annee'] == 0)
								{
									echo "inconnue";
								}
								else
								{
									echo $elem['annee']; ?>
									<?php
								}
								?>
							</td>
							<td>
								<?php 
								if($elem['genre'] == NULL)
								{
									echo "inconnu";
								}
								else
								{
									echo $elem['genre']; ?>
									<?php
								}
								?>
							</td>
							<td><a href="ficheFilm.php<?php echo "?"."idFilm=".$elem['id_film']?>">Voir +</a></td>
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
				<a href='<?php echo "?"; if(isset($genre)) echo "genre=" . $genre; if(isset($distrib)) echo "&amp;distrib=" . $distrib; if(isset($titre)) echo "&amp;titre=" .$titre; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($currentPage+1); ?>' class="next" title="lien vers la page suivante">Suivant &rarr;</a>
				<?php
			}
			if($currentPage > 1)
			{
				?>
				<a href='<?php echo "?"; if(isset($genre)) echo "genre=" . $genre; if(isset($distrib)) echo "&amp;distrib=" . $distrib; if(isset($titre)) echo "&amp;titre=" .$titre; if(isset($limit)) echo "&amp;limit=" .$limit; echo "&amp;page=" . ($currentPage-1); ?>' class="previous" title="lien vers la page précédente">&larr; Précédent</a>
				<?php
			}
			?>

		</section>

		<?php include("includes/footer.php"); ?>
	</div>

</body>
</html>