<?php

namespace App\Controller;

// use Amp\Http\Client\Request;

use App\Entity\Currentapply;
use App\Form\CurrentapplyType;
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

    // /**
    //  * @Route("/currentapply", name="currentapply")
    //  */
    // public function currentapply(): Response
    // {
    //     return $this->render('currentapply.html.twig', [
    //         'controller_name' => 'HomeController',
    //     ]);
    // }

    // #[Route('/currentapply', name: "", methods: ['POST'])]
    /**
     * @Route("/currentapply", name="currentapply")
     */
    public function generate_pdf(Request $request, ManagerRegistry $entityManager)
    {

        // dump($request);


        // $form = $this->createForm(CurrentapplyType::class);
        // return $this->render('currentapply.html.twig', [
        //     'form' => $form->createView()
        // ]);


        $currentapply = new Currentapply();
        $form = $this->createForm(CurrentapplyType::class, $currentapply);
        $form->handleRequest($request);


        // if ($form->isSubmitted() && $form->isValid()) {

        // }


        if (isset($_POST['send'])) {


            $currentapply->setNom($_POST['Name']);
            $currentapply->setPrenom($_POST['prenom']);
            $currentapply->setDate(new \DateTime());

            // dump($currentapply);
            // $files = $_FILES["Passport"]["name"];
            // $currentapply->setFile($files);
            // var_dump($request('file'));



            //************Upload image ************* */
            $file = $currentapply->getFile();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('Upload_dir'), $fileName);
            $currentapply->setFile($fileName);

            //********Set DATA TO MySQL***** */
            $em = $entityManager->getManager();
            $em->persist($currentapply);
            $em->flush();

            // $file = $this->getParameter('Upload_dir') . '/' . $fileName;
            // $response = new BinaryFileResponse($file);
            // you can modify headers here, before returning
            //echo $response;
            // return $response;


            // $this->entityManager = $this->$this->getDoctrine()->getManager();
            // $entityManager->$entityManager->persist($currentapply);
            // $entityManager->flush();



            // $this->entityManager->flush();

            // $entityManager = $this->$this->getDoctrine()->getManager();
            // $entityManager->flush();

            // move_uploaded_file($this->getParameter('Upload_dir'), $file);
            // $file = $_FILES["Passport"]["name"];

            // if ($request->hasFile('file')) {
            // $destinationPath = 'path/th/save/file/';
            // // $files = $request->$this->file('Passport'); // will get all files

            // // foreach ($files as $file) { //this statement will loop through all files.
            // // $file_name = $file->getClientOriginalName(); //Get file original name
            // $file->move($destinationPath, $file); // move files to destination folder
            // }
            // }


            // $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            // $file->move($this->getParameter('Upload_dir'), $fileName);
            // $currentapply->setFile($fileName);

            // return $this->redirectToRoute('homepage');


            $options = new Options();
            $options->set('defaultFont', 'Roboto');
            $dompdf = new Dompdf($options);

            // $data = array(
            //     'headline' => 'my headline'
            // );
            // $view = 'currentapply.html.twig';
            // $parameters = $_POST['Gender'];
            // $html = $this->renderForm($view, $parameters, $response);
            // $html = $this->render('currentapply.php', [
            //     'headline' => "Test pdf generator"
            // ]);
            // $uploads_dir = '/uploads';



            // $name = "";
            // foreach ($_FILES["Passport"]["error"] as $key => $error) {
            //     if ($error == UPLOAD_ERR_OK) {
            //         $tmp_name = $_FILES["Passport"]["tmp_name"][$key];
            //         // basename() peut empêcher les attaques de système de fichiers;
            //         // la validation/assainissement supplémentaire du nom de fichier peut être approprié
            //         $name = basename($_FILES["Passport"]["name"][$key]);
            //         move_uploaded_file($tmp_name, "$uploads_dir/$name");
            //     }
            // }

            // $tmp_name = $_FILES["Passport"]["tmp_name"];


            // move_uploaded_file($name, "/uploads");

            // if (move_uploaded_file($_FILES['Passport']['tmp_name'], __DIR__ . '/../../uploads/' . $_FILES["Passport"]['name'])) {
            //     echo "Uploaded";
            // } else {
            //     echo "File not uploaded";
            // }

            /************************FDF***** */
            // $html = <<<EOF

            // EOF;
            // $html = $this->renderView('currentapply.html.twig', [
            //     'headline' => "Test pdf generator"
            // ]);
            $html =  $this->render('confirmation.html.twig', [
                'id' => $currentapply->getId(),
                'form' => $form->createView(),
                'nom' => $currentapply->getNom(),
                'prenom' => $currentapply->getPrenom(),
                'img' => $fileName

            ]);

            // echo $html;

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("testpdf.pdf", [
                "Attachment" => true
            ]);
            /************************** */

            // Si vous voulez le faire télécharger par le navigateur
            // $dompdf->stream("document.pdf", array("Attachment" => true));

            // OU si vous voulez le mettre dans un ficher sur le serveur
            // file_put_contents("document.pdf", $dompdf->output());
            return $this->render('confirmation.html.twig', [
                'id' => $currentapply->getId(),
                'form' => $form->createView(),
                'nom' => $currentapply->getNom(),
                'prenom' => $currentapply->getPrenom(),
                'img' => $fileName

            ]);
            // return $this->render('currentapply.html.twig');
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



        // if ($form->isSubmitted() && $form->isValid()) 
        if (isset($_POST['send'])) {


            $options = new Options();
            $options->set('defaultFont', 'Roboto');
            $dompdf = new Dompdf($options);
            echo "hhhhh";
            // $html = $this->generateUrl("confirmation.twig.html");

            // $html = $this->renderView('confirmation.html.twig', [
            //     'headline' => "Test pdf generator"
            // ]);

            // $dompdf->set_option('chroot', getcwd()); //assuming HTML file is in the root folder
            // $dompdf->loadHtmlFile('currentapply.html.twig');

            $html = file_get_contents('../templates/confirmation.html.twig');

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("testpdf.pdf", [
                "Attachment" => true
            ]);

            // $fileName = md5(uniqid()) . '.' . $dompdf->guessExtension();
            // $file->move($this->getParameter('Upload_dir'), $fileName);
            // $currentapply->setFile($fileName);


            return $this->render('currentapply.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        // return $this->render('currentapply.html.twig', [
        //     'form' => $form->createView(),
        // ]);
    }


    /**
     * @Route("currentprojects", name="projects")
     */
    public function projects(): Response
    {
        return $this->render('currentprojects.html.twig', []);
    }

    /**
     * @Route("readmore", name="index")
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
