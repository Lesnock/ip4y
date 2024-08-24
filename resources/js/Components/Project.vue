<script setup lang="ts">
import type { Project } from '@/types';
import { computed } from 'vue';
import PrimaryButton from './PrimaryButton.vue';

const props = defineProps<{
    project: Project
}>()

const completedQuantity = computed(() => {
    return props.project.tasks.filter(task => task.status == 'completed').length
})
</script>

<template>
    <div
        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:px-6 lg:px-8 p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center w-full">
            <div class="flex-1">
                <div data-title class="text-2xl font-semibold"># {{ project.id }} - {{ project.title }}</div>
                <div data-description class="text-gray-400">{{ project.description }}</div>
            </div>

            <div data-tasks-status class="flex-1 text-center">
                <div class="text-gray-400 mb-1">Tarefas</div>
                <span
                    class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300 border border-green-400">
                    {{ completedQuantity }} / {{ $props.project.tasks.length }}
                </span>
            </div>

            <div data-tasks-status class="flex-1 text-center">
                <div class="text-gray-400 mb-1">Data de Conclusão</div>
                <span
                    data-due-date
                    class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 border border-blue-400">
                    {{ project.due_date.toLocaleDateString() }}
                </span>
            </div>

            <div class="flex-1 text-right">
                <PrimaryButton>
                    Abrir
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>