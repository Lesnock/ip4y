<script setup lang="ts">
import type { Task as TaskType, User } from '@/types';
import { router } from '@inertiajs/vue3'
import Task from './Task.vue';

type Props = {
    tasks: TaskType[],
    users: User[]
}

const props = withDefaults(defineProps<Props>(), {
    tasks: () => []
})

function onDeleteTask(index: number) {
    props.tasks.splice(index, 1)
}
</script>

<template>
    <TransitionGroup name="list" tag="ul">
        <Task v-for="(task, index) in tasks" :key="task.id" :task="task" :users="users" data-task @delete="onDeleteTask(index)">
        </Task>
    </TransitionGroup>
</template>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>