<script setup lang="ts">
import { inject, ref } from 'vue';
import toastr from 'toastr';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Project } from '@/types';
import { ProjectGateway } from '@/types/gateways';
import { router } from '@inertiajs/vue3';
import { delay } from '@/helpers';

const props = defineProps<{
    project: Project
}>()

const gateway = inject('projectGateway') as ProjectGateway
const confirmingProjectDeletion = ref(false);
const isLoading = ref(false)

const deleteProject = async () => {
    isLoading.value = true
    await delay()
    const error = await gateway.delete(props.project.id)
    if (error) {
        isLoading.value = false
        toastr.error(error); 
        return
    }
    toastr.success('Projeto excluído com sucesso!')
    router.visit(route('projects.index'))
    isLoading.value = false
};

const closeModal = () => {
    confirmingProjectDeletion.value = false;
};
</script>

<template>
    <section class="space-y-6">
        <DangerButton @click="confirmingProjectDeletion = true">Excluir projeto</DangerButton>

        <Modal :show="confirmingProjectDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Você tem certeza que deseja excluir este projeto?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Uma vez excluído, todas as configurações e tarefas deste projeto serão permanentemente excluídas.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': isLoading }"
                        :disabled="isLoading"
                        @click="deleteProject"
                    >
                        Excluir projeto
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
