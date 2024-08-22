<script setup lang="ts">
import type { Project } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    project: Project
}>()

const completedQuantity = computed(() => {
    return props.project.tasks.filter(task => task.status == 'completed').length
})
</script>

<template>
    <div class="project">
        <div class="title">{{ project.title }}</div>
        <div class="description">{{ project.description }}</div>
        <div class="tasks-status">
            {{ completedQuantity }} / {{ $props.project.tasks.length }}
        </div>
    </div>
</template>