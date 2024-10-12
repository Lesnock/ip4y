<script setup lang="ts">
import type { TaskGateway } from '@/types/gateways.d.ts';
import { onMounted, ref, inject } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Loading from '@/Components/Loading.vue';
import { Props, useLogic } from './logic';

const props = defineProps<Props>()

const gateway = inject('taskGateway') as TaskGateway
const title = ref<HTMLInputElement>()
const emit = defineEmits(['add'])
const {
    loading,
    form,
    errors,
    submit
} = useLogic(props, emit, gateway)

onMounted(() => {
    title.value?.focus()
})
</script>

<template>
    <div class="relative p-4" data-project-add-form>
        <Loading v-if="loading" />

        <div class="grid gap-6 md:grid-cols-2">
            <div class="mb-4">
                <div class="text-gray-400 mb-1">Título</div>
                <input data-input-title ref="title" type="text" id="title" v-model="form.title" class="borderless-input"
                    :class="{ 'error': errors.title }" placeholder="Título da tarefa..." required />
                <div class="text-red-500" v-if="errors.title">{{ errors.title }}</div>
            </div>

            <div class="mb-4">
                <div class="text-gray-400 mb-1">Data</div>
                <input data-input-due-date type="date" v-model="form.due_date" class="borderless-input"
                    :class="{ 'error': errors.due_date }" required />
                <div class="text-red-500" v-if="errors.due_date">{{ errors.due_date }}</div>
            </div>
        </div>

        <div class="mb-4">
            <div class="text-gray-400 mb-1">Descrição</div>
            <textarea data-input-description id="description" rows="4" v-model="form.description"
                class="borderless-input h-16" :class="{ 'error': errors.description }"
                placeholder="Descrição da tarefa..."></textarea>
                <div class="text-red-500" v-if="errors.description">{{ errors.description }}</div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div class="mb-4">
                <div class="text-gray-400 mb-1">Status</div>
                <select class="borderless-input" :class="{ 'error': errors.status }" placeholder="Status"
                    v-model="form.status">
                    <option value="pendent">Pendente</option>
                    <option value="in-progress">Em progresso</option>
                    <option value="completed">Finalizada</option>
                </select>
                <div class="text-red-500" v-if="errors.status">{{ errors.status }}</div>
            </div>

            <div class="mb-4">
                <div class="text-gray-400 mb-1">Responsável</div>
                <select class="borderless-input" :class="{ 'error': errors.responsible_id }" placeholder="Responsável"
                    v-model="form.responsible_id">
                    <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                </select>
                <div class="text-red-500" v-if="errors.responsible_id">{{ errors.responsible_id }}</div>
            </div>
        </div>

        <PrimaryButton class="flex items-center gap-1" data-input-submit-button @click="submit">
            Adicionar Tarefa
        </PrimaryButton>
    </div>
</template>