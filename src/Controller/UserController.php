<?php

namespace App\Controller;

//use Doctrine\DBAL\Types\TextType;
use App\Entity\Topic;
use App\Entity\UtilisateurVuTopic;
use App\Form\TopicType;
use App\Form\UserLoginType;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use App\Repository\UtilisateurVuTopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class UserController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return Response
     * @Route("/",name="welcome")
     */
    public function index(Request $request)
    {
        return $this->render(
            'user/welcome.html.twig',
            [
                'message' => 'Welcome here!',

            ]
        );
    }

    /**
     * @Route("/signup", name="create_user")
     */
    public function signUp(Request $request)
    {

        $user = new User();
        $user->setPhoto('image.png');

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();

            if (!$file) {
                die('ya une erreur');
            }

            $globalPath = $file->getRealPath();
            $this->resizeImage($globalPath);

            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('photos_directory'),
                $filename
            );

            $user->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Bienvenue sur notre plateforme.');

            return $this->redirectToRoute('app_login');

        }

        return $this->render(
            'user/index.html.twig',
            [
                'controller_name' => 'UserController',
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/home/{page}",requirements={"page" = "\d+"}, name="homepage")
     */
    public function homepage($page, UserRepository $userRepository)
    {
        $nbMaxParPage = 3;

        $this->controlPageNumber($page);

//        $nbArticlesParPage = 5;

        $users = $userRepository->findAllWithPagination($page, $nbMaxParPage);

        $pagination = array(
            'nbPages' => ceil(count($users) / $nbMaxParPage),
            'page' => $page,
            'nomRoute' => 'homepage',
            'paramsRoute' => array(),
        );

        return $this->render(
            'user/listing_users.html.twig',
            [
                'page' => $page,
                'message' => 'Welcome home!',
                'users' => $users,
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * @Route("/ajax", name="modify_user")
     */
    public function ajax(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $jsonData = array();

            $changeUsernameInfo = $request->request->has('id') && $request->request->has('Username') &&
                $request->request->has('First_Name') && $request->request->has('Last_Name') &&
                $request->request->has('Birthday');

            $changeUserAddressInfo = $request->request->has('id') && $request->request->has('Postal_Code') &&
                $request->request->has('City') && $request->request->has('Country') &&
                $request->request->has('Address');

            $em = $this->getDoctrine()->getManager();
            $userRepository = $em->getRepository(User::class);

            if ($changeUsernameInfo) {
                $id = $request->request->get('id');
                $pseudo = $request->request->get('Username');
                $nom = $request->request->get('Last_Name');
                $prenom = $request->request->get('First_Name');
                $date = $request->request->get('Birthday');

                $birthday = new \DateTime($date);

                $user = $userRepository->find($id);
                $user->setPseudo($pseudo);
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setDateDeNaissance($birthday);
                $em->flush();

                $jsonData['message'] = "Username Information updated";
            }

            if ($changeUserAddressInfo) {
                $id = $request->request->get('id');
                $codePostal = $request->request->get('Postal_Code');
                $ville = $request->request->get('City');
                $pays = $request->request->get('Country');

                $user = $userRepository->find($id);
                $user->setCodePostal($codePostal);
                $user->setVille($ville);
                $user->setPays($pays);
                $em->flush();

                $jsonData['message'] = "User Address Information updated";

            }

            return new JsonResponse($jsonData);
        } else {
            return new JsonResponse(array('message' => 'non'));
        }

    }

    /**
     * @Route("/profil", name="profil_user")
     */
    public function showProfil(Request $request)
    {
        $topic = new Topic();

        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            $titre = $form->get('titre')->getData();

            $topic->setTitre($titre);
            $topic->setUtilisateurId($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($topic);
            $em->flush();

            $this->addFlash('success', 'New topic registered');

        }

        return $this->render(
            'user/profil.html.twig',
            [
                'message' => 'Welcome home!',
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/fileupload", name="fileupload")
     */
    public function ajaxFile(Request $request)
    {
        $test = $request->files->has('file');
        $originalFilename = "vide";

        if ($test) {

            $file = $request->files->get('file');

            if (!$file) {
                die('ya une erreur');
            }

            $globalPath = $file->getRealPath();
            $this->resizeImage($globalPath);

            $filename = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('photos_directory'),
                $filename
            );

            $user = $this->getUser();
            $user->setImage($filename);
            $this->getUser()->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new JsonResponse(array('filename' => $filename));
//            return $this->redirectToRoute('profil_user');
        }

        return new JsonResponse(array('message' => 'coucou','filename'=> $originalFilename));
    }

    /**
     * @Route("/topics/{page}", requirements={"page" = "\d+"}, name="topics")
     */
    public function showTopics(int $page, Request $request, UserRepository $userRepository, TopicRepository $topicRepository)
    {

        $nbMaxParPage = 3;
        $recherche = 0;

        $errors = $this->controlPageNumber($page);

        if (count($errors) > 0) {
            return $this->render(
                'user/topics.html.twig',
                ['errors' => $errors]
            );
        }

        if ($request->request->has('author') && $request->request->has('topicTitle')) {

            $pseudo = $request->request->get('author');
            $titre = $request->request->get('topicTitle');

            $topics = $topicRepository->findTopicByTitreAndAuteur($titre, $pseudo);

            if (count($topics) < 1) {
                $this->addFlash('failed', 'Author not found and title does not exist');
            } else {
                $this->addFlash('success', 'Search result');
            }
            $recherche = 1;

        } else {
            $topics = $topicRepository->findAllWithPagination($page, $nbMaxParPage);
        }

        $pagination = array(
            'nbPages' => ceil(count($topics) / $nbMaxParPage),
            'page' => $page,
            'nomRoute' => 'topics',
            'paramsRoute' => array(),
        );

        return $this->render(
            'user/topics.html.twig',
            [
                'topics' => $topics,
                'page' => $page,
                'pagination' => $pagination,
                'recherche' => $recherche,
            ]
        );

    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @Route("/savetopic", name="saveTopic")
     */
    public function saveTopic(Request $request, TopicRepository $topicRepository, UtilisateurVuTopicRepository $utilisateurVuTopicRepository)
    {
        $jsonData = array('seen' => 0);

        if ($request->isXmlHttpRequest() && $request->request->has('id_topic')) {

            $jsonData['seen'] = 1;

            $topicId = $request->request->get('id_topic');

            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createNotFoundException('You must log in');
            }

            $userId = $user->getId();

            $vu = $utilisateurVuTopicRepository->findOneBy(['utilisateurId' => $userId, 'topicId' => $topicId]);

            if (!$vu instanceof UtilisateurVuTopic && is_null($vu)) {

                $topic = $topicRepository->findOneBy(['id' => $topicId]);

                if (!$topic instanceof Topic) {
                    throw $this->createNotFoundException('Topic not found');
                }

                $vuTopic = new UtilisateurVuTopic();
                $vuTopic->setUtilisateurId($user);
                $vuTopic->setTopicId($topic);

                $em = $this->getDoctrine()->getManager();
                $em->persist($vuTopic);
                $em->flush();

                $jsonData['seen'] = 2;
            }
        }

        return new JsonResponse($jsonData);
    }

    /**
     * @param int $page
     *
     * @return array
     */
    private function controlPageNumber(int $page) {
        // fonction qui contrôle le paramètre page pour les paginations
        $errors = [];
        if (!is_numeric($page)) {
//            throw new InvalidArgumentException(
//                'La valeur de l\'argument $page est incorrecte (valeur : '.$page.').'
//            );
            $errors[] = 'La valeur de l\'argument $page est incorrecte (valeur : '.$page.').';
        }

        if ($page < 1) {
//            throw new NotFoundHttpException('La page demandée n\'existe pas');
            $errors[] = 'La page demandée n\'existe pas';
        }
        return $errors;
    }

    private function resizeImage($file)
    {
//        $file = "C:\Users\m.raharitsiresy\Desktop\oiseau.jpg";

        $x = 200;
        $y = 200; # Taille en pixel de l'image redimensionnée

        $size = getimagesize($file);

        if ($size) {
//            echo 'redimensionnement en cours';
            if ($size['mime'] == 'image/jpeg') {
                $img_big = imagecreatefromjpeg($file); # On ouvre l'image d'origine
                $img_new = imagecreate($x, $y);
                # création de la miniature
                $img_mini = imagecreatetruecolor($x, $y);
                //or $img_mini = imagecreate($x, $y);
                // copie de l'image, avec le redimensionnement.
                imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);
                imagejpeg($img_mini, $file);

            } else{
                $img_big = imagecreatefrompng($file);
                # On ouvre l'image d'origine
                $img_new = imagecreate($x, $y);
                # création de la miniature
                $img_mini = imagecreatetruecolor($x, $y);
                //or $img_mini = imagecreate($x, $y);
                // copie de l'image, avec le redimensionnement.
                imagecopyresized($img_mini, $img_big, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);
                imagepng($img_mini, $file);
            }
        }
    }
}
