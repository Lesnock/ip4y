<script setup lang="ts">
import type { Task, User } from '@/types';
import toastr from 'toastr'
import DangerButton from './DangerButton.vue';
import { TaskAddForm } from '@/Forms/TaskAddForm';
import { inject, nextTick, onMounted, watch } from 'vue';
import { TaskGateway } from '@/types/gateways';
import { TaskUpdateForm } from '@/Forms/TaskUpdateForm';
import { TaskUpdateFormDTO } from '@/types/dto';
import { isValidDate } from '@/helpers';

const props = defineProps<{
    task: Task,
    users: User[]
}>()

const gateway = inject('taskGateway') as TaskGateway

const form = new TaskUpdateForm(props.task.project_id, gateway)

async function save(field: keyof TaskUpdateFormDTO) {
    const error = await form.patch(props.task.id, field)
    if (error) {
        toastr.error(`Erro ao salvar o campo: ${error}`); return;
    }
    
    toastr.success('Campo salvo com sucesso!')
}

onMounted(() => {
    if (props.task) {
        form.fill({
            title: props.task.title,
            description: props.task.description,
            status: props.task.status,
            project_id: props.task.project_id,
            responsible_id: props.task.responsible_id,
            due_date: new Date(props.task.due_date).toISOString().slice(0, 10),
        })

        watch(() => form.data().status, () => { save('status') })
        watch(() => form.data().responsible_id, () => { save('responsible_id') })
    }
})

</script>

<template>
    <div
        class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg mb-4 sm:px-2 lg:px-8 p-4 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center flex-wrap gap-4 w-full mb-4">
            <div class="w-full relative">
                <input class="borderless-input !pl-0 !pr-10" v-model="form.data().title" required />
                <div class="absolute top-2 right-1" :class="{
                    'text-gray-400 cursor-not-allowed': !form.data().title || form.data().title == form.saved().title,
                    'text-blue-500 cursor-pointer ring-2 rounded': form.data().title && form.data().title != form.saved().title,
                }" @click="save('title')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path
                            d="M18 2.25c.2 0 .39.08.53.22l3 3c.14.14.22.33.22.53v15c0 .41-.34.75-.75.75H3a.75.75 0 0 1-.75-.75V3c0-.41.34-.75.75-.75h15ZM17 12H7a1 1 0 0 0-1 1v7.25h12V13a1 1 0 0 0-1-1Zm-.5-8.25h-9V8a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V3.75Zm-2.25.75c.41 0 .75.34.75.75v1.5a.75.75 0 1 1-1.5 0v-1.5c0-.41.34-.75.75-.75Z" />
                    </svg>
                </div>
            </div>

            <div class="w-full relative">
                <textarea class="borderless-input !pl-0 !pr-10" v-model="form.data().description" required />
                <div class="absolute top-2 right-1" :class="{
                    'text-gray-400 cursor-not-allowed': !form.data().description || form.data().description == form.saved().description,
                    'text-blue-500 cursor-pointer ring-2 rounded': form.data().description && form.data().description != form.saved().description,
                }" @click="save('description')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path
                            d="M18 2.25c.2 0 .39.08.53.22l3 3c.14.14.22.33.22.53v15c0 .41-.34.75-.75.75H3a.75.75 0 0 1-.75-.75V3c0-.41.34-.75.75-.75h15ZM17 12H7a1 1 0 0 0-1 1v7.25h12V13a1 1 0 0 0-1-1Zm-.5-8.25h-9V8a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V3.75Zm-2.25.75c.41 0 .75.34.75.75v1.5a.75.75 0 1 1-1.5 0v-1.5c0-.41.34-.75.75-.75Z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center flex-wrap gap-4 w-full">
            <div data-status>
                <div class="text-gray-400 mb-1">Status</div>
                <select
                    class="w-40 block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    v-model="form.data().status">
                    <option value="pendent">Pendente</option>
                    <option value="in-progress">Em progresso</option>
                    <option value="completed">Finalizada</option>
                </select>
            </div>

            <div data-responsible>
                <div class="text-gray-400 mb-1">Responsável</div>
                <select
                    v-model="form.data().responsible_id"
                    class="w-60 block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                </select>
            </div>

            <div :data-due-date="form.saved().due_date">
                <div class="text-gray-400 mb-1">Data de Vencimento</div>
                <div class="relative">
                    <input data-input-due-date type="date" class="borderless-input !p-2 !pr-10 !w-60" required
                        v-model="form.data().due_date" />
                    <div class="absolute top-2 right-1" :class="{
                        'text-gray-400 cursor-not-allowed': !form.data().due_date || form.data().due_date == form.saved().due_date,
                        'text-blue-500 cursor-pointer ring-2 rounded': form.data().due_date && form.data().due_date != form.saved().due_date,
                    }" @click="save('due_date')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                            <path
                                d="M18 2.25c.2 0 .39.08.53.22l3 3c.14.14.22.33.22.53v15c0 .41-.34.75-.75.75H3a.75.75 0 0 1-.75-.75V3c0-.41.34-.75.75-.75h15ZM17 12H7a1 1 0 0 0-1 1v7.25h12V13a1 1 0 0 0-1-1Zm-.5-8.25h-9V8a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V3.75Zm-2.25.75c.41 0 .75.34.75.75v1.5a.75.75 0 1 1-1.5 0v-1.5c0-.41.34-.75.75-.75Z" />
                        </svg>
                    </div>
                </div>
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