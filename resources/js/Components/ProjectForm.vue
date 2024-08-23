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
    <div>
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Título
                </label>
                <input ref="title" type="text" id="title" v-model="form.title"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Título do projeto..." required />
            </div>

            <!-- Due Date -->
            <div class="mb-6">
                <label for="dueDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Data de Conclusão
                </label>
                <input type="date" id="dueDate" v-model="form.dueDate"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required />
            </div>

        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Descrição
            </label>
            <textarea id="description" rows="4" v-model="form.description"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Descrição do projeto"></textarea>
        </div>


        <PrimaryButton>
            Salvar
        </PrimaryButton>
    </div>
</template>