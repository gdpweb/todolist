<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     * @Security("is_granted('ROLE_USER')")
     */
    public function listAction()
    {
        $title = "Liste des tâches à réaliser";
        $tasks = $this->getDoctrine()->getRepository('AppBundle\Entity\Task')->findByStatus(0);

        $response = $this->render(
            'task/list.html.twig',
            [
                'tasks' => $tasks,
                'title' => $title
            ]
        );
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;
    }

    /**
     * @Route("/tasks/done", name="task_status_done")
     * @Security("is_granted('ROLE_USER')")
     */
    public function listStatusDoneAction()
    {
        $title = "Liste des tâches terminées";
        $tasks = $this->getDoctrine()->getRepository('AppBundle\Entity\Task')->findByStatus(1);
        $response =  $this->render(
            'task/list.html.twig',
            [
                'tasks' => $tasks,
                'title' => $title
            ]
        );
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @Security("is_granted('ROLE_USER')")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $task->setUser($this->getUser());
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        $response =  $this->render('task/create.html.twig', ['form' => $form->createView()]);
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * @Security("is_granted('ROLE_USER')")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }
        $response =  $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @Security("is_granted('ROLE_USER')")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        if ($task->isDone()) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        } else {
            $this->addFlash('warning', sprintf('La tâche %s a bien été marquée comme non faite.', $task->getTitle()));
        }
        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @Security("is_granted('ROLE_USER')")
     */
    public function deleteTaskAction(Task $task)
    {
        if ($this->getUser() === $task->getUser() or ($task->getUser() === null and $this->isGranted('ROLE_ADMIN'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer cette tâche');
        }

        return $this->redirectToRoute('task_list');
    }
}
