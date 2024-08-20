<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Project;

interface ProjectRepository
{
    public function save(Project $project): int;
}