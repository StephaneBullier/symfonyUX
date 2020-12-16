<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\UX\Cropperjs\Form\CropperType;

class AccountController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /**
     * AccountController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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
     * @param string $photoDir
     * @return Response
     * @throws Exception
     */
    public function edit(int $id, Request $request, string $photoDir): Response
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
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre photo de profil a bien été enregistrée.');

            return $this->redirectToRoute('app_crop_image');
        }

        return $this->render('account/edit.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/crop", name="app_crop_image")
     * @param CropperInterface $cropper
     * @param Request $request
     * @param string $cropDir
     * @param string $photoDir
     * @return RedirectResponse|Response
     */
    public function crop(CropperInterface $cropper, Request $request, string $cropDir, string $photoDir)
    {
        /* @var User $user*/
        $user = $this->getUser();

        $crop = $cropper->createCrop($photoDir.$user->getPhotoFilename());
        $crop->setCroppedMaxSize(2000, 1500);

        $form = $this->createFormBuilder(['crop' => $crop])
            ->add('crop', CropperType::class, [
                'public_url' => $cropDir.$user->getPhotoFilename(),
                'view_mode' => 1,
                'aspect_ratio' => 2000 / 1500,
                'responsive' => true,
                'guides' => true,
            ])
            ->add('submit', SubmitType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            file_put_contents($photoDir.$user->getPhotoFilename(), $crop->getCroppedImage());

            $this->addFlash('success', 'Votre photo de profil a bien été modifiée.');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/crop.html.twig', [
            'cropForm' => $form->createView()
        ]);

    }
}
