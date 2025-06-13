<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const totalEmployees = ref(0);
const upcomingHolidays = ref([]);
const pendingPunches = ref(0);
const estimatedPayrollCost = ref('N/A');
const loading = ref(true);
const error = ref(null);

const fetchDashboardData = async () => {
    try {
        // Placeholder for API calls to fetch dashboard data
        // For now, using dummy data
        totalEmployees.value = 125;
        upcomingHolidays.value = [
            { name: 'King\'s Birthday', date: 'June 23, 2025' },
            { name: 'Emancipation Day', date: 'August 1, 2025' },
        ];
        pendingPunches.value = 7;
        estimatedPayrollCost.value = '$15,000.00';
    } catch (err) {
        error.value = 'Failed to fetch dashboard data.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchDashboardData);
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div v-if="loading">Loading dashboard data...</div>
                    <div v-else-if="error" class="text-red-500">{{ error }}</div>
                    <div v-else>
                        <h3 class="text-lg font-semibold mb-4">Payroll Overview</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-100 p-4 rounded-lg shadow">
                                <p class="text-sm text-gray-600">Total Employees</p>
                                <p class="text-2xl font-bold text-blue-800">{{ totalEmployees }}</p>
                            </div>
                            <div class="bg-green-100 p-4 rounded-lg shadow">
                                <p class="text-sm text-gray-600">Estimated Payroll Cost (Current Period)</p>
                                <p class="text-2xl font-bold text-green-800">{{ estimatedPayrollCost }}</p>
                            </div>
                            <div class="bg-yellow-100 p-4 rounded-lg shadow">
                                <p class="text-sm text-gray-600">Pending Punches to Review</p>
                                <p class="text-2xl font-bold text-yellow-800">{{ pendingPunches }}</p>
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold mb-4">Upcoming Public Holidays</h3>
                        <ul v-if="upcomingHolidays.length > 0" class="list-disc list-inside">
                            <li v-for="holiday in upcomingHolidays" :key="holiday.name">
                                {{ holiday.name }} ({{ holiday.date }})
                            </li>
                        </ul>
                        <p v-else class="text-gray-600">No upcoming public holidays.</p>

                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                            <div class="flex space-x-4">
                                <Link :href="route('punches.index')" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                    Review Punches
                                </Link>
                                <Link :href="route('reports.hours')" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                    Generate Reports
                                </Link>
                                <!-- Add more quick actions as needed -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
