<?php
// Classe qui gère la fabrication d'un objet Pilote
// Et gère les caractéristiques de cet objet (attributs & méthodes)
// Un classe = un rôle

class Pilote
{
	// Atrributs d'un pilote
	private $_NumP;
	private $_NameP;
	private $_Address;
	private $_Salary;

	// Le constructeur
	public function __construct(array $donnees)
	{
		// Le constructeur appelle la méthode hydrate
		// Celle ci sera utile pour la construction des objets 
		$this->hydrate($donnees);
	}


	public function hydrate(array $donnees)
	{
		// L'hydratation se fait toujours via les mutateurs
		foreach ($donnees as $key => $value) {
			$method = 'set'.$key;
			if (method_exists($this, $method))
			{
				$this->$method($value);
			}
			else
			{
				trigger_error('Je trouve pas la méthode !', E_USER_WARNING);
			}
		}
	}

	// Méthode magique afin d'afficher un objet Pilote
	public function __toString()
	{
		return "Pilote ".$this->getNameP()." qui habite ".$this->getAddress()." et dont le salaire est ".$this->getSalary();
	}

	// Les getters
	public function getNumP()
	{
		return $this->_NumP;
	}
	public function getNameP()
	{
		return $this->_NameP;
	}
	public function getAddress()
	{
		return $this->_Address;
	}
	public function getSalary()
	{
		return $this->_Salary;
	}

	// Les setters ou mutateurs avec éventuellement des restrictions
	public function setNumP($NumP)
	{
		$NumP = (int) $NumP;
		// Si c'est pas un entier la convertion donne 0.
		// On suppose que l'Id d'un pilote ne peut pas être 0
		if ($NumP > 0)
		{
			$this->_NumP = $NumP;
		}
	}

	public function setNameP($NameP)
	{
		if (is_string($NameP))
		{
			$this->_NameP = $NameP;
		}
	}

	public function setAddress($Address)
	{
		if (is_string($Address))
		{
			$this->_Address = $Address;
		}
	}

	public function setSalary($Salary)
	{
		$Salary = (float) $Salary;
		if ($Salary >=1 && $Salary < 50000)
		{
			$this->_Salary = $Salary;
		}
	}
}