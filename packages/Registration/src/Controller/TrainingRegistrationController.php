<?php

declare(strict_types=1);

namespace Pehapkari\Registration\Controller;

use Pehapkari\Registration\Entity\TrainingRegistration;
use Pehapkari\Registration\Form\TrainingRegistrationFormType;
use Pehapkari\Registration\Repository\TrainingRegistrationRepository;
use Pehapkari\Training\Entity\TrainingTerm;
use Pehapkari\Training\Repository\TrainingTermRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TrainingRegistrationController extends AbstractController
{
    /**
     * @var TrainingRegistrationRepository
     */
    private $trainingRegistrationRepository;

    /**
     * @var TrainingTermRepository
     */
    private $trainingTermRepository;

    public function __construct(
        TrainingRegistrationRepository $trainingRegistrationRepository,
        TrainingTermRepository $trainingTermRepository
    ) {
        $this->trainingTermRepository = $trainingTermRepository;
        $this->trainingRegistrationRepository = $trainingRegistrationRepository;
    }

    /**
     * @Route(path="/registration/{slug}/", name="registration", methods={"GET", "POST"})
     *
     * @see https://github.com/symfony/demo/blob/master/src/Controller/Admin/BlogController.php
     */
    public function default(Request $request, TrainingTerm $trainingTerm): Response
    {
        $trainingRegistration = new TrainingRegistration();
        $trainingRegistration->setTrainingTerm($trainingTerm);
        $trainingRegistration->setPrice($trainingTerm->getPrice());

        $form = $this->createForm(TrainingRegistrationFormType::class, $trainingRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->trainingRegistrationRepository->save($trainingRegistration);

            // @todo send mail?

            return $this->redirectToRoute('registration_thank_you', [
                'slug' => $trainingTerm->getSlug(),
            ]);
        }

        return $this->render('registration/default.twig', [
            'training' => $trainingTerm->getTraining(),
            'trainingTerm' => $trainingTerm,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/vitej-na-skoleni/{slug}/", name="registration_thank_you")
     */
    public function thankYou(TrainingTerm $trainingTerm): Response
    {
        return $this->render('registration/thank_you_for_registration.twig', [
            'trainingTerm' => $trainingTerm,
        ]);
    }

    /**
     * @Route(path="/prehled-registraci/", name="registration-overview", methods={"GET"})
     */
    public function overview(): Response
    {
        return $this->render('registration/overview.twig', [
            'upcoming_training_terms' => $this->trainingTermRepository->getUpcoming(),
        ]);
    }
}
