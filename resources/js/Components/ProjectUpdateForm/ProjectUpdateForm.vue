<script setup lang="ts">
import type { ProjectGateway } from '@/types/gateways.d.ts';
import { inject } from 'vue';
import Loading from '@/Components/Loading.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Props, useLogic } from './logic';

const props = defineProps<Props>()

const gateway = inject('projectGateway') as ProjectGateway

const {
    loading,
    form,
    errors,
    submit
} = useLogic(props, gateway)
</script>

<template>
    <div class="relative p-4" data-project-add-form>
        <Loading v-if="loading" />

        <div class="grid gap-6 md:grid-cols-2">
            <div class="mb-4">
                <div class="text-gray-400 mb-1">Título</div>
                <input data-input-title type="text" id="title" v-model="form.title" class="borderless-input !pr-10"
                    placeholder="Título do projeto..." required />
                <div class="text-red-500" v-if="errors.title">{{ errors.title }}</div>
            </div>

            <div class="mb-4">
                <div class="text-gray-400 mb-1">Data</div>
                <input data-input-due-date type="date" v-model="form.due_date" class="borderless-input !pr-10" required />
            </div>
        </div>

        <div class="mb-4">
            <div class="text-gray-400 mb-1">Descrição</div>
            <textarea data-input-description id="description" rows="4" v-model="form.description"
                class="borderless-input h-16 !pr-10"
                placeholder="Descrição do projeto"></textarea>
        </div>

        <div class="mt-8">
            <PrimaryButton class="flex items-center gap-1" data-input-submit-button @click="submit">
                Salvar Informações do Projeto
            </PrimaryButton>
        </div>
    </div>
</template>