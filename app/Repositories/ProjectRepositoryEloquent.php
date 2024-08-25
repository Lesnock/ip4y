<?php

namespace App\Repositories;

use App\Repositories\Contracts\ProjectRepository;
use App\Domain\Entities\Project;
use App\Models\Project as ProjectModel;
use Carbon\Carbon;

class ProjectRepositoryEloquent implements ProjectRepository
{
    public function getById(int $id): ?Project
    {
        $row = ProjectModel::find($id);
        if (!$row) {
            return null;
        }
        return Project::build($row->title, $row->description, Carbon::parse($row->due_date)->toDateTime(), $row->id);
    }

    public function save(Project $project): int
    {
        $data = [
            'title' => $project->getTitle(),
            'description' => $project->getDescription(),
            'due_date' => $project->getdue_date()->format('Y-m-d h:i:s'),
        ];

        if (!$project->getId()) {
            $row = ProjectModel::create($data);
            return $row->id;
        }

        ProjectModel::where('id', $project->getId())->update($data);
        return $project->getId();
    }
}