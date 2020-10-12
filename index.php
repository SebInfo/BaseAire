<?php
	require_once ("config.php");
	require_once ("connection.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title> Accès et CRUD avec la table Pilote </title>
	<link rel="stylesheet" href="styles.css">
	<meta charset="utf-8">
</head>
<body>
	<?php
	if (!empty($_POST))
	{
		// Cas du bouton effacer
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

		/* Cas du bouton ajouter un pilote */
		if (isset($_POST['ajouterUnPilote']) AND strlen($_POST['NumP'])>3 AND !empty($_POST['NameP']))
		{
			$nouveauPilote = new Pilote(['NumP'=>$_POST['NumP'], 'NameP'=>$_POST['NameP'],'Address'=>$_POST['Address'],'Salary'=>$_POST['Salary']]);
			$manager->add($nouveauPilote);
		}

		/* Cas du bouton afficher un pilote depuis son numéro */
		if (isset($_POST['unPilote']) && $_POST['codePilote'] != 0)
		{
			$resultat=$manager->get($_POST['codePilote']);
		}

		// Iterator 

		// Cas du bouton suivant
		if (isset($_POST['next']))
		{
			$nbrDecallage = $_POST['nbr'];
			$i=0;
			// on decalle du nombre de pas necessaire
			while ($i<=$nbrDecallage AND $manager->valid($manager->current()))
			{ 
				$manager->next();
				$i++;
			}
			$resultat = $manager->current();
		}


		// Retour au début
		if (isset($_POST['rewind']))
		{
			$manager->rewind();
			$resultat = $manager->current();
		}
	}
	?>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	<fieldset>
			<legend>Gestion des pilotes</legend>
			<ol>
				<li>
					<input type="submit" name="lister" value="Lister les pilotes">
				</li>
				<li>
					<label for="codePilote">Numéro du pilote :</label>
					<input type="number" id="codePilote" name="codePilote">
					<input type="submit" name="unPilote" value="Afficher">
				</li>
				<li>
					<label for="numPilote">Numéro</label>
					<input type="number" id="numPilote" name="NumP">
					<label for="nomPilote">Nom du pilote</label>
					<input type="text" name="NameP" id="nomPilote" placeholder="Prénom et nom du pilote">
					<label for="adressePilote">Adresse du pilote</label>
					<input type="text" name="Address" placeholder="Adresse du pilote">
					<label for="salairePilote">Salaire du pilote</label>
					<input type="number" name="Salary" min="2000" max="30000">
					<input type="submit" name="ajouterUnPilote" value="Ajouter">
				</li>
				<li>
					<label>Nombre de pas</label>
					<input type="number" name="nbr">
					<input type="submit" name="next"  value="Suivant">
					<input type="submit" name="rewind"  value="Debut">
				</li>
			</ol>
		</fieldset>		
		<input type="submit" name="effacer" value="Effacer">
	</form>
	<?php 
	if (isset($resultat))
	{
		echo $resultat;
		echo "Clef du tableau".$manager->key();
		unset($resultat);
		unset($_POST);
	}
	?>
</body>
</html>