<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const publicHolidays = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchPublicHolidays = async () => {
    try {
        const response = await axios.get('/api/holidays');
        publicHolidays.value = response.data;
    } catch (err) {
        error.value = 'Failed to fetch public holidays.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchPublicHolidays);

const deleteHoliday = async (id) => {
    if (confirm('Are you sure you want to delete this holiday?')) {
        try {
            await axios.delete(`/api/holidays/${id}`);
            fetchPublicHolidays(); // Refresh the list
        } catch (err) {
            error.value = 'Failed to delete public holiday.';
            console.error(err);
        }
    }
};
</script>

<template>
    <AppLayout title="Public Holidays">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Public Holidays
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div v-if="loading">Loading public holidays...</div>
                    <div v-else-if="error" class="text-red-500">{{ error }}</div>
                    <div v-else>
                        <div class="flex justify-end mb-4">
                            <Link :href="route('holidays.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add New Holiday
                            </Link>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Holiday Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="holiday in publicHolidays" :key="holiday.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ holiday.holiday_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ new Date(holiday.holiday_date).toLocaleDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('holidays.edit', holiday.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                        <button @click="deleteHoliday(holiday.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
