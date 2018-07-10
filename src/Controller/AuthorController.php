<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="author", methods={"GET"})
     */
    public function index()
    {
		$authors = $this->getDoctrine()->getRepository(Author::class)->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

	/**
     * @Route("/author/add", name="add_author", methods={"GET", "POST"})
     */
    public function add(Request $request)
    {
		$author = new Author();
		$form = $this->createFormBuilder($author)
			->add('name', TextType::class, array('attr' => array('required' => true, 'class' => 'form-control')))
			->add('save', SubmitType::class, array('label' => 'Add', 'attr' => array('class' => 'btn btn-primary mt-3')))
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isvalid()) {
			$author = $form->getData();
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($author);
			$entityManager->flush();
			
			return $this->redirectToRoute('author');
		}
		
		return $this->render('author/add.html.twig', [
            'form' => $form->createView(),
        ]);
	}

    /**
     * @Route("/author/delete/{id}", name="delete_author", methods={"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
		$entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($author);
        $entityManager->flush();

        $response = new Response();
		$response->send();
    }

    /**
     * @Route("/author/{author_id}/book/{book_id}", name="uncheck_author_book", methods={"DELETE"})
     */
    public function uncheck_book(Request $request, $author_id, $book_id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($author_id);
        $book = $this->getDoctrine()->getRepository(Book::class)->find($book_id);
		$author->removeBook($book);
        $entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($author);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/author/{author_id}/book/{book_id}", name="check_author_book", methods={"PUT"})
     */
    public function check_book(Request $request, $author_id, $book_id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($author_id);
        $book = $this->getDoctrine()->getRepository(Book::class)->find($book_id);
		$author->addBook($book);
        $entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($author);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }

	/**
     * @Route("/author/{id}", name="show_author", methods={"GET"})
     */
    public function show(Request $request, $id)
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        $books = $this->getDoctrine()->getRepository(Book::class)->findAll();
		$books_ids = array();
		foreach($author->getBooks() as $book) {
			$books_ids[$book->getId()] = $book->getName();
		}

		return $this->render('author/show.html.twig', [
			'author' => $author,
            'books' => $books,
			'book_ids' => $books_ids,
        ]);
    }
}
