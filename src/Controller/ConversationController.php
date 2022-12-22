<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\Conversation;
use App\Entity\Participant;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conversations", name="conversations.")
 */
class ConversationController extends AbstractController
{
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, ConversationRepository $conversationRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="newConversations", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(Request $request): Response
    {
        $otherUser = $request->get(key:'otherUser', default:0);
        $otherUser = $this->userRepository->find($otherUser);

        if (is_null($otherUser)) {
            throw new \Exception(message:"The user was not found");
        }

        if ($otherUser->getId() === $this->getUser()->getId()) {
            throw new \Exception(message:"Can't create a conversation with yourself");
        }

        $conversation = this->conversationRepository->findConversationByParticipants(
            $otherUser->getId(),
            $this->getUser()->getId()
        );

        dd($conversation);
        return $this->json();
    }
}
