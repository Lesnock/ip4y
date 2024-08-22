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
    <div 
        v-if="!projects.length" 
        data-empty
        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:px-6 lg:px-8 p-6 text-gray-900 dark:text-gray-100">
        Você ainda não possui projetos.
    </div>

    <Project v-for="project in projects" :project="project" class="project"></Project>
</template>