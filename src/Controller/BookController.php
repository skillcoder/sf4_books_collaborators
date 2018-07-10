<?php

namespace App\Controller;

use App\Entity\Book;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book", methods={"GET"})
     */
    public function index()
    {
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/book/add", name="add_book", methods={"GET", "POST"})
     */
    public function add(Request $request)
    {
        $book = new Book();
        $form = $this->createFormBuilder($book)
            ->add('name', TextType::class, array('attr' => array('required' => true, 'class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Add', 'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $book = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/book/delete/{id}", name="delete_book", methods={"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($book);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}
