<script setup lang="ts">
import type { ProjectGateway } from '@/types/gateways.d.ts';
import { onMounted, ref, inject } from 'vue';
import toastr from 'toastr'
import { ProjectAddForm } from '@/Forms/ProjectAddForm';
import { delay } from '@/helpers';
import PrimaryButton from './PrimaryButton.vue';
import Loading from './Loading.vue';

const gateway = inject('projectGateway') as ProjectGateway
const title = ref<HTMLInputElement>()
const emit = defineEmits(['add'])
const form = new ProjectAddForm(gateway);
const isLoading = ref(false)

async function submit() {
    isLoading.value = true
    await delay()

    if (!form.validate()) {
        isLoading.value = false
        toastr.error('Dados inválidos. Preencha todos os campos'); return
    }

    const { error, project } = await form.submit()

    isLoading.value = false

    if (error) {
        toastr.error(error)
        return
    }

    form.clear()
    toastr.success('Projeto adicionado com sucesso!')
    emit('add', project)
}

onMounted(() => {
    title.value?.focus()
})
</script>

<template>
    <div class="relative p-4" data-project-add-form>
        <Loading v-if="isLoading" />

        <div class="grid gap-6 md:grid-cols-2">
            <div class="mb-4">
                <input data-input-title ref="title" type="text" id="title" v-model="form.data().title"
                    class="borderless-input" :class="{ 'error': form.errors().title }"
                    placeholder="Título do projeto..." required />
            </div>

            <div class="mb-4">
                <input data-input-due-date type="date" id="dueDate" v-model="form.data().dueDate"
                    class="borderless-input" :class="{ 'error': form.errors().dueDate }" required />
            </div>
        </div>

        <div class="mb-4">
            <textarea data-input-description id="description" rows="4" v-model="form.data().description"
                class="borderless-input h-16" :class="{ 'error': form.errors().description }"
                placeholder="Descrição do projeto"></textarea>
        </div>

        <PrimaryButton class="flex items-center gap-1" data-input-submit-button @click="submit">
            Adicionar Projeto
        </PrimaryButton>
    </div>
</template>