<script setup lang="ts">
import type { Project as ProjectType } from '@/types';
import Project from './Project.vue';

defineProps<{
    projects: ProjectType[]
}>()
</script>

<template>
    <div class="projects-list">
        <div class="empty" v-if="!projects.length">
            Você ainda não possui projetos.
        </div>

        <div class="projects">
            <Project v-for="project in projects" :project="project" class="project"></Project>
        </div>
    </div>
</template>