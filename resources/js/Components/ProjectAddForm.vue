<script setup lang="ts">
import { onMounted, ref, reactive } from 'vue';
import PrimaryButton from './PrimaryButton.vue';

const title = ref<HTMLInputElement>()

const form = reactive({
    title: '',
    description: '',
    dueDate: ''
})

onMounted(() => {
    title.value?.focus()
})
</script>

<template>
    <div data-project-add-form>
        <div class="grid gap-6 md:grid-cols-2">
            <div class="mb-4">
                <input ref="title" type="text" id="title" v-model="form.title" class="borderless-input"
                    placeholder="Título do projeto..." required />
            </div>

            <div class="mb-4">
                <input type="date" id="dueDate" v-model="form.dueDate" class="borderless-input" required />
            </div>
        </div>

        <div class="mb-4">
            <textarea id="description" rows="4" v-model="form.description" class="borderless-input h-16"
                placeholder="Descrição do projeto"></textarea>
        </div>

        <PrimaryButton>
            Adicionar Projeto
        </PrimaryButton>
    </div>
</template>