<script setup lang="ts">
import ProjectForm from '@/Components/ProjectForm.vue';
import ProjectList from '@/Components/ProjectList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex align-items justify-between items-center">
                <span class="mb-4">Projeto #1</span>

            </h2>
            <ProjectForm />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto">
                <span class="text-xl text-gray-800 dark:text-gray-200 mb-2 block">Tarefas</span>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:px-6 lg:px-8 p-6 text-gray-900 dark:text-gray-100">
                    <ProjectForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
