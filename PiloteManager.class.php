<?php

// Classe qui va gérer la manipulation des objets Pilotes et faire le lien avec la BDD
class PiloteManager implements iterator 
{
	// Le seul attribut de cette classe Manager
	private $_db;
	private $_listePilote;
	private $_pos;

	public function __construct($db)
	{
		$this->setDB($db); // on passe par un setter
		$this->_listePilote = $this->getList();
		$this->_pos = 0;
	}

	// Méthodes de la classe Itérator
	// Que l'on doit définir

	public function current()
	{
		if($this->valid())
			return $this->_listePilote[$this->_pos];
	}

	public function key()
	{
		return $this->_pos;
	}

	public function next()
	{
		++$this->_pos;
	}

	public function rewind()
	{
		$this->_pos = 0;
	}

	public function valid()
	{
		return isset($this->_listePilote[$this->_pos]);
	}
	// méthodes CRUD

	// Ajout d'un pilote
	public function add(Pilote $pilote)
	{
		$q = $this->_db->prepare('INSERT INTO PILOT(NumP, NameP, Address, Salary) VALUES(:NumP, :NameP, :Address, :Salary)');	
		$q->bindValue(':NumP', $pilote->getNumP());
		$q->bindValue(':NameP', $pilote->getNameP());
		$q->bindValue(':Address', $pilote->getAddress());
		$q->bindValue(':Salary', $pilote->getSalary());
		$q->execute();
	}

	// Suppression d'un pilote
	public function delete(Pilote $pilote)
	{
		$this->_db->exec('DELETE FROM PILOT WHERE NumP = '.$pilote->getNumP());
	}

	// Modification d'un pilote
	public function update(Pilote $pilote)
	{
		$q = $this->_db->prepare('UPDATE PILOT 
			SET NameP = :NameP,
			Address = :Address,
			Salary = :Salary
			WHERE NumP = :NumP'); // on ne modifie pas NumP ( identifiant )
		$q->bindValue(':NameP', $pilote->getNameP(), PDO::PARAM_STR);
		$q->bindValue(':Address', $pilote->getAddress(), PDO::PARAM_STR);
		$q->bindValue(':Salary', $pilote->getSalary(), PDO::PARAM_STR);
		$q->bindValue(':NumP', $pilote->getNumP(), PDO::PARAM_INT);
		$q->execute();
	}

	// Retourne un objet de type Pilote en fonction de l'identifiant passé en paramètre
	public function get($NumP)
	{
		$NumP = (int) $NumP;
		$q = $this->_db->query('SELECT NumP, NameP, Address, Salary FROM PILOT Where NumP = '.$NumP);
		$donnees = $q->fetch(PDO::FETCH_ASSOC);

		// On retourne un objet de type Pilote si on a récupéré les infos
		if (is_array($donnees))
		{
			return new Pilote($donnees);
		}
	}	

	// Autres méthodes outils 

	// Liste de pilotes
	public function getList()
	{
		// Retourne tous les pilotes 
		$pilotes = [];
		$q = $this->_db->query('SELECT NumP, NameP, Address, Salary FROM PILOT ORDER BY NameP');
		while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
		{
			$pilotes[] = new Pilote($donnees);
		}
		return $pilotes; // On retourne un tableau d'objets Pilote
	}

	// On fixe la BDD
	public function setDb(PDO $db)
	{
		$this->_db = $db;
	}
}
?>