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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->json($note);
        }

        return $this->json('Invalid data', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="app_note_show", methods={"GET"})
     */
    public function show(Note $note, EntityManagerInterface $entityManager): Response
    {
        return $this->json($note);
    }

    /**
     * @Route("/{id}/edit", name="app_note_edit", methods={"PUT"})
     */
    public function edit(Request $request, Note $note, EntityManagerInterface $entityManager): Response
    {
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
    public function delete(Request $request, Note $note, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($note);
        $entityManager->flush();

        return $this->json('Note deleted', Response::HTTP_NO_CONTENT);
    }





}
