<?php
namespace App\Controller;

use App\Entity\Job;
use App\Entity\Note;
use App\Repository\JobRepository;
use App\Repository\NoteRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\arrayHasKey;

class JobsController extends AbstractController {

    #[Route('/')]
    public function index(JobRepository $jobRepository, Request $request) {

        $sort = $this->handleSort($request);
        $jobs = $jobRepository->findAllBy($sort['active_field'], $sort[$sort['active_field']]);


        $statusOptions = [Job::SCHEDULED_STATUS,
            Job::TO_PRICE_STATUS,
            Job::INVOICEING_STATUS,
            Job::COMPLETED_STATUS];

        return $this->render('index/index.html.twig', [
            'jobs' => $jobs,
            'sort' => $sort,
            'statusoptions' => $statusOptions
        ]);
    }

    #[Route('/notes/{id}', name: 'notes_view')]
    public function notes(Job $job, NoteRepository $noteRepository,ManagerRegistry $doctrine) {

//        $notes = $noteRepository->findAll();
        $notes = $noteRepository->findAllByJobId($job->getId());


        return $this->render('index/notes.html.twig', [
            'job' => $job,
            'notes' => $notes,
        ]);

    }

    #[Route('/note/new/{jobId}')]
    public function addNote(int $jobId,Request $request,ManagerRegistry $doctrine) {
        $text = $request->get('text');

        $entityManager = $doctrine->getManager();
        $note = new Note();
        $note->setText($text);
        $note->setJobId($jobId);
        $note->setCreated(new DateTime());

        $entityManager->persist($note);
        $entityManager->flush();

        return $this->redirectToRoute('notes_view', [
            'id' => $jobId
        ]);
    }

    #[Route('/job/status/{jobId}')]
    public function updateStatus(int $jobId, ManagerRegistry $doctrine,JobRepository $jobRepository,Request $request) {
            $status = $request->get('status');

            $job = $jobRepository->find($jobId);

            $job->setStatus($status);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            return new Response('ok');
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

    private function handleSort(Request $request)
    {
        $session = new Session();
        $session->start();

        if($session->get('sort') == null) {

            $sort = ['id' => 'asc',
                'client_name' => 'asc',
                'contact_number' => 'asc',
                'status' => 'asc',
                'active_field' => 'id'];

            $session->set('sort', $sort);
        }

        $sort = $session->get('sort');

        if($request->get('toggle')) {
            $field = $request->get('toggle');
            if (array_key_exists($field, $sort)) {
                $sort[$field] = ($sort[$field] == 'asc') ? 'desc' : 'asc';
                $sort['active_field'] = $field;
                $session->set('sort',$sort);
            }
        }

        return $sort;
    }

}