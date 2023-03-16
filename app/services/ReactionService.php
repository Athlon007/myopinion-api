<?php

namespace Services;

use Repositories\ReactionRepository;
use Models\Opinion;
use Models\Reaction;
use Models\ReactionEntity;

class ReactionService
{
    private ReactionRepository $repo;

    public function __construct()
    {
        $this->repo = new ReactionRepository();
    }

    // Returns the list of all reactions for provided opinion.
    public function getAllForOpinion(Opinion $opinion): array
    {
        return $this->repo->getAllForOpinion($opinion);
    }

    // Returns true, if opinion has 1 or more reactions.
    private function isReactionForOpinionPresent(int $opinionID, int $reactionID): bool
    {
        return $this->repo->getReactionCount($opinionID, $reactionID) > 0;
    }

    // Adds a new reaction to opinion.
    public function addReaction(Opinion $opinion, ReactionEntity $reactionEntity): Reaction
    {
        $opinionID = htmlspecialchars($opinion->getId());
        $reactionID = htmlspecialchars($reactionEntity->getId());

        if ($this->isReactionForOpinionPresent($opinionID, $reactionID)) {
            // If such reaction for specific opinion is already present,
            // instead of creating a new instance of the opinion,
            // increase the count of existing reaction instead.
            $this->repo->increaseCountOfExistingOpinion($opinionID, $reactionID);
        } else {
            // Else, simply add a new reaction.
            $this->repo->createNewReaction($opinionID, $reactionID);
        }

        // Return the reaction.
        return $this->repo->getReactionEntryForOpinionAndReaction($opinionID, $reactionID);
    }
}
