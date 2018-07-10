<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CollaboratorsController extends Controller
{
    /**
     * @Route("/collaborators", name="collaborators")
     */
    public function index()
    {
		$conn = $this->getDoctrine()->getManager()->getConnection();

		$sql = '
		SELECT b.name, COUNT(ab.author_id) AS cnt
		FROM author_book ab
		LEFT JOIN book b ON b.id=ab.book_id
		GROUP BY ab.book_id HAVING cnt = 3 
		';
		$stmt = $conn->prepare($sql);
	    $stmt->execute();
		$list = $stmt->fetchAll();
        return $this->render('collaborators/index.html.twig', [
            'list' => $list,
			'sql' => $sql
        ]);
    }
}
