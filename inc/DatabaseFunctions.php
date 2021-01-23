<?php

function countComments(int $id)
{
    $sql = 'SELECT COUNT("id") AS totalComments FROM comments WHERE comments.post_id = :id';

    $statement = getDatabase()->prepare($sql);
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);
    $statement->closeCursor();

    return $result->totalComments;
}