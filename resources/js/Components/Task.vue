<script setup lang="ts">
import type { Task, User } from '@/types';
import DangerButton from './DangerButton.vue';

const props = defineProps<{
    task: Task,
    users: User[]
}>()
</script>

<template>
    <div
        class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg mb-4 sm:px-2 lg:px-6 p-2 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center flex-wrap gap-4 w-full">
            <div class="flex-1">
                <div data-title class="text-xl font-semibold" 
                    :class="{ 'line-through': task.status == 'completed' }">
                    {{ task.title }}
                </div>
                <div data-description class="text-gray-400">{{ task.description }}</div>
            </div>

            <div data-status>
                <div class="text-gray-400 mb-1">Status</div>
                <select
                    class="w-40 block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="pendent">Pendente</option>
                    <option value="in-progress">Em progresso</option>
                    <option value="completed">Finalizada</option>
                </select>
            </div>

            <div data-responsible>
                <div class="text-gray-400 mb-1">Responsável</div>
                <select
                    class="w-60 block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                </select>
            </div>

            <div data-due-date>
                <div class="text-gray-400 mb-1">Data de Vencimento</div>
                <input data-input-due-date type="date" class="borderless-input !p-2 !w-40" required />
            </div>

            <div data-delete-icon>
                <DangerButton class="!px-2 py-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </DangerButton>
            </div>
        </div>
    </div>
</template>