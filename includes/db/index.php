<?php

define("DB_HOST", "127.0.0.1");
define("DB_USER", "p1808309");
define("DB_PWD", "522672");
define("DB_NAME", "p1808309");
define("DB_DEBUG", true);

/*Cette fonction prend en entrée l'identifiant de la machine hôte de la base de données, les identifiants (login, mot de passe) d'un utilisateur autorisé 
sur la base de données et renvoie une connexion active sur cette base de donnée. Sinon, un message d'erreur est affiché.*/
/**
 * Se connecte à la BDD
 * @return PDO
 */
function getConnection()
{
	try
	{
		return new PDO(
			'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', 
			DB_USER, 
			DB_PWD, 
			DB_DEBUG ? array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION) : null
		);
	}
	catch (Exception $e)
	{
			die('Error : ' . $e->getMessage());
	}
}


/**
 * Prépare et exécute une requête
 * @param PDO $pdo
 * @param string query La requête à préparer
 * @param array|null $params La liste des paramètres de la requête
 * @return PDOStatement|false Le résultat de la requête, false en cas d'erreur
 * @throws PDOException En cas d'erreur si l'option PDO::ERRMODE_EXCEPTION est activée
 */
function executeQuery($pdo, $query, $params = null)
{
	if(empty($params)) {
		$req = $pdo->query($query);
		return $req;
	}

	$req = $pdo->prepare($query);
	if(!$req) {
		return false;
	}
	$executed = $req->execute($params);
	if(!$executed) {
		return false;
	}

	return $req;
}

/**
 * Prépare et exécute une requête sans retour
 * @param PDO $pdo
 * @param string query La requête à préparer
 * @param array|null $params La liste des paramètres de la requête
 * @throws PDOException En cas d'erreur si l'option PDO::ERRMODE_EXCEPTION est activée
 */
function executeUpdate($pdo, $query, $params = null)
{
	$req = executeQuery($pdo, $query, $params);
	$req->closeCursor();
}

/**
 * Ferme la connexion à la BDD
 * @param PDO $pdo
 */
function closeConnexion($pdo)
{
	//
}
?>