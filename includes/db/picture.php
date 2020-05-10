<?php

/**
 * Ajoute une image à la base en spécifiant son thème, son titre et sa description
 * @param PDO $pdo
 * @param string $title titre de l'image
 * @param string $file chemin vers l'image
 * @param string $description chemin vers l'image
 * @param int $themeId identifiant du thème auquel l'image fait référence
 */
function addPicture($pdo, $title, $file, $description, $themeId)
{
    executeUpdate($pdo, 
        "INSERT INTO Picture (`title`,`file`,`description`,`themeId`) VALUES (?,?,?,?)", array(
            $title, $file, $description, $themeId
        )
    );
}

/**
 * Supprime l'image ayant l'id correspondant de la base de données
 * @param PDO $pdo
 * @param int $id identifiant de l'image
 */
function removePicture($pdo, $id)
{
    executeUpdate($pdo, 
        "DELETE FROM Picture WHERE id = ?", array(
            $id
        )
    );
}

/**
 * Supprime toutes les images ayant pour theme le thème spécifié par identifiant
 * @param PDO $pdo
 * @param int $themeId identifiant du thème de l'image
 */
function removePictureByThemeId($pdo, $themeId)
{
    executeUpdate($pdo, 
        "DELETE FROM Picture WHERE themeId = ?", array(
            $themeId
        )
    );
}

/**
 * Supprime toutes les images postées par l'utilisateur ayant l'identifiant spécifié
 * @param PDO $pdo
 * @param int $userId identifiant de l'utilisateur
 */
function removePictureByUserId($pdo, $userId)
{
    executeUpdate($pdo, 
        "DELETE FROM Picture WHERE themeId IN (SELECT id FROM Theme WHERE userId = ?)", array(
            $userId
        )
    );
}

/**
 * Retourne l'image ayant pour identifiant celui spécifié
 * @param PDO $pdo
 * @param int $id identifiant de l'image recherchée
 */
function getPictureById($pdo, $id)
{
    $res = executeQuery($pdo, 
        "SELECT * FROM Picture P WHERE P.id = ?", array(
            $id
        )
    );
    $picture = $res->fetch();
    $res->closeCursor();
    return $picture;
}

/**
 * Retourne au maximum les 100 premières images du thème spécifié par son nom
 * @param PDO $pdo
 * @param string $themeName nom du thème
 * @param int $limit=100 limite d'images retournées
 */
function getPicturesByThemeName($pdo, $themeName, $limit=100)
{
    $res = executeQuery($pdo, 
        "SELECT P.*, T.name as themeName, T.userId, T.id as themeId
            FROM Picture P INNER JOIN Theme T ON P.themeId = T.id WHERE T.name = ? LIMIT $limit", array(
                $themeName
            )
    );
    $pictures = $res->fetchAll();
    $res->closeCursor();
    return $pictures;
}

/**
 * Retourne au maximum les 100 premières images du thème spécifié par son identifiant
 * @param PDO $pdo
 * @param int $themeId identifiant du thème
 * @param int $limit=100 limite d'images retournées
 */
function getPicturesByThemeId($pdo, $themeId, $limit=100)
{
    $res = executeQuery($pdo, 
        "SELECT * FROM Picture P WHERE P.themeId = ? LIMIT $limit", array(
            $themeId
        )
    );
    $pictures = $res->fetchAll();
    $res->closeCursor();
    return $pictures;
}

/**
 * Retourne au maximum les 100 premières images postées par l'utilisateur spécifié par son identifiant
 * @param PDO $pdo
 * @param int $userId identifiant de l'utilisateur
 * @param int $limit=100 limite d'images retournées
 */
function getPicturesByUserId($pdo, $userId, $limit=100)
{
    $res = executeQuery($pdo, 
        "SELECT P.* FROM Picture P INNER JOIN Theme T ON P.themeId = T.id WHERE T.userId = ? LIMIT $limit", array(
            $userId
        )
    );
    $pictures = $res->fetchAll();
    $res->closeCursor();
    return $pictures;
}

/**
 * Retourne au maximum les 100 premières images postées les plus populaires
 * @param PDO $pdo
 * @param int $limit=100 limite d'images retournées
 */
function getPopularPictures($pdo, $limit=100) {
    $res = executeQuery($pdo, 
        "SELECT * FROM Picture P LIMIT $limit"
    );
    $pictures = $res->fetchAll();
    $res->closeCursor();
    return $pictures;
}

/**
 * Retourne un tableau comprenant les chemins vers les images liées au thème spécifié par identifiant
 * @param PDO $pdo
 * @param int $themeId identifiant du thème
 */
function getPictureFilesByThemeId($pdo, $themeId)
{
    $files = array();
    $res = executeQuery($pdo, 
        "SELECT P.file FROM Picture P WHERE P.themeId = ?", array(
            $themeId
        )
    );

    while ($row = $res->fetch()) {
        array_push($files, $row['file']);
    }

    $res->closeCursor();
    return $files;
}

/**
 * Retourne un tableau comprenant les chemins vers les images postées par l'utilisateur spécifié par son identifiant
 * @param PDO $pdo
 * @param int $userId identifiant de l'utilisateur
 */
function getPictureFilesByUserId($pdo, $userId)
{
    $files = array();
    $res = executeQuery($pdo, 
        "SELECT P.file FROM Picture P INNER JOIN Theme T ON P.themeId = T.id WHERE T.userId = ?", array(
            $userId
        )
    );

    while ($row = $res->fetch()) {
        array_push($files, $row['file']);
    }

    $res->closeCursor();
    return $files;
}

?>