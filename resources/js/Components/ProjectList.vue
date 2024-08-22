<script setup lang="ts">
import type { Project as ProjectType } from '@/types';
import Project from './Project.vue';

type Props = {
    projects: ProjectType[]
}

withDefaults(defineProps<Props>(), {
    projects: () => []
})
</script>

<template>
    <div class="p-6 text-gray-900 dark:text-gray-100 ">
        <div class="empty" v-if="!projects.length">
            <span class="text-lg">Adicionar Projeto</span>
            <!-- Você ainda não possui projetos. -->
            <input class="mt-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                placeholder="Título do Projeto..." 
                required />

            <textarea class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-4" 
                placeholder="Descrição do Projeto..." 
                required />

            <input type="datetime-local" class="mt-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                required />

            <button type="button" class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Salvar
            </button>

        </div>

        <div class="projects">
            <Project v-for="project in projects" :project="project" class="project"></Project>
        </div>
    </div>
</template>