<?php

namespace App\Controller;

// use Amp\Http\Client\Request;

use App\Entity\Currentapply;
use App\Entity\Voluntaryapply;
use App\Form\CurrentapplyType;
use App\Form\VoluntaryapplyType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    // #[Route('/currentapply', name: "", methods: ['POST'])]
    /**
     * @Route("/currentapply", name="currentapply")
     */
    public function generate_pdf(Request $request, ManagerRegistry $entityManager)
    {


        $currentapply = new Currentapply();
        $form = $this->createForm(CurrentapplyType::class, $currentapply);
        $form->handleRequest($request);

        if (isset($_POST['send'])) {


            $currentapply->setNom($_POST['Name']);
            $currentapply->setPrenom($_POST['prenom']);
            $currentapply->setDate(new \DateTime());


            //************Upload image ************* */
            $file = $currentapply->getFile();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('Upload_dir'), $fileName);
            $currentapply->setFile($fileName);

            //********Set DATA TO MySQL***** */
            $em = $entityManager->getManager();
            $em->persist($currentapply);
            $em->flush();

            $options = new Options();
            $options->set('defaultFont', 'Roboto');
            $dompdf = new Dompdf($options);

            $html =  $this->render('confirmation.html.twig', [
                'id' => $currentapply->getId(),
                'form' => $form->createView(),
                'nom' => $currentapply->getNom(),
                'prenom' => $currentapply->getPrenom(),
                'img' => $fileName

            ]);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("testpdf.pdf", [
                "Attachment" => true
            ]);

            return $this->render('confirmation.html.twig', [
                'id' => $currentapply->getId(),
                'form' => $form->createView(),
                'nom' => $currentapply->getNom(),
                'prenom' => $currentapply->getPrenom(),
                'img' => $fileName

            ]);
        } else
            return $this->render('currentapply.html.twig', [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @Route("confirmation", name="confirmation")
     */
    public function Validate(Request $request, ManagerRegistry $entityManager): Response
    {



        $currentapply = new Currentapply();
        $form = $this->createForm(CurrentapplyType::class, $currentapply);
        $form->handleRequest($request);

        if (isset($_POST['send'])) {


            $options = new Options();
            $options->set('defaultFont', 'Roboto');
            $dompdf = new Dompdf($options);

            $html = file_get_contents('../templates/confirmation.html.twig');

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("testpdf.pdf", [
                "Attachment" => true
            ]);


            return $this->render('currentapply.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }


    /**
     * @Route("voluntaryapply", name="voluntaryapply")
     */
    public function voluntaryapply(Request $request, ManagerRegistry $entityManager): Response
    {

        $voluntaryapply = new Voluntaryapply();
        $form = $this->createForm(VoluntaryapplyType::class, $voluntaryapply);
        $form->handleRequest($request);

        if (isset($_POST['send'])) {

            $voluntaryapply->setNom($_POST['nom']);
            $voluntaryapply->setPrenom($_POST['prenom']);
            $voluntaryapply->setDate(new \DateTime());

            //************Upload image ************* */
            $file = $voluntaryapply->getFile();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('Upload_dir'), $fileName);
            $voluntaryapply->setFile($fileName);

            //********Set DATA TO MySQL***** */
            $em = $entityManager->getManager();
            $em->persist($voluntaryapply);
            $em->flush();

            $options = new Options();
            $options->set('defaultFont', 'Roboto');
            $dompdf = new Dompdf($options);
            $html =  $this->render('confirmation.html.twig', [
                'id' => $voluntaryapply->getId(),
                'form' => $form->createView(),
                'nom' => $voluntaryapply->getNom(),
                'prenom' => $voluntaryapply->getPrenom(),
                'img' => $fileName

            ]);


            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("testpdf.pdf", [
                "Attachment" => true
            ]);

            return $this->render('confirmation.html.twig', [
                'id' => $voluntaryapply->getId(),
                'form' => $form->createView(),
                'nom' => $voluntaryapply->getNom(),
                'prenom' => $voluntaryapply->getPrenom(),
                'img' => $fileName

            ]);
        } else
            return $this->render('voluntaryapply.html.twig', ['form' => $form->createView(),]);
    }


    /**
     * @Route("currentprojects", name="projects")
     */
    public function projects(): Response
    {
        return $this->render('currentprojects.html.twig', []);
    }

    /**
     * @Route("readmore", name="readmore")
     */
    public function readmore(): Response
    {
        return $this->render('readmore.html.twig', []);
    }

    /**
     * @Route("contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('contact.html.twig', []);
    }
}
