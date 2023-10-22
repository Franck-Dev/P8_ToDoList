<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'app_task_index', methods: ['GET'])]


    /**
     * @param TaskRepository $taskRepository
     * 
     * @return Response
     */
    public function index(TaskRepository $taskRepository): Response
    {
        return $this->render('task/index.html.twig', ['tasks' => $taskRepository->findAll(),]);

    }


    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted()===TRUE && $form->isValid()===TRUE) {
            $task->setUserCreat($this->getUser());
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', ['task' => $task,'form' => $form,]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    /**
     * @param Task $task
     * 
     * @return Response
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', ['task' => $task,]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    #[Security("task.getUserCrea() == user")]
    /**
     * @param Request $request
     * @param Task $task
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response
     */
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($task->getUserCreat() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No access for you!');
        }
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', ['task' => $task,'form' => $form,]);
    }

    #[Route('/{id}/toggle', name: 'app_task_toggle')]
    #[IsGranted('ROLE_USER')]
    /**
     * @param Task $task
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response
     */
    public function toggleTaskAction(Task $task, EntityManagerInterface $entityManager)
    {
        if ($task->getUserCreat() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No access for you!');
        }
        $task->toggle(!$task->isDone());
        $entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('app_task_index');
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    /**
     * @param Request $request
     * @param Task $task
     * @param EntityManagerInterface $entityManager
     * 
     * @return Response
     */
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($task->getUserCreat() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No access for you!');
        }
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))===TRUE) {
            $entityManager->remove($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
