<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const form = useForm({
    holiday_name: '',
    holiday_date: '',
});

const submit = () => {
    form.post(route('holidays.store'), {
        onSuccess: () => form.reset(),
        onError: (errors) => console.error(errors),
    });
};
</script>

<template>
    <AppLayout title="Add Public Holiday">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add Public Holiday
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="holiday_name" class="block text-gray-700 text-sm font-bold mb-2">Holiday Name:</label>
                            <input type="text" id="holiday_name" v-model="form.holiday_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <div v-if="form.errors.holiday_name" class="text-red-500 text-xs mt-1">{{ form.errors.holiday_name }}</div>
                        </div>

                        <div class="mb-4">
                            <label for="holiday_date" class="block text-gray-700 text-sm font-bold mb-2">Holiday Date:</label>
                            <input type="date" id="holiday_date" v-model="form.holiday_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <div v-if="form.errors.holiday_date" class="text-red-500 text-xs mt-1">{{ form.errors.holiday_date }}</div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" :disabled="form.processing" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Add Holiday
                            </button>
                            <Link :href="route('holidays.index')" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
