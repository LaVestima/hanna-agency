<?php

namespace App\Controller\Conversation;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\MessageReplyType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/conversation")
 */
class ConversationController extends BaseController
{
    private $conversationRepository;
    private $messageRepository;

    public function __construct(
        ConversationRepository $conversationRepository,
        MessageRepository $messageRepository
    ) {
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
    }

    /**
     * @Route("s", name="conversation_list")
     */
    public function list()
    {
        // TODO: check access

        // TODO: add condition for userTo
//        $conversations = $this->conversationRepository->findBy(['userFrom' => $this->getUser()]);
        $conversations = $this->conversationRepository->findAll();
        // TODO: sort messages by date

        return $this->render('Conversation/list.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    /**
     * @Route("/{identifier}", name="conversation_show")
     */
    public function show(Conversation $conversation, Request $request)
    {
        // TODO: check access

        $message = new Message();

        $messageReplyForm = $this->createForm(MessageReplyType::class, $message);
        $messageReplyForm->handleRequest($request);

        if ($messageReplyForm->isSubmitted() && $messageReplyForm->isValid()) {
            $message = $messageReplyForm->getData();

            $message->setConversation($conversation);
            $message->setIsFromInitiator($conversation->getUserFrom() === $this->getUser());

            $this->messageRepository->createEntity($message);
//            var_dump($message);
            return $this->redirect($request->getUri());
        }

        return $this->render('Conversation/show.html.twig', [
            'conversation' => $conversation,
            'messageReplyForm' => $messageReplyForm->createView(),
        ]);
    }
}