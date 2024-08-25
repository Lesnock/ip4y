<script setup lang="ts">
import ProjectUpdateForm from '@/Components/ProjectUpdateForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue'
import DeleteProjectButton from '@/Components/DeleteProjectButton.vue';
import { Project } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

type Props = {
    project: Project
}

const props = defineProps<Props>()

const project = ref(props.project)

function onAddProject(project: Project) {
    // projects.value.unshift(project)
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

                <DeleteProjectButton :project="project">Excluir projeto</DeleteProjectButton>
            </div>

            <ProjectUpdateForm :project="project" @add="onAddProject" />
        </template>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="mt-6">
                <h2 class="block mb-2 text-lg font-medium text-gray-900 dark:text-white">Tarefas</h2>
                <!-- <ProjectList :projects="projects" /> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
