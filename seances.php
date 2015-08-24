<?php

include("includes/connectBDD.php");

// recherche des seances du jour
$currentYear = date("Y"); // retourne la date actuelle
$currentMonth = date("m");
$currentDay = date("d"); 

$sql = "SELECT `tp_film`.`titre` AS titre, `tp_grille_programme`.`id_film`, `tp_grille_programme`.`debut_seance` AS debutSeance FROM `tp_grille_programme` LEFT JOIN `tp_film` ON `tp_grille_programme`.`id_film`=`tp_film`.`id_film` WHERE YEAR(`tp_grille_programme`.`debut_seance`)=$currentYear AND MONTH(`tp_grille_programme`.`debut_seance`)=$currentMonth AND DAY(`tp_grille_programme`.`debut_seance`)=$currentDay";
$resultats = $bdd->query($sql);
$seanceJour = $resultats->fetchAll();

if(isset($_GET['searchSeance']) && !empty($_GET['jour']))
{
	$date = $_GET['jour'];
	$year = substr($date, 0, 4);
	$month = substr($date, 5, 2);
	$day = substr($date, 8, 2);

	$query = "SELECT `tp_film`.`titre` AS titre, `tp_grille_programme`.`id_film`, `tp_grille_programme`.`debut_seance` AS debutSeance FROM `tp_grille_programme` JOIN `tp_film` ON `tp_grille_programme`.`id_film`=`tp_film`.`id_film` WHERE YEAR(`tp_grille_programme`.`debut_seance`)=$year AND MONTH(`tp_grille_programme`.`debut_seance`)=$month AND DAY(`tp_grille_programme`.`debut_seance`)=$day";

	$resultats = $bdd->query($query);
	$avecFiltres = $resultats->fetchAll();
}

// execution pour obtenir le nombre total de resultats avant limit

//$resultats = $bdd->query($query);
//$pagination = $resultats->fetchAll();

//$nbFilm = count($pagination); // compte le nombre de resultats dans le tableau

//$limit = 10;

//if(isset($_GET['page'])) // recupration de la page courante
//{
//	$currentPage = $_GET['page'];
//}
//else
//{
	$currentPage = 1; //remet sur page 1 si aucune page definie
//}

//if(isset($_GET['limit']))
//{
//	$limit = $_GET['limit'];
//	$query .= ' LIMIT '.(($currentPage - 1)*$limit).", $limit";
//}
//else
//{
//	$query .= ' LIMIT '.(($currentPage - 1)*$limit).", $limit";
//}


//$nbPages = ceil($nbFilm/$limit);
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

		<section id="seance">
			<header><h2><a href="seances.php" title="lien vers la page séances">Rechercher une séance</a></h2></header>

			<form method="GET" name="searchSeance" action="seances.php">

				<label for="jour">Date : </label>
				<input type="date" id="jour" name="jour" />

				<label for="limit">Affichage :</label>
				<input type="number" id="limit" name="limit" value="15" min="0" />

				<input type="submit" name="searchSeance" value="Chercher" />
			</form>
		</section>
		<?php
		if(isset($_GET['searchSeance']))
		{
			?>
			<section class="resultSeances">
				<table>
					<tr>
						<th>Film</th>
						<th>Jour</th>
						<th>Heure</th>
					</tr>

					<?php
					if(!empty($avecFiltres))
					{
						foreach ($avecFiltres as $elem) 
						{
							$heure = substr($elem['debutSeance'], 11, 5);
							?>
							<tr>
								<td><?php echo $elem['titre']; ?></td>
								<td><?php echo $date ?></td>
								<td><?php echo $heure ?></td>
							</tr>
							<?php
						}
					}
					else
					{
						?><tr><td colspan="3"><?php echo "aucune séance trouvée :("; ?></td></tr><?php
					}
				?>
				</table>
			</section>
			<?php
		}
		else
		{
			?>
			<section class="resultSeances">
				<header><h2>Aujourd'hui</h2></header>
				<table>
					<tr>
						<th>Film</th>
						<th>Heure</th>
					</tr>

					<?php
					if(!empty($seanceJour))
					{
						foreach ($seanceJour as $elem) 
						{
							$heure = substr($elem['debutSeance'], 11, 5);
							?>
							<tr>
								<td><?php echo $elem['titre']; ?></td>
								<td><?php echo $heure; ?></td>
							</tr>
							<?php
						}
					}
					else
					{
						?><tr><td colspan="3"><?php echo "aucune séance aujourd'hui :("; ?></td></tr><?php
					}
				?>
				</table>
			</section>
			<?php
		}
		?>

		<?php include("includes/footer.php"); ?>
	</div>

</body>
</html>