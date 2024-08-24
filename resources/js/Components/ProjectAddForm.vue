<script setup lang="ts">
import { onMounted, ref, reactive, inject } from 'vue';
import PrimaryButton from './PrimaryButton.vue';
import type { ProjectGateway } from '@/types/Gateways';

const gateway = inject<ProjectGateway>('projectGateway') as ProjectGateway

const title = ref<HTMLInputElement>()

const form = reactive<ProjectAddFormDTO>({
    title: '',
    description: '',
    dueDate: ''
})

const errors = reactive<Partial<ProjectAddFormDTO>>({})

function validate(): boolean {
    errors.title = ''
    errors.description = ''
    errors.dueDate = ''

    if (!form.title) {
        errors.title = 'Obrigatório'
        return false
    }

    if (!form.description) {
        errors.description = 'Obrigatório'
        return false
    } 

    if (!form.dueDate) {
        errors.dueDate = 'Obrigatório'
        return false
    }

    return true
}

async function submit() {
    errors.title = 'Obrigatório'
    try {
        gateway.add(form)
    } catch (error) {
        // TODO: Show toast
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
                <input data-input-title ref="title" type="text" id="title" v-model="form.title" class="borderless-input"
                    :class="{ 'error': errors.title }" placeholder="Título do projeto..." required />
            </div>

            <div class="mb-4">
                <input data-input-due-date type="date" id="dueDate" v-model="form.dueDate" class="borderless-input"
                    :class="{ 'error': errors.description }" required />
            </div>
        </div>

        <div class="mb-4">
            <textarea data-input-description id="description" rows="4" v-model="form.description"
                class="borderless-input h-16" :class="{ 'error': errors.dueDate }"
                placeholder="Descrição do projeto"></textarea>
        </div>

        <PrimaryButton data-input-submit-button @click="validate() && submit()">
            Adicionar Projeto
        </PrimaryButton>
    </div>
</template>