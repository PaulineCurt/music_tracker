<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    #[Route('/tasks', name: 'task_list')]
    public function list(): Response
    {
        // Récupérer les tâches depuis la base de données
        $tasks = $this->taskRepository->findAll();
        // dd($tasks);
        return $this->render('tasks/list.html.twig', ['tasks' => $tasks]);
    }
}
