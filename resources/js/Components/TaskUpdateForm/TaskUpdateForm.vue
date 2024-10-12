<script setup lang="ts">
import { inject } from 'vue';
import { TaskGateway } from '@/types/gateways';
import Loading from '@/Components/Loading.vue';
import { Props, useLogic } from './logic';
import DeleteTaskButton from '@/Components/DeleteTaskButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps<Props>()

defineEmits(['delete'])
const gateway = inject('taskGateway') as TaskGateway
const {
    loading,
    form,
    errors,
    submit
} = useLogic(props, gateway)
</script>

<template>
    <div
        class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg mb-4 sm:px-2 lg:px-8 p-4 text-gray-900 dark:text-gray-100 relative">
        <div class="flex justify-between items-center flex-wrap gap-4 w-full mb-4">
            <div class="w-full relative">
                <div class="text-gray-400 mb-1">Título</div>
                <input class="borderless-input" :class="{ 'error': errors.title }" v-model="form.title" required />
                <div class="text-red-500" v-if="errors.title">{{ errors.title }}</div>
            </div>

            <div class="w-full relative">
                <div class="text-gray-400 mb-1">Descrição</div>
                <textarea class="borderless-input" :class="{ 'error': errors.description }" v-model="form.description" required />
                <div class="text-red-500" v-if="errors.description">{{ errors.description }}</div>
            </div>
        </div>

        <div class="flex justify-between items-center flex-wrap gap-4 w-full">
            <div data-status>
                <div class="text-gray-400 mb-1">Status</div>
                <select
                    class="w-40 block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    :class="{ 'error': errors.status }"
                    v-model="form.status">
                    <option value="pendent">Pendente</option>
                    <option value="in-progress">Em progresso</option>
                    <option value="completed">Finalizada</option>
                </select>
                <div class="text-red-500" v-if="errors.status">{{ errors.status }}</div>
            </div>

            <div data-responsible>
                <div class="text-gray-400 mb-1">Responsável</div>
                <select
                    v-model="form.responsible_id"
                    :class="{ 'error': errors.responsible_id }"
                    class="w-60 block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                </select>
                <div class="text-red-500" v-if="errors.responsible_id">{{ errors.responsible_id }}</div>
            </div>

            <div>
                <div class="text-gray-400 mb-1">Data de Vencimento</div>
                <div class="relative">
                    <input data-input-due-date type="date" class="borderless-input" :class="{ 'error': errors.due_date }" required
                        v-model="form.due_date" />
                </div>
                <div class="text-red-500" v-if="errors.due_date">{{ errors.due_date }}</div>
            </div>

            <div data-delete-icon>
                <DeleteTaskButton :task="task" @delete="$emit('delete')" />
            </div>
        </div>

        <div class="mt-4">
            <PrimaryButton class="flex items-center gap-1" data-input-submit-button @click="submit">
                Salvar tarefa
            </PrimaryButton>
        </div>
        
        <Loading v-if="loading" />
    </div>
</template>