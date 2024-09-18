<?php

namespace App\Controller;

use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/note")
 */
class NoteController extends AbstractController
{

    /**
     * @Route("/", name="app_note_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $notes = $entityManager->getRepository(Note::class)->findAll();

        return $this->json($notes);
    }

    /**
     * @Route("/new", name="app_note_new", methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json('Invalid JSON', Response::HTTP_BAD_REQUEST);
        }

        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->submit($data);  // Submit the data to the form manually

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();

            $jsonData = $serializer->serialize($note, 'json');
            return new Response($jsonData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
        }

        return $this->json('Invalid data', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="app_note_show", methods={"GET"})
     */
    public function show(int $id, NoteRepository $noteRepository): Response
    {
        $note = $noteRepository->find($id);

        if (!$note) {
            return $this->json(['error' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($note);
    }

    /**
     * @Route("/{id}/edit", name="app_note_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id, NoteRepository $noteRepository, EntityManagerInterface $entityManager): Response
    {
        $note = $noteRepository->find($id);

        if (!$note) {
            return $this->json(['error' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->json($note);
        }

        return $this->json('Invalid data', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="app_note_delete", methods={"DELETE"})
     */
    public function delete(int $id, NoteRepository $noteRepository, EntityManagerInterface $entityManager): Response
    {
        $note = $noteRepository->find($id);

        if (!$note) {
            return $this->json(['error' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($note);
        $entityManager->flush();

        return $this->json('Note deleted', Response::HTTP_NO_CONTENT);
    }
}
