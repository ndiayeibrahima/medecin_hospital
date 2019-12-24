<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin.service.show")
     */
    public function showService(ServiceRepository $repo)
    {
        $service= $repo->findAll();
        return $this->render('admin/index.html.twig', [
            'services' => $service,
        ]);
    }
    /**
     * @Route("/admin_add", name="admin.service.add")
     */
    public function addService(Request $req)
    {
        $service =new Service();
        $form=$this->createForm(ServiceType::class,$service);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();
            $this->addFlash("success","Ajouter avec success");
            return $this->redirectToRoute('admin.service.show');

        }
    
        return $this->render('admin/add.html.twig', [
            'form' => $form->createView(),

        ]);
    }
    /**
     * @Route("/admin_edit_{id}", name="admin.service.edit")
     */
    public function editService($id,Request $req,ServiceRepository $rep)
    {
        $service =$rep->find($id);
        $form=$this->createForm(ServiceType::class,$service);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash("success","Editer avec success");
            return $this->redirectToRoute('admin.service.show');

        }
    
        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),

        ]);
    }
     /**
     * @Route("/admin_delete_{id}", name="admin.service.delete")
     */
    public function deleteService($id,Request $req,ServiceRepository $rep)
    {
        $service =$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($service);
        $em->flush();
        $this->addFlash("success","Supprimer avec success");
        return $this->redirectToRoute('admin.service.show');

    }
}
