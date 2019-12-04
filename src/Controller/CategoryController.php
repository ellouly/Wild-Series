<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/", name="admin_")
 */
class CategoryController extends AbstractController
{

    /**
     * Add a category to the database
     *
     * @Route("category", name="category")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this
            ->createForm(CategoryType::class, $category)
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('admin/admin.category.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
