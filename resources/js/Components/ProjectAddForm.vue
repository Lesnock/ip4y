<script setup lang="ts">
import type { ProjectGateway } from '@/types/gateways.d.ts';
import { onMounted, ref, inject } from 'vue';
import toastr from 'toastr'
import PrimaryButton from './PrimaryButton.vue';
import { ProjectAddForm } from '@/Forms/ProjectAddForm';

const gateway = inject('projectGateway') as ProjectGateway
const title = ref<HTMLInputElement>()
const emit = defineEmits(['add'])
const form = new ProjectAddForm();

async function submit() {
    if (!form.validate()) {
        toastr.error('Dados inválidos. Preencha todos os campos')
        return
    }
    try {
        const project = await gateway.add(form.data())
        toastr.success('Projeto adicionado com sucesso!')
        emit('add', project)
    } catch (error) {
        if (error instanceof Error) {
            toastr.error(`Erro ao salvar projeto: ${error.message}`)
        }
    }
}

onMounted(() => {
    title.value?.focus()
})
</script>

<template>
    <div data-project-add-form>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="mb-4">
                <input data-input-title ref="title" type="text" id="title" v-model="form.data().title" class="borderless-input"
                    :class="{ 'error': form.errors().title }" placeholder="Título do projeto..." required />
            </div>

            <div class="mb-4">
                <input data-input-due-date type="date" id="dueDate" v-model="form.data().dueDate" class="borderless-input"
                    :class="{ 'error': form.errors().dueDate }" required />
            </div>
        </div>

        <div class="mb-4">
            <textarea data-input-description id="description" rows="4" v-model="form.data().description"
                class="borderless-input h-16" :class="{ 'error': form.errors().description }"
                placeholder="Descrição do projeto"></textarea>
        </div>

        <PrimaryButton data-input-submit-button @click="submit">
            Adicionar Projeto
        </PrimaryButton>
    </div>
</template>