<?php

/**
 * Vérifie si un email n'est pas utilisé dans la relation user
 * @param string $email
 * @param PDO $pdo
 */
function checkUserAvailability($email, $pdo)
{
	$res = executeQuery($pdo, 
		"SELECT U.email FROM User U WHERE U.email = ?", array(
			$email
		)
	);
	$available = $res->rowCount() == 0;
	$res->closeCursor();

	return $available;
}

/**
 * Enregistre un nouvel utilisateur dans la table User
 * @param string $email email du nouvel utilisateur, comprenant nécessairement un @
 * @param string $hashPwd mot de passe du nouvel utilisateur, encrypté
 * @param string $pseudo pseudonyme du nouvel utilisateur
 * @param PDO $pdo
 */
function register($email, $hashPwd, $pseudo, $pdo)
{
	executeUpdate($pdo, 
		"INSERT INTO User (`email`,`pwd`,`pseudo`,`rights`) VALUES (?,?,?,'USER')", array(
			$email, $hashPwd, $pseudo
		)
	);
}

/**
 * Supprime l'utilisateur de la base de données spécifié par son identifiant
 * @param PDO $pdo
 * @param int $id identifiant de l'utilisateur
 */
function removeUser($pdo, $id)
{
    executeUpdate($pdo, 
        "DELETE FROM User WHERE id = ?", array(
            $id
        )
    );
}

/**
 * Supprime l'utilisateur de la base de données spécifié par son identifiant
 * @param PDO $pdo
 * @param int $id identifiant de l'utilisateur
 */
function changeUserPseudo($pdo, $id, $pseudo)
{
    executeUpdate($pdo, 
        "UPDATE User SET pseudo = ? WHERE id = ?", array(
            $pseudo, $id
        )
    );
}

/**
 * Change l'image de profil de l'utilisateur
 * @param PDO $pdo
 * @param int $id identifiant de l'utilisateur
 * @param string $image chemin vers la nouvelle image
 */
function changeUserImage($pdo, $id, $image)
{
    executeUpdate($pdo, 
        "UPDATE User SET image = ? WHERE id = ?", array(
            $image, $id
        )
    );
}

/**
 * Change le mot de passe de l'utilisateur
 * @param PDO $pdo
 * @param int $id identifiant de l'utilisateur
 * @param string $hashPwd nouveau mot de passe encrypté
 */
function changeUserPassword($pdo, $id, $hashPwd)
{
    executeUpdate($pdo, 
        "UPDATE User SET pwd = ? WHERE id = ?", array(
            $hashPwd, $id
        )
    );
}

/**
 * Vérifie si l'utilisateur existe avec le mail spécifié et un mot de passe et renvoie ses informations s'il existe, null sinon
 * @param string $email email de l'utilisateur, comprenant nécessairement un @
 * @param string $hashPwd mot de passe encrypté de l'utilisateur
 * @param PDO $pdo
 */
function getUser($email, $hashPwd, $pdo)
{
	$res = executeQuery($pdo, 
		"SELECT * FROM User U WHERE U.email = ? AND U.pwd = ?", array(
			$email, $hashPwd
		)
	);
	$user = $res->fetch();
	$res->closeCursor();
	return $user;
}

/**
 * Vérifie si l'utilisateur existe avec un identifiant et renvoie ses informations s'il existe, null sinon
 * @param PDO $pdo
 * @param int $id identifiant de l'utilisateur
 */
function getUserById($pdo, $id)
{
	$res = executeQuery($pdo, 
		"SELECT * FROM User U WHERE U.id = ?", array(
			$id
		)
	);
    $user = $res->fetch();
    $res->closeCursor();
    return $user;
}

/**
 * Renvoie le nombre d'utilisateurs inscrits dans la base de données
 * @param PDO $pdo
 */
function getUserCount($pdo)
{
	$res = executeQuery($pdo, "SELECT count(*) FROM User");
	$nb = $res->fetch();
	$res->closeCursor();
	return $nb["count(*)"];
}

/**
 * Renvoie le nombre de photos postées sur la base
 * @param PDO $pdo
 */
function getPicturesCount($pdo)
{
	$res = executeQuery($pdo, "SELECT count(*) FROM Picture");
	$nb = $res->fetch();
	$res->closeCursor();
	return $nb["count(*)"];
}

/**
 * Renvoie le nombre total de photos postées par l'utilisateur par identifiant
 * @param PDO $pdo
 * @param int $userId identifiant de l'utilisateur
 */
function totalUserPicturesFromId($pdo, $userId){
	$res = executeQuery($pdo, "SELECT count(*) FROM User U JOIN Theme T ON U.id=T.userId JOIN Picture P ON T.id=P.themeId WHERE U.id='$userId'");
	$nb = $res->fetch();
	$res->closeCursor();
	return $nb["count(*)"];
}

?>
