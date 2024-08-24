<script setup lang="ts">
import ProjectList from '@/Components/ProjectList.vue';
import ProjectAddForm from '@/Components/ProjectAddForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Project } from '@/types';
import { Head } from '@inertiajs/vue3';
import { inject, ref } from 'vue';
import { Link } from '@inertiajs/vue3'

const projects = ref<Project[]>(inject('projects') as Project[])

function onAddProject(project: Project) {
    console.log(project)
    projects.value.unshift(project)
}
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex align-items justify-between items-center">
                <span>Projetos</span>

                <Link type="button" href="/projects/add"
                    class="flex items-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="inline-block size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="mt-0.5 ml-1">Novo Projeto</span>
                </Link>
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto">
                <h2 class="block mb-2 text-lg font-medium text-gray-900 dark:text-white">Novo projeto</h2>
                <ProjectAddForm @add="onAddProject" />
                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                <div class="mt-6">
                    <h2 class="block mb-2 text-lg font-medium text-gray-900 dark:text-white">Projetos</h2>
                    <ProjectList :projects="projects" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
