<script setup lang="ts">
import type { ProjectGateway } from '@/types/gateways.d.ts';
import { onMounted, ref, inject } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Loading from '@/Components/Loading.vue';
import { useLogic } from './logic';

const gateway = inject('projectGateway') as ProjectGateway
const title = ref<HTMLInputElement>()
const emit = defineEmits(['add'])

const {
    loading,
    form,
    errors,
    submit
} = useLogic(emit, gateway)

onMounted(() => {
    title.value?.focus()
})
</script>

<template>
    <div class="relative p-4 bg-transparent" data-project-add-form>
        <Loading v-if="loading" />

        <div class="grid gap-6 md:grid-cols-2">
            <div class="mb-4">
                <div class="text-gray-400 mb-1">Título</div>
                <input data-input-title ref="title" type="text" id="title" v-model="form.title"
                    class="borderless-input" :class="{ 'error': errors.title }"
                    placeholder="Título do projeto..." required />
                <div class="text-red-500" v-if="errors.title">{{ errors.title }}</div>
            </div>

            <div class="mb-4">
                <div class="text-gray-400 mb-1">Data</div>
                <input data-input-due-date type="date" v-model="form.due_date"
                    class="borderless-input" :class="{ 'error': errors.due_date }" required />
                <div class="text-red-500" v-if="errors.due_date">{{ errors.due_date }}</div>
            </div>
        </div>

        <div class="mb-4">
            <div class="text-gray-400 mb-1">Descrição</div>
            <textarea data-input-description id="description" rows="4" v-model="form.description"
                class="borderless-input h-16" :class="{ 'error': errors.description }"
                placeholder="Descrição do projeto"></textarea>
            <div class="text-red-500" v-if="errors.description">{{ errors.description }}</div>
        </div>

        <PrimaryButton class="flex items-center gap-1" data-input-submit-button @click="submit">
            Adicionar Projeto
        </PrimaryButton>
    </div>
</template>