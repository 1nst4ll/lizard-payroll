<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const punches = ref([]);
const employees = ref([]);
const loading = ref(true);
const error = ref(null);

const filters = ref({
    employeeId: '',
    startDate: '',
    endDate: '',
});

const punchStatuses = ['IN', 'OUT'];

const fetchEmployees = async () => {
    try {
        const response = await axios.get('/api/employees');
        employees.value = response.data;
    } catch (err) {
        console.error('Failed to fetch employees:', err);
    }
};

const fetchPunches = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await axios.get('/api/punches', { params: filters.value });
        punches.value = response.data;
    } catch (err) {
        error.value = 'Failed to fetch punches.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchEmployees();
    fetchPunches();
});

watch(filters.value, fetchPunches, { deep: true });

const deletePunch = async (id) => {
    if (confirm('Are you sure you want to delete this punch?')) {
        try {
            await axios.delete(`/api/punches/${id}`);
            fetchPunches(); // Refresh the list
        } catch (err) {
            error.value = 'Failed to delete punch.';
            console.error(err);
        }
    }
};

const importPunches = async () => {
    // Placeholder for file import logic
    alert('Import Punches functionality is a placeholder.');
    // In a real scenario, you'd open a modal, handle file upload, etc.
};

const autofixPunches = async () => {
    // Placeholder for autofix logic
    alert('Autofix Punches functionality is a placeholder.');
    // In a real scenario, you'd confirm dates/employees and send an API request
};
</script>

<template>
    <AppLayout title="Punch Management">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Punch Management
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex space-x-4">
                            <div>
                                <label for="employeeFilter" class="block text-sm font-medium text-gray-700">Employee:</label>
                                <select id="employeeFilter" v-model="filters.employeeId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">All Employees</option>
                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">{{ employee.first_name }} {{ employee.last_name }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date:</label>
                                <input type="date" id="startDate" v-model="filters.startDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date:</label>
                                <input type="date" id="endDate" v-model="filters.endDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button @click="importPunches" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Import Punches
                            </button>
                            <button @click="autofixPunches" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Autofix Punches
                            </button>
                            <Link :href="route('punches.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Manual Punch
                            </Link>
                        </div>
                    </div>
                    <div v-if="loading">Loading punches...</div>
                    <div v-else-if="error" class="text-red-500">{{ error }}</div>
                    <div v-else>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Employee
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Punch Time
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Manual Edit
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="punch in punches" :key="punch.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ punch.employee ? punch.employee.first_name + ' ' + punch.employee.last_name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ new Date(punch.punch_time).toLocaleString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ punch.punch_status }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ punch.is_manual_edit ? 'Yes' : 'No' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('punches.edit', punch.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                        <button @click="deletePunch(punch.id)" class="text-red-600 hover:text-red-900">Delete</button>
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
