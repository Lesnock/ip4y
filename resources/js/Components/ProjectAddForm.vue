<script setup lang="ts">
import { onMounted, ref, reactive, inject, watch } from 'vue';
import PrimaryButton from './PrimaryButton.vue';
import type { ProjectGateway } from '@/types/Gateways';
import { object, string } from 'yup'
import { validate } from '@/validation';
const gateway = inject<ProjectGateway>('projectGateway') as ProjectGateway
const title = ref<HTMLInputElement>()

const emit = defineEmits(['add'])

const form = reactive<ProjectAddFormDTO>({
    title: '',
    description: '',
    dueDate: ''
})

const schema = object({
    title: string().required(),
    description: string().required(),
    dueDate: string().required(),
})

const errors = reactive<Partial<ProjectAddFormDTO>>({})

async function submit() {
    const validation = validate(schema, form)
    if (!validation.status) {
        for (const prop in validation.errors) {
            errors[prop as keyof ProjectAddFormDTO] = validation.errors[prop]
        }
        return
    }

    try {
        const project = await gateway.add(form)
        emit('add', project)
    } catch (error) {
        // TODO: Show toast
    }
}

onMounted(() => {
    title.value?.focus()
})

// Clear errors
watch(() => form.title, () => errors.title = '')
watch(() => form.description, () => errors.description = '')
watch(() => form.dueDate, () => errors.dueDate = '')
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
                    :class="{ 'error': errors.dueDate }" required />
            </div>
        </div>

        <div class="mb-4">
            <textarea data-input-description id="description" rows="4" v-model="form.description"
                class="borderless-input h-16" :class="{ 'error': errors.description }"
                placeholder="Descrição do projeto"></textarea>
        </div>

        <PrimaryButton data-input-submit-button @click="submit">
            Adicionar Projeto
        </PrimaryButton>
    </div>
</template>