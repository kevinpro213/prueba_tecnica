<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoType;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/producto')]
class ProductoController extends AbstractController
{
    #[Route('/', name:'producto_index')]
    public function index(ProductoRepository $repo): Response
    {
        return $this->render('producto/index.html.twig', [
            'productos' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name:'producto_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $producto = new Producto();
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($producto);
            $em->flush();
            return $this->redirectToRoute('producto_index');
        }

        return $this->render('producto/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', name:'producto_edit')]
    public function edit(Request $request, Producto $producto, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductoType::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('producto_index');
        }

        return $this->render('producto/edit.html.twig', ['form' => $form]);
    }

    #[Route('/{id}', name:'producto_delete', methods:['POST'])]
    public function delete(Request $request, Producto $producto, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->request->get('_token'))) {
            $em->remove($producto);
            $em->flush();
        }

        return $this->redirectToRoute('producto_index');
    }
}
