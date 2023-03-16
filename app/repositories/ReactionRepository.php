<?php

namespace Repositories;

use PDO;
use PDOException;
use Models\Reaction;
use Models\Opinion;

class ReactionRepository extends Repository
{
    private function reactionBuilder(array $input): array
    {
        $reactionEntityService = new ReactionEntityRepository();

        $output = array();
        foreach ($input as $row) {
            $id = $row["id"];
            $reactionEntity = $reactionEntityService->getById($row["reactionID"]);
            $count = $row["count"];

            $reaction = new Reaction();
            $reaction->setId($id);
            $reaction->setReactionEntity($reactionEntity);
            $reaction->setCount($count);

            array_push($output, $reaction);
        }
        return $output;
    }

    public function getAllForOpinion(Opinion $opinion): array
    {
        $stmt = $this->connection->prepare("SELECT id, reactionID, 'name', opinionID, count FROM Reactions WHERE opinionID = :opinionID");
        $opinionID = $opinion->getId();
        $stmt->bindParam(":opinionID", $opinionID, PDO::PARAM_INT);
        $stmt->execute();
        return $this->reactionBuilder($stmt->fetchAll());
    }

    public function createNewReaction(int $opinionID, int $reactionID): int
    {
        $sql = "INSERT INTO Reactions (reactionID, opinionID, count) VALUES (:reactionID, :opinionID, 1)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":reactionID", $reactionID, PDO::PARAM_INT);
        $stmt->bindParam(":opinionID", $opinionID, PDO::PARAM_INT);
        $stmt->execute();

        return $this->connection->lastInsertId();
    }

    public function increaseCountOfExistingOpinion(int $opinionID, int $reactionID)
    {
        $sql = "UPDATE Reactions SET count = count + 1 WHERE opinionID = :opinionID AND reactionID = :reactionID";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":reactionID", $reactionID, PDO::PARAM_INT);
        $stmt->bindParam(":opinionID", $opinionID, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Returns number of specific reactions for specific opinion.
    public function getReactionCount(int $opinionID, int $reactionID): int
    {
        $sql = "SELECT count(id) AS reactionCount FROM Reactions " .
            "WHERE opinionID = :opinionID AND reactionID = :reactionID";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":reactionID", $reactionID, PDO::PARAM_INT);
        $stmt->bindParam(":opinionID", $opinionID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()["reactionCount"];
    }

    public function getReactionsCount(int $opinionID): int
    {
        $sql = "SELECT IFNULL(SUM(Reactions.count), 0) as reactionCount FROM Opinions " .
            "LEFT JOIN Reactions on Reactions.opinionID = Opinions.id WHERE Opinions.id = :opinionID;";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":opinionID", $opinionID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()["reactionCount"];
    }

    public function getReactionEntryForOpinionAndReaction($opinionId, $reacitonEntityId): ?Reaction
    {
        $sql = "SELECT id, reactionID, opinionID, count FROM Reactions WHERE opinionID = :opinionID AND reactionID = :reactionID";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":opinionID", $opinionId, PDO::PARAM_INT);
        $stmt->bindParam(":reactionID", $reacitonEntityId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (empty($result)) {
            return null;
        }

        return $this->reactionBuilder($result)[0];
    }
}
