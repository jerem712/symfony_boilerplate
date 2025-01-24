<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Task;
use App\Form\TaskType;
use DateTimeImmutable;

class TaskController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(TaskRepository $task_repo): Response
    {
        $tasks = $task_repo->findAll();
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $new_task = new Task();
        $new_task->setCreatedAt(new DateTimeImmutable());
        $new_task->setUpdatedAt(new DateTimeImmutable());
        $new_task->setAuthor("Roger");
        $form = $this->createForm(TaskType::class, $new_task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($new_task);
                $em->flush();
                return $this->redirectToRoute('index');
        }
        return $this->render('task/add.html.twig', [
            'new_task' => $new_task,
            'form' => $form,
        ]);
    }

    #[Route('/edit_{id}', name: 'edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em, TaskRepository $task_repo): Response
    {
        $task = $task_repo->findOneById($id);
        $task->setUpdatedAt(new DateTimeImmutable());
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('index');
        }
        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/view_{id}', name: 'view')]
    public function view(int $id, TaskRepository $task_repo): Response
    {
        $task = $task_repo->findOneById($id);
        return $this->render('task/view.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/delete_{id}', name: 'delete')]
    public function delete(int $id, TaskRepository $task_repo, EntityManagerInterface $em): Response
    {
        $task = $task_repo->findOneById($id);
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('index');
    }
}
