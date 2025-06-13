<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    punch: Object,
    employees: Array,
});

const punchStatuses = ['IN', 'OUT'];

const form = useForm({
    employee_id: props.punch.employee_id,
    punch_time: props.punch.punch_time.slice(0, 16), // Format for datetime-local input
    punch_status: props.punch.punch_status,
});

const submit = () => {
    form.put(route('punches.update', props.punch.id), {
        onSuccess: () => {}, // Handle success, maybe redirect to index
        onError: (errors) => console.error(errors),
    });
};
</script>

<template>
    <AppLayout title="Edit Punch">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Punch
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee:</label>
                            <select id="employee_id" v-model="form.employee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required disabled>
                                <option value="" disabled>Select Employee</option>
                                <option v-for="employee in employees" :key="employee.id" :value="employee.id">{{ employee.first_name }} {{ employee.last_name }}</option>
                            </select>
                            <div v-if="form.errors.employee_id" class="text-red-500 text-xs mt-1">{{ form.errors.employee_id }}</div>
                        </div>

                        <div class="mb-4">
                            <label for="punch_time" class="block text-sm font-medium text-gray-700">Punch Time:</label>
                            <input type="datetime-local" id="punch_time" v-model="form.punch_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            <div v-if="form.errors.punch_time" class="text-red-500 text-xs mt-1">{{ form.errors.punch_time }}</div>
                        </div>

                        <div class="mb-4">
                            <label for="punch_status" class="block text-sm font-medium text-gray-700">Punch Status:</label>
                            <select id="punch_status" v-model="form.punch_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="" disabled>Select Status</option>
                                <option v-for="status in punchStatuses" :key="status" :value="status">{{ status }}</option>
                            </select>
                            <div v-if="form.errors.punch_status" class="text-red-500 text-xs mt-1">{{ form.errors.punch_status }}</div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" :disabled="form.processing" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Punch
                            </button>
                            <Link :href="route('punches.index')" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
