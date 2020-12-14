<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    /**
     * @Route("/account/edit/{id}", name="app_account_edit")
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param string $photoDir
     * @return Response
     * @throws Exception
     */
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager, string $photoDir): Response
    {
        /* @var User $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé pour l\'id '.$id);
        }

        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($photo = $form['photo']->getData()) {
                $filename = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
                try {
                    $photo->move($photoDir, $filename);
                } catch (FileException $e) {
                    throw new FileException('Erreur lors de la copie du fichier');
                }
                $user->setPhotoFilename($filename);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Votre photo de profil a bien été enregistrée.');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig', [
            'userForm' => $form->createView()
        ]);
    }
}
