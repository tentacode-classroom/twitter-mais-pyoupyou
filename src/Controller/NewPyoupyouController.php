<?php

namespace App\Controller;

use App\Entity\Incubator;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Security\AccessChecker;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\NewPyoupyouType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Pyoupyou;
use Symfony\Component\Validator\Constraints\DateTime;


class NewPyoupyouController extends AbstractController
{
    /**
     * @Route("/newPyoupyou", name="newPost")
     */
    public function index(Request $request, AccessChecker $accessChecker)
    {
        $pyoupyou = new Pyoupyou();
        $user = $accessChecker->getUser();

         /*   $projects = $this->getDoctrine()->getRepository(Project::class)->findAll();
            if (in_array($entity,$projects) )
                $pyoupyou->setProject($entity);
            else{
                $pyoupyou->setIncubator($entity);
            }
        }*/ //test pour le mettre dans l'uncubateur ou le projet

        $form = $this->createForm(NewPyoupyouType::class, $pyoupyou, [
            'action' => $this->generateUrl('newPost'),
        ]);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            // echo "OK";
            $pyoupyou = $form->getData();
            $pyoupyou->setUser( $user );
            $pyoupyou->setDate(new \DateTime());


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pyoupyou);
            $entityManager->flush();

            $previousUrl = $request->server->get('HTTP_REFERER');
            return $this->redirect($previousUrl);
        }

        return $this->render('form/form_pyoupyou.html.twig', [
            'title' => 'Ecrire un PyouPyou',
            'form' => $form->createView(),
        ]);
    }
}
