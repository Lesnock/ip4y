<?php

namespace App\Domain\Entities;

use App\Domain\Exceptions\InvalidProjectParametersException;
use DateTime;

/**
 * Entidade Projeto
 * Aqui ficam armazenadas todas as regras de negócio relacionadas diretamente com a entidade Project.
 * Eu optei por criar as entidades separadas dos models Eloquent porque assim nós conseguimos
 * desacoplar a regra de negócio das regras de persistência do banco de dados.
 * Para as entidades de domínio, não importa como elas são armazenadas no banco. A única coisa que importa
 * é que a entidade sempre esteja em um estado válido, e que as regras de negócio não vazem para a camada de aplicação.
 */
class Project
{
    private string $title;
    private string $description;
    private DateTime $dueDate;

    /**
     * Construtor
     * Criei o construtor como "private" para que a classe nunca seja instanciada diretamente
     * mas sempre passe pelo método "build" que fará as devidas validações.
     * Pode parecer "Overengineering", mas assim garantimos que a entidade Project sempre estará em um estado válido.
     */
    private function __construct()
    {}

    public static function build(string $title, string $description, DateTime $dueDate)
    {
        return (new Project)
            ->setTitle($title)
            ->setDescription($description)
            ->setDueDate($dueDate);
    }

    public function setTitle(string $title)
    {
        if (empty($title)) {
            throw new InvalidProjectParametersException("O título do projeto deve conter pelo menos 1 caractere");
        }
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description)
    {
        if (empty($description)) {
            throw new InvalidProjectParametersException("O título do projeto deve conter pelo menos 1 caractere");
        }
        $this->description = $description;
        return $this;
    }

    public function setDueDate(DateTime $dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
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
        return $this->dueDate;
    }
}