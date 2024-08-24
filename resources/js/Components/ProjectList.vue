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
    <Project v-for="project in projects" :project="project" class="project"></Project>
</template>