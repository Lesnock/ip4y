<script setup lang="ts">
import { inject, ref } from 'vue';
import toastr from 'toastr';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Task } from '@/types';
import { TaskGateway } from '@/types/gateways';
import { router } from '@inertiajs/vue3';
import { delay } from '@/helpers';

const props = defineProps<{
    task: Task
}>()

const emit = defineEmits(['delete'])
const gateway = inject('taskGateway') as TaskGateway
const confirming = ref(false);
const isLoading = ref(false)

const deleteTask = async () => {
    isLoading.value = true
    await delay()
    const error = await gateway.delete(props.task.id)
    if (error) {
        isLoading.value = false
        toastr.error(error); 
        return
    }
    toastr.success('Tarefa excluída com sucesso!')
    isLoading.value = false
    emit('delete')
};

const closeModal = () => {
    confirming.value = false;
};
</script>

<template>
    <section class="space-y-6">
        <DangerButton @click="confirming = true">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
        </DangerButton>

        <Modal :show="confirming" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Você tem certeza que deseja excluir esta tarefa?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Uma vez excluído, a tarefa será permanentemente excluída.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                    <DangerButton class="ms-3" :class="{ 'opacity-25': isLoading }" :disabled="isLoading"
                        @click="deleteTask">
                        Excluir tarefa
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
