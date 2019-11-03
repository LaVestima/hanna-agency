<?php

namespace App\Controller\Store;

use App\Controller\Infrastructure\BaseController;
use App\Entity\StoreOpinionVote;
use App\Repository\StoreOpinionRepository;
use App\Repository\StoreOpinionVoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StoreOpinionController extends BaseController
{
    private $storeOpinionRepository;
    private $storeOpinionVoteRepository;

    /**
     * @var StoreOpinionVote
     */
    private $vote;
    private $storeOpinion;

    public function __construct(
        StoreOpinionRepository $storeOpinionRepository,
        StoreOpinionVoteRepository $storeOpinionVoteRepository
    ) {
        $this->storeOpinionRepository = $storeOpinionRepository;
        $this->storeOpinionVoteRepository = $storeOpinionVoteRepository;
    }

    /**
     * @Route("/store_opinion_vote_up", name="store_opinion_vote_up", options={"expose"=true})
     */
    public function voteUp(Request $request)
    {
        if (!$this->getUser()) {
            return $this->json(null);
        }

        $this->preVote($request);

        if ($this->vote) {
            if ($this->vote->getIsPositive()) {
                $this->entityManager->remove($this->vote);
                $this->entityManager->flush();

                return $this->json(false);
            } else {
                $this->vote->setIsPositive(true);

                $this->entityManager->persist($this->vote);
            }
        } else {
            $this->vote = new StoreOpinionVote();
            $this->vote->setStoreOpinion($this->storeOpinion);
            $this->vote->setUser($this->getUser());
            $this->vote->setIsPositive(true);

            $this->entityManager->persist($this->vote);
        }

        $this->entityManager->flush();

        return $this->json(true);
    }

    /**
     * @Route("/store_opinion_vote_down", name="store_opinion_vote_down", options={"expose"=true})
     */
    public function voteDown(Request $request)
    {
        if (!$this->getUser()) {
            return $this->json(null);
        }

        $this->preVote($request);

        if ($this->vote) {
            if (!$this->vote->getIsPositive()) {
                $this->entityManager->remove($this->vote);
                $this->entityManager->flush();

                return $this->json(false);
            } else {
                $this->vote->setIsPositive(false);

                $this->entityManager->persist($this->vote);
            }
        } else {
            $this->vote = new StoreOpinionVote();
            $this->vote->setStoreOpinion($this->storeOpinion);
            $this->vote->setUser($this->getUser());
            $this->vote->setIsPositive(false);

            $this->entityManager->persist($this->vote);
        }

        $this->entityManager->flush();

        return $this->json(true);
    }

    private function preVote(Request $request)
    {
        $this->denyNonXhrs($request);

        $this->storeOpinion = $this->storeOpinionRepository->findOneBy([
            'identifier' => $request->query->get('identifier')
        ]);

        $this->vote = $this->storeOpinionVoteRepository->findOneBy([
            'storeOpinion' => $this->storeOpinion,
            'user' => $this->getUser()
        ]);
    }
}
