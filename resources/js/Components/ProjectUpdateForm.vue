<script setup lang="ts">
import type { ProjectGateway } from '@/types/gateways.d.ts';
import { onMounted, ref, inject } from 'vue';
import toastr from 'toastr'
import { ProjectAddForm } from '@/Forms/ProjectAddForm';
import { delay } from '@/helpers';
import Loading from './Loading.vue';
import PrimaryButton from './PrimaryButton.vue'
import { Project } from '@/types';
import { ProjectUpdateForm } from '@/Forms/ProjectUpdateForm';
import { ProjectUpdateFormDTO } from '@/types/dto';

type Props = {
    project: Project
}
const props = defineProps<Props>()

const gateway = inject('projectGateway') as ProjectGateway
const emit = defineEmits(['add'])
const form = new ProjectUpdateForm(gateway);
const isLoading = ref(false)

async function submit() {
    
}

async function save(field: keyof ProjectUpdateFormDTO) {
    const error = await form.patch(props.project.id, field)
    if (error) {
        toastr.error(`Erro ao salvar o campo: ${error}`); return;
    }
    toastr.success('Campo salvo com sucesso!')
}

onMounted(() => {
    if (props.project) {
        const formData = {
            title: props.project.title,
            description: props.project.description,
            due_date: new Date(props.project.due_date).toISOString().slice(0, 10),
        }
        form.fill(formData)
    }
})
</script>

<template>
    <div class="relative p-4" data-project-add-form>
        <Loading v-if="isLoading" />

        <div class="grid gap-6 md:grid-cols-2">
            <div class="mb-4 relative">
                <input data-input-title type="text" id="title" v-model="form.data().title" class="borderless-input !pr-10"
                    placeholder="Título do projeto..." required />

                <div 
                    class="absolute top-2 right-1" 
                    :class="{
                        'text-gray-400 cursor-not-allowed': !form.data().title || form.data().title == form.saved().title,
                        'text-blue-500 cursor-pointer ring-2 rounded': form.data().title && form.data().title != form.saved().title,
                    }"
                    @click="save('title')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path
                            d="M18 2.25c.2 0 .39.08.53.22l3 3c.14.14.22.33.22.53v15c0 .41-.34.75-.75.75H3a.75.75 0 0 1-.75-.75V3c0-.41.34-.75.75-.75h15ZM17 12H7a1 1 0 0 0-1 1v7.25h12V13a1 1 0 0 0-1-1Zm-.5-8.25h-9V8a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V3.75Zm-2.25.75c.41 0 .75.34.75.75v1.5a.75.75 0 1 1-1.5 0v-1.5c0-.41.34-.75.75-.75Z" />
                    </svg>
                </div>
            </div>

            <div class="mb-4 relative">
                <input data-input-due-date type="date" v-model="form.data().due_date" class="borderless-input !pr-10" required />

                <div 
                    class="absolute top-2 right-1" 
                    :class="{
                        'text-gray-400 cursor-not-allowed': !form.data().due_date || form.data().due_date == form.saved().due_date,
                        'text-blue-500 cursor-pointer ring-2 rounded': form.data().due_date && form.data().due_date != form.saved().due_date,
                    }"
                    @click="save('due_date')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path
                            d="M18 2.25c.2 0 .39.08.53.22l3 3c.14.14.22.33.22.53v15c0 .41-.34.75-.75.75H3a.75.75 0 0 1-.75-.75V3c0-.41.34-.75.75-.75h15ZM17 12H7a1 1 0 0 0-1 1v7.25h12V13a1 1 0 0 0-1-1Zm-.5-8.25h-9V8a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V3.75Zm-2.25.75c.41 0 .75.34.75.75v1.5a.75.75 0 1 1-1.5 0v-1.5c0-.41.34-.75.75-.75Z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="mb-4 relative">
            <textarea data-input-description id="description" rows="4" v-model="form.data().description"
                class="borderless-input h-16 !pr-10"
                placeholder="Descrição do projeto"></textarea>

                <div 
                    class="absolute top-2 right-1" 
                    :class="{
                        'text-gray-400 cursor-not-allowed': !form.data().description || form.data().description == form.saved().description,
                        'text-blue-500 cursor-pointer ring-2 rounded': form.data().description && form.data().description != form.saved().description,
                    }"
                    @click="save('description')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                    <path
                        d="M18 2.25c.2 0 .39.08.53.22l3 3c.14.14.22.33.22.53v15c0 .41-.34.75-.75.75H3a.75.75 0 0 1-.75-.75V3c0-.41.34-.75.75-.75h15ZM17 12H7a1 1 0 0 0-1 1v7.25h12V13a1 1 0 0 0-1-1Zm-.5-8.25h-9V8a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V3.75Zm-2.25.75c.41 0 .75.34.75.75v1.5a.75.75 0 1 1-1.5 0v-1.5c0-.41.34-.75.75-.75Z" />
                </svg>
            </div>
        </div>
    </div>
</template>