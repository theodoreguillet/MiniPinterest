<?php

/**
 * Vérifie si un nom de thème est disponible pour l'utilisateur spécifié et renvoie un booléen
 * @param PDO $pdo
 * @param string $theme nom du thème
 * @param int $userId identifiant de l'utilisateur
 */
function checkThemeAvailability($pdo, $theme, $userId)
{
    $res = executeQuery($pdo, 
        "SELECT * FROM Theme T WHERE T.name = ? and T.userId = ?", array(
            $theme, $userId
        )
    );
    $available = ($res->rowCount() == 0);
	$res->closeCursor();

	return $available;
}

/**
 * Ajoute un thème à l'utilisateur spécifié
 * @param PDO $pdo
 * @param string $name nom du thème
 * @param int $userId identifiant de l'utilisateur
 */
function addTheme($pdo, $name, $userId)
{
    executeUpdate($pdo, 
        "INSERT INTO Theme (`name`,`userId`) VALUES (?,?)", array(
            $name, $userId
        )
    );
}

/**
 * Change le nom du thème par identifiant
 * @param PDO $pdo
 * @param int $id identifiant du thème
 * @param string $name nom du thème
 */
function changeThemeName($pdo, $id, $name)
{
    executeUpdate($pdo, 
        "UPDATE Theme SET name = ? WHERE id = ?", array(
            $name, $id
        )
    );
}

/**
 * Supprime un thème par identifiant
 * @param PDO $pdo
 * @param int $id identifiant du thème
 */
function removeTheme($pdo, $id)
{
    executeUpdate($pdo, 
        "DELETE FROM Theme WHERE id = ?", array(
            $id
        )
    );
}

/**
 * Supprime un thème par identifiant de l'utilisateur
 * @param PDO $pdo
 * @param int $id identifiant de l'utilisateur
 */
function removeThemeByUserId($pdo, $userId)
{
    executeUpdate($pdo, 
        "DELETE FROM Theme WHERE userId = ?", array(
            $userId
        )
    );
}

/**
 * Renvoie un nom de thème par identifiant
 * @param PDO $pdo
 * @param int $id identifiant du thème
 */
function getThemeById($pdo, $id)
{
    $res = executeQuery($pdo, 
        "SELECT * FROM Theme T WHERE T.id = ?", array(
            $id
        )
    );
    $theme = $res->fetch();
    $res->closeCursor();
    return $theme;
}

/**
 * Renvoie un tableau des noms des thèmes les plus populaires. Le tableau renvoyé a une taille maximale égale à 100.
 * @param PDO $pdo
 * @param int $limit=100 limite d'images retournées
 */
function getPopularThemes($pdo, $limit=100)
{
    $themes = array();
    $res = executeQuery($pdo, 
        "SELECT T.name, count(*) as count FROM Theme T GROUP BY T.name ORDER BY count DESC, T.name LIMIT $limit"
    );

    while ($row = $res->fetch()) {
        array_push($themes, $row["name"]);
    }

    $res->closeCursor();

    return $themes;
}

/**
 * Renvoie un tableau des 100 noms des thèmes créés par l'utilisateur spécifié par son identifiant. Le tableau renvoyé a une taille maximale égale à 100.
 * @param PDO $pdo
 * @param int $userId identifiant de l'utilisateur
 * @param int $limit=100 limite d'images retournées
 */
function getUserThemes($pdo, $userId, $limit=100)
{
    $res = executeQuery($pdo, 
        "SELECT U.id as userId, U.email as userEmail, U.pseudo as userName, T.id as themeId, T.name as themeName 
            FROM User U INNER JOIN Theme T ON U.id = T.userId WHERE U.id = ? ORDER BY T.id LIMIT $limit", array(
                $userId
            )
    );
    $themes = $res->fetchAll();
    $res->closeCursor();
    return $themes;
}

/**
 * Renvoie un tableau des informations concernant le theme recherché par son nom et l'id de l'utilisateur qui l'a créé
 * @param PDO $pdo
 * @param int $userId identifiant de l'utilisateur
 * @param string $themeName nom du thème recherché
 */
function findUserThemeByName($pdo, $userId, $themeName)
{
    $res = executeQuery($pdo, 
        "SELECT * FROM Theme T WHERE T.userId = ? and T.name = ?", array(
            $userId, $themeName
        )
    );
    $theme = $res->fetch();
    $res->closeCursor();
    return $theme;
}

?>
