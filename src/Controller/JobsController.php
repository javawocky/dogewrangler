<?php
namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JobsController extends AbstractController {

//    #[Route('/api/posts/{id}', methods: ['GET', 'HEAD'])]
// routing here https://symfony.com/doc/current/routing.html
// templates here https://symfony.com/doc/current/templates.html

    #[Route('/')]
    public function index(JobRepository $jobRepository) {
        $jobs = $jobRepository->findAllByCreatedDescending();
        return $this->render('index/index.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    #[Route('/jobs')]
    public function jobs(ManagerRegistry $doctrine, JobRepository $jobRepository) {
        $job = $jobRepository->findAllByCreatedDescending();
        return new Response($job[0]->getClientName());
    }

    #[Route('/job/{id}')]
    public function job(Job $job, ManagerRegistry $doctrine) {
        $job->setClientName('Bobby Flurry');
        $doctrine->getManager()->flush();
        return new Response('Job clients name is '.$job->getClientName());
    }

    #[Route('/test')]
    public function test(ManagerRegistry $doctrine, ValidatorInterface $validator) {
        $entityManager = $doctrine->getManager();

        $job = new Job();
        $job->setClientName('Bob');
        $job->setAddress('1 Boom staat');
        $job->setStatus(Job::SCHEDULED_STATUS);
        $job->setCreated(new DateTime());
         $job->setContactNumber('0800838383');

        // validate doesn't seam to work here
        $errors = $validator->validate($job);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($job);
        $entityManager->flush();

        return new Response('ok');
    }
}