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
    <TransitionGroup name="list" tag="ul">
        <Project v-for="project in projects" :key="project.id" :project="project" class="project"></Project>
    </TransitionGroup>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>