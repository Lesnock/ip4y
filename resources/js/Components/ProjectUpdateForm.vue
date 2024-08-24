<script setup lang="ts">
import type { ProjectGateway } from '@/types/gateways.d.ts';
import { onMounted, ref, inject } from 'vue';
import toastr from 'toastr'
import { ProjectAddForm } from '@/Forms/ProjectAddForm';
import { delay } from '@/helpers';
import Loading from './Loading.vue';
import { Project } from '@/types';

type Props = {
    project: Project
}

const props = defineProps<Props>()

const title = ref(props.project.title)
const description = ref(props.project.description)
const dueDate = ref(new Date(props.project.due_date).toISOString().slice(0, 10)) // Remove time

const gateway = inject('projectGateway') as ProjectGateway
const emit = defineEmits(['add'])
const form = new ProjectAddForm(gateway);
const isLoading = ref(false)

async function submit() {
    
}

async function save(field: string, value: string) {
    const error = await gateway.patch({ [field]: value })
    if (error) {
        toastr.error(error); return;
    }
    toastr.success('Campo atualizado com sucesso!')
}

onMounted(() => {
    // title.value?.focus()
})
</script>

<template>
    <div class="relative p-4" data-project-add-form>
        <Loading v-if="isLoading" />

        <div class="grid gap-6 md:grid-cols-2">
            <div class="mb-4">
                <input data-input-title type="text" id="title" v-model="title"
                    class="borderless-input" :class="{ 'error': form.errors().title }"
                    placeholder="Título do projeto..." required @blur="save('title', title)" />
            </div>

            <div class="mb-4">
                <input data-input-due-date type="date" v-model="dueDate"
                    class="borderless-input" :class="{ 'error': form.errors().due_date }" required />
            </div>
        </div>

        <div>
            <textarea data-input-description id="description" rows="4" v-model="description"
                class="borderless-input h-16" :class="{ 'error': form.errors().description }"
                placeholder="Descrição do projeto"></textarea>
        </div>
    </div>
</template>