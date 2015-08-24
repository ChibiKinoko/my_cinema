<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="css/style.css" />

	<title>My_Cinema</title>
</head>
<body>

	<?php include("includes/header.php"); ?>

	<section id="bloc_center">
		<h2>Recherche de film(s)</h2>
		
		<form method="POST" name="searchFilm" action="resultFilms.php" >
			<label for="titre">Titre : </label>
			<input type="text" id="titre" name="titre" />

			<label for="genre">Genre : </label>
			<select id="genre" name="genre">
				<option selected="selected">--</option>
				<option>dramatic comedy</option>
				<option>science fiction</option>
				<option>drama</option>
				<option>documentary</option>
				<option>animation</option>
				<option>comedy</option>
				<option>fantasy</option>
				<option>action</option>
				<option>thriller</option>
				<option>adventure</option>
				<option>various</option>
				<option>historical</option>
				<option>western</option>
				<option>romance</option>
				<option>music</option>
				<option>musical</option>
				<option>horror</option>
				<option>war</option>
				<option>spying</option>
				<option>historical epic</option>
				<option>biography</option>
				<option>experimental</option>
				<option>short film</option>
				<option>erotic</option>
				<option>karate</option>
				<option>program</option>
				<option>family</option>
			</select>

			<label for="distrib">Distributeur : </label>
			<select id="distrib" name="distrib"></select>
				<!-- <option selected="selected">--</option>
				<option>les films du losange</option>
				<option>mk2 diffusion</option>
				<option>rezo films</option>
				<option>studio images 5</option>
				<option>eiffel productions</option>
				<option>cerito films</option>
				<option>france 3 cin&amp;atilde;&amp;copy;ma</option>
				<option>tartan films</option>
				<option>monarchy enterprises b.v.</option>
				<option>advanced</option>
				<option>the vista organisation group</option>
				<option>les films balenciaga</option>
				<option>art-light productions</option>
				<option>telinor</option>
('15','bandidos films','0371213306'),
('16','parco co, ltd','0527672375'),
('17','transfilm','0448268106'),
('18','dmvb films','0215373495'),
('19','davis-panzer productions','0535418285'),
('20','idea productions','0313187914'),
('21','vision international','0424465993'),
('22','films a2','0243936488'),
('23','dog eat dog productions','0216868187'),
('24','the carousel pictures company','0578341887'),
('25','interlight','0387722115'),
('26','deluxe productions','0554695372'),
('27','lolistar','0190887722'),
('28','united international pictures (uip)','0511650332'),
('29','verve pictures','0343139508'),
('30','entertainment film distributors ltd','0136355344'),
('31','eros film ltd.','0117308564'),
('32','dogwoof pictures','0397752175'),
('33','guerilla films ltd.','0126939575'),
('34','ica films','0109521351'),
('35','sony pictures','0117667343'),
('36','20th century fox','0450273867'),
('37','contender entertainment','0524345397'),
('38','momentum pictures','0333654411'),
('39','adlabs films','0255521648'),
('40','artificial eye','0135718252'),
('41','the works','0482435790'),
('42','peccadillo pictures','0142367829'),
('43','metrodome films','0184888460'),
('44','icon film distribution uk','0285454608'),
('45','bfi distribution','0278292428'),
('46','optimum releasing','0210219708'),
('47','miracle comms','0344573119'),
('48','revolver entertainment','0489407327'),
('49','soda pictures','0370402534'),
('50','national film theater','0304269716'),
('51','revelation films','0138006046'),
('52','ace films','0588636787'),
('53','path&amp;atilde;&amp;copy;','0530470806'),
('54','v&amp;atilde;&amp;copy;rtigo films','0588492202'),
('55','park circus','0517505479'),
('56','buena vista international','0147740888'),
('57','yeah yeah yeah ltd.','0579557407'),
('58','swipe films','0438820671'),
('59','universal','0118023807'),
('60','paramount pictures','0586773629'),
('61','warner bros u.k.','0379233026'),
('62','showbox media group','0194205118'),
('63','united pictures international uk','0398187864'),
('64','paramount pictures uk','0127074098'),
('65','buena vista international uk','0391010431'),
('66','universal international pictures','0142892701'),
('67','punk distribution','0387909616'),
('68','axiom films','0115965167'),
('69','eros international ltd.','0101316595'),
('70','sony pictures uk','0576925615'),
('71','lions gate films home entertainment','0486208894'),
('72','studio 18','0311132263'),
('73','british path&amp;atilde;&amp;copy;','0523400093'),
('74','maiden voyage pictures','0355023753'),
('75','warner music entertainment','0433185208'),
('76','utv motion pictures','0334826167'),
('77','lionsgate uk','0276522134'),
('78','yume pictures','0309873282'),
('79','delanic films','0412565948'),
('80','vertigo films','0464391054'),
('81','path&amp;atilde;&amp;copy; distribution ltd.','0267718795'),
('82','spark pictures','0114385541'),
('83','slingshot','0146903442'),
('84','diffusion pictures','0448074755'),
('85','transmedia pictures','0435146575'),
('86','cinefilm','0139243944'),
('87','odeon sky filmworks','0289544451'),
('88','liberation entertainment','0537490904'),
('89','lagoon entertainment','0462275200'),
('90','halcyon pictures','0394022987'); -->

		<?php include("includes/connectBDD.php");

		$reponse = $bdd->query('SELECT nom FROM tp_distrib');

		while($donnees = $reponse->fetch())
		{

		?>

		<option><?php echo $donnees['nom']; ?></option>

		<?php 
		}

		?>

		</form>
	</section>

	<?php include("includes/footer.php"); ?>

</body>
</html>