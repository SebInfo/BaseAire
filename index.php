<?php
	require ("config.php");

	function chargerClasse($classname)
	{
		require $classname.'.class.php';
	}

	spl_autoload_register('chargerClasse');

	try 
	{
		$db = new PDO("mysql:host=$host;dbname=$base",$login,$motdepasse);
	} 
	catch (PDOException $e) 
	{
		echo "Erreur : ".$e->getMessage();
		die();
	}

	// On va créer un objet de type manager pour gérer les pilotes avec la BDD
	$manager = new PiloteManager($db);

	if (isset($_POST['effacer']))
	{
		unset($_POST['lister']);
		unset($resultat);
	}

	if (isset($_POST['lister']))
	{
		// On récupère la liste des pilotes et on met les résultats dans un tableau
		$listePilotes = $manager->getList();
		$resultat = "";
		// On affiche en parcourant le tableau
		foreach ($listePilotes as $unPilote) 
		{
			$resultat .= $unPilote."<br>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title> Accès et CRUD avec la table Pilote </title>
	<meta charset="utf-8">
</head>
<body>
	<form action="" method="post">
		<fieldset>
			<legend>Gestion des pilotes</legend>
			<ol>
				<li>
					<input type="submit" name="lister" value="Lister les pilotes">
				</li>
				<li>
					<label>Recherche d'un pilote</label>
					<input type="number" id="unPilote" name="numPilote">
					<input type="submit" name="unPilote" value="Afficher">
				</li>
			</ol>
		</fieldset>		
		<input type="submit" name="effacer" value="Effacer">
	</form>
	<?php 
	if (isset($resultat))
	{
		echo $resultat;
		unset($resultat);
	}
	?>
</body>
</html>