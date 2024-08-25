<?php

namespace App\Domain\Entities;

use App\Domain\Entity;
use App\Domain\Exceptions\EntityBuildValidationException;
use App\Domain\ValueObjects\TaskStatus;
use DateTime;

class Task extends Entity
{
    private ?int $id;
    private string $title;
    private string $description;
    private TaskStatus $status;
    private int $project_id;
    private int $responsible_id;
    private DateTime $due_date;

    /**
     * Construtor
     * Criei o construtor como "private" para que a classe nunca seja instanciada diretamente
     * mas sempre passe pelo método "build" que fará as devidas validações.
     * Pode parecer "Overengineering", mas assim garantimos que a entidade Task sempre estará em um estado válido.
     */
    private function __construct()
    {}

    public static function build(
        string $title, 
        string $description, 
        string $status, 
        string $due_date, 
        int $project_id, 
        int $responsible_id, 
        ?int $id = null
    ) {
        // Se não possui ID, significa que uma nova tarefa está sendo criado
        // Neste caso não podemos permitir que a data de vencimento seja antes de "agora"
        $today = (new DateTime)->setTime(0, 0);
        $dueDateDateTime = new DateTime($due_date);
        if (!$id && $dueDateDateTime < $today) {
            throw new EntityBuildValidationException("A data de conclusão já passou");
        }

        $task = (new Task)
            ->setTitle($title)
            ->setDescription($description)
            ->setStatus($status)
            ->setProjectId($project_id)
            ->setResponsibleId($responsible_id)
            ->setDueDate($due_date);

        // ID's não podem ser alterados, por isso não criamos um setter para ele
        $task->id = $id;
        return $task;
    }

    public function setTitle(string $title)
    {
        if (empty($title)) {
            throw new EntityBuildValidationException("O título da tarefa deve conter pelo menos 1 caractere");
        }
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description)
    {
        if (empty($description)) {
            throw new EntityBuildValidationException("A descrição da tarefa deve conter pelo menos 1 caractere");
        }
        $this->description = $description;
        return $this;
    }

    public function setStatus(string $status)
    {
        $this->status = new TaskStatus($status);
        return $this;
    }

    public function setProjectId(int $project_id)
    {
        $this->project_id = $project_id;
        return $this;
    }

    public function setResponsibleId(int $responsible_id)
    {
        $this->responsible_id = $responsible_id;
        return $this;
    }

    public function setDueDate(string $due_date)
    {
        $this->due_date = new DateTime($due_date);
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getStatus()
    {
        return $this->status->getStatus();
    }

    public function getProjectId()
    {
        return $this->project_id;
    }
    
    public function getResponsibleId()
    {
        return $this->responsible_id;
    }

    public function getDueDate()
    {
        return $this->due_date->format('Y-m-d');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'status' => $this->getStatus(),
            'project_id' => $this->getProjectId(),
            'responsible_id' => $this->getResponsibleId(),
            'description' => $this->getDescription(),
            'due_date' => $this->getDueDate()
        ];
    }
}