<?php

namespace App\Repositories\Contracts;

use App\Domain\Entities\Project;

interface ProjectRepository
{
    public function save(Project $project): int;
}