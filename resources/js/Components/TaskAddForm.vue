<script setup lang="ts">
import type { TaskGateway } from '@/types/gateways.d.ts';
import { onMounted, ref, inject } from 'vue';
import toastr from 'toastr'
import { TaskAddForm } from '@/Forms/TaskAddForm';
import { delay } from '@/helpers';
import PrimaryButton from './PrimaryButton.vue';
import Loading from './Loading.vue';
import { Project, User } from '@/types';

const props = defineProps<{
    project: Project,
    users: User[]
}>()

const gateway = inject('taskGateway') as TaskGateway
const title = ref<HTMLInputElement>()
const emit = defineEmits(['add'])
const form = new TaskAddForm(props.project.id, gateway);
const isLoading = ref(false)

async function submit() {
    isLoading.value = true
    await delay()

    if (!form.validate()) {
        isLoading.value = false
        toastr.error('Dados inválidos. Preencha todos os campos'); return
    }

    const { error, task } = await form.submit()

    isLoading.value = false

    if (error) {
        toastr.error(error)
        return
    }

    form.clear()
    toastr.success('Tarefa adicionada com sucesso!')
    emit('add', task)
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
                    class="borderless-input" :class="{ 'error': form.errors().title }" placeholder="Título da tarefa..."
                    required />
            </div>

            <div class="mb-4">
                <input data-input-due-date type="date" v-model="form.data().due_date" class="borderless-input"
                    :class="{ 'error': form.errors().due_date }" required />
            </div>
        </div>

        <div class="mb-4">
            <textarea data-input-description id="description" rows="4" v-model="form.data().description"
                class="borderless-input h-16" :class="{ 'error': form.errors().description }"
                placeholder="Descrição da tarefa..."></textarea>
        </div>

        <div class="flex mb-4 gap-4">
            <select class="borderless-input" placeholder="Status" v-model="form.data().status">
                <option value="pendent">Pendente</option>
                <option value="in-progress">Em progresso</option>
                <option value="completed">Finalizada</option>
            </select>

            <select class="borderless-input" placeholder="Responsável" v-model="form.data().responsible_id">
                <option v-for="user in users" :value="user.id">{{ user.name }}</option>
            </select>
        </div>

        <PrimaryButton class="flex items-center gap-1" data-input-submit-button @click="submit">
            Adicionar Tarefa
        </PrimaryButton>
    </div>
</template>