<script setup lang="ts">
import ProjectList from '@/Components/ProjectList.vue';
import ProjectAddForm from '@/Components/ProjectAddForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Project } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, inject } from 'vue';

type Props = {
    projects: Project[]
}

const props = withDefaults(defineProps<Props>(), {
    projects: () => ([])
})

const projects = ref([...props.projects])

function onAddProject(project: Project) {
    projects.value.unshift(project)
}
</script>

<template>

    <Head title="Projetos" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex align-items justify-between items-center">
                <span>Projetos</span>
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="block text-lg font-medium text-gray-900 dark:text-white">Novo projeto</h2>
            <ProjectAddForm @add="onAddProject" />
            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
            <div class="mt-6">
                <h2 class="block mb-2 text-lg font-medium text-gray-900 dark:text-white">Projetos</h2>
                <ProjectList :projects="projects" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
