<script setup lang="ts">
import type { Project as ProjectType } from '@/types';
import { router } from '@inertiajs/vue3'
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
        <Project v-for="project in projects" :key="project.id" :project="project"
            data-project
            class="hover:transform hover:-translate-y-1 hover:-translate-x-1 hover:border-2 hover:border-blue-500 duration-75 cursor-pointer"
            @click="router.visit(`/projects/${project.id}`)">
        </Project>
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