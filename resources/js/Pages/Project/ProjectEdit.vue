<script setup lang="ts">
import ProjectUpdateForm from '@/Components/ProjectUpdateForm/ProjectUpdateForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteProjectButton from '@/Components/DeleteProjectButton.vue';
import { Project, Task, User } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import TaskList from '@/Components/TaskList.vue';
import TaskAddForm from '@/Components/TaskAddForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

type Props = {
    project: Project
    users: User[]
}

const props = defineProps<Props>()

const project = ref(props.project)

function onAddTask(task: Task) {
    props.project.tasks.push(task)
}
</script>

<template>

    <Head title="Projetos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">

                <h2
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex align-items justify-between items-center">
                    <span>Projeto # {{ project.id }}</span>
                </h2>

                <div class="flex gap-2">
                    <a :href="route('projects.xlsx', { id: props.project.id })" target="_blank">
                        <PrimaryButton :project="project">Exportar Xlsx</PrimaryButton>
                    </a>
                    <a :href="route('projects.pdf', { id: props.project.id })" target="_blank">
                        <PrimaryButton :project="project">Exportar PDF</PrimaryButton>
                    </a>
                    <DeleteProjectButton :project="project">Excluir projeto</DeleteProjectButton>
                </div>
            </div>

            <ProjectUpdateForm :project="project" />
        </template>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="mt-6">
                <h2 class="block mb-2 text-lg font-medium text-gray-900 dark:text-white">Tarefas</h2>
                <TaskList :tasks="project.tasks" :users="users" />

                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

                <h2 class="block mb-2 text-lg font-medium text-gray-900 dark:text-white">Nova tarefa</h2>
                <TaskAddForm :project="project" :users="users" @add="onAddTask" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
