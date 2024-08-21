<script setup lang="ts">
import type { Project } from '@/types';

defineProps<{
    project: Project
}>()
</script>

<template>
    <div class="project">
        {{ project }}
    </div>
</template>