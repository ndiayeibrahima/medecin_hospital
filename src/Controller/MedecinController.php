<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Repository\MedecinRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedecinController extends AbstractController 
{
     /**
     * @Route("/medecin", name="medecin.show")
     */
    public function showMedecin(MedecinRepository $repo,SerializerInterface $serializer)
    {
        //Recuperer tous les medecins
        $medecins= $repo->findAll();
        // Tip : Inject SerializerInterface $serializer in the controller method
// and avoid these 3 lines of instanciation/configuration
//$encoders = [new JsonEncoder()]; // If no need for XmlEncoder
//$normalizers = [new DateTimeNormalizer(),new ObjectNormalizer()];
//$serializer = new Serializer($normalizers, $encoders);

// Serialize your object in Json
///$jsonObject = $serializer->serialize($medecins, 'json', [
   /// 'circular_reference_handler' => function ($object) {
   //     return $object->getId();
    //}
//], ['ignored_attributes' => ["dateNais"] ]);

// For instance, return a Response with encoded Json
////return new Response($jsonObject, 200, ['Content-Type' => 'application/json']);
       // $data=$this->get('serializer')->serialize($medecins,'json');
      //  $reponse = new Response($data);
     //   $reponse->headers->set('Content-Type','application/json');
       // return $reponse;
        return $this->render('medecin/index.html.twig', [
         //Envoie du var $medecins dans twig
          'medecins' => $medecins,
        ]);
        
    }
     /**
     * @Route("/medecin_edit_{id}", name="medecin.edit")
     */
    public function editService($id,Request $req,MedecinRepository $rep)
    {
        //recuperer le medecins par sont id
        $medecin =$rep->find($id);
        //creation formulaire avec var $medecin
        $form=$this->createForm(MedecinType::class,$medecin);
        //recuperation des donnees modifies
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            //Modification des donnees dans le db
            $em->flush();
            //Ajout msg alert de success
            $this->addFlash("success","Editer avec success");
            //Redirection
            return $this->redirectToRoute('medecin.show');

        }
    
        return $this->render('medecin/edit.html.twig', [
            'form' => $form->createView(),

        ]);
    }
     /**
     * @Route("/medecin_delete_{id}", name="medecin.delete")
     */
    public function deleteMedecin($id,Request $req,MedecinRepository $rep)
    {
        $medecin =$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($medecin);
        $em->flush();
        $this->addFlash("success","Supprimer avec success");
        return $this->redirectToRoute('medecin.show');

    }
    /**
     * @Route("/medecin_add", name="medecin.add")
     */
    public function addMedecin(Request $req,MedecinRepository $repo)
    {
        $medecin =new Medecin();
        $form=$this->createForm(MedecinType::class,$medecin);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $specialites=$medecin->getSpecialites();
            $lastMedecin=$repo->getLastMedecin();
            if($lastMedecin){
                $matricule=$lastMedecin[0]->getMatricule();
                $lm=substr($matricule,3);
                $id=$medecin->genereIdMatricule($lm);
            }else{
                $id="00001";
            }
            $spe=$medecin->genereSpecialite($specialites);
            $mat=$medecin->genereMatricule($spe,$id);
            $medecin->setMatricule($mat);
            $em->persist($medecin);
            $em->flush();
            $this->addFlash("success","Ajouter avec success");
            return $this->redirectToRoute('medecin.show');

        }
        return $this->render('medecin/add.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
