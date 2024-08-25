<?php

namespace App\Domain\Entities;

use App\Domain\Entity;
use App\Domain\Exceptions\EntityBuildValidationException;
use DateTime;

/**
 * Entidade Projeto
 * Aqui ficam armazenadas todas as regras de negócio relacionadas diretamente com a entidade Project.
 * Eu optei por criar as entidades separadas dos models Eloquent porque assim nós conseguimos
 * desacoplar a regra de negócio das regras de persistência do banco de dados.
 * Para as entidades de domínio, não importa como elas são armazenadas no banco. A única coisa que importa
 * é que a entidade sempre esteja em um estado válido, e que as regras de negócio não vazem para a camada de aplicação.
 */
class Project extends Entity
{
    private ?int $id;
    private string $title;
    private string $description;
    private DateTime $due_date;

    /**
     * Construtor
     * Criei o construtor como "private" para que a classe nunca seja instanciada diretamente
     * mas sempre passe pelo método "build" que fará as devidas validações.
     * Pode parecer "Overengineering", mas assim garantimos que a entidade Project sempre estará em um estado válido.
     */
    private function __construct()
    {}

    public static function build(string $title, string $description, string $due_date, ?int $id = null)
    {
        // Se não possui ID, significa que um novo projeto está sendo criado
        // Neste caso não podemos permitir que a data de conclusão seja antes de "agora"
        $today = (new DateTime)->setTime(0, 0);
        $dueDateDateTime = new DateTime($due_date);
        if (!$id && $dueDateDateTime < $today) {
            throw new EntityBuildValidationException("A data de conclusão já passou");
        }

        $project = (new Project)
            ->setTitle($title)
            ->setDescription($description)
            ->setDueDate($due_date);

        // ID's não podem ser alterados, por isso não criamos um setter para ele
        $project->id = $id;
        return $project;
    }

    public function setTitle(string $title)
    {
        if (empty($title)) {
            throw new EntityBuildValidationException("O título do projeto deve conter pelo menos 1 caractere");
        }
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description)
    {
        if (empty($description)) {
            throw new EntityBuildValidationException("A descrição do projeto deve conter pelo menos 1 caractere");
        }
        $this->description = $description;
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

    public function getDueDate()
    {
        return $this->due_date->format('Y-m-d');
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'due_date' => $this->getDueDate()
        ];
    }
}