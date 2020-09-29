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

	/* Cas du bouton lister */ 
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

	/* Cas du bouton afficher un pilote depuis son numéro */
	if (isset($_POST['unPilote']) && $_POST['codePilote'] != 0)
	{
		// On affiche l'objet pilote
		$resultat=$manager->get($_POST['codePilote']);
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
					<label for="codePilote">Numéro du pilote :
						<input type="number" id="codePilote" name="codePilote">
					</label>
					<input type="submit" name="unPilote" value="Afficher">
				</li>
				<li>
					<label for="numPilote">Numéro
						<input type="number" id="numPilote" name="numPilote">
					</label>
					<label for="nomPilote">Nom du pilote (nom et prénom)
						<input type="text" name="nomPilote" placeholder="Prénom et nom du pilote">
					</label>
					<label for="adressePilote">Adresse du pilote
						<input type="text" name="adressePilote" placeholder="Adresse du pilote">
					</label>
					<label for="salairePilote">Salaire du pilote
						<input type="number" name="salairePilote" min="2000" max="30000">
					</label>
					<input type="submit" name="unPilote" value="Ajouter">
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