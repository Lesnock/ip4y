<script setup lang="ts">
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
                <span>Novo Projeto</span>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 sm:px-6 lg:px-8 p-6 text-gray-900 dark:text-gray-100">
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
