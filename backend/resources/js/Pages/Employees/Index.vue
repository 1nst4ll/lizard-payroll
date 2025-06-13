<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const employees = ref([]);
const departments = ref([]);
const jobRoles = ref([]);
const loading = ref(true);
const error = ref(null);

const filters = ref({
    departmentId: '',
    jobRoleId: '',
    status: '',
    search: '',
});

const employeeStatuses = [
    'Work Permit Holder',
    'Resident',
    'Citizen',
    'Belonger'
];

const fetchDepartments = async () => {
    try {
        const response = await axios.get('/api/departments');
        departments.value = response.data;
    } catch (err) {
        console.error('Failed to fetch departments:', err);
    }
};

const fetchJobRoles = async () => {
    try {
        const response = await axios.get('/api/departments/' + (filters.value.departmentId || 'all') + '/job-roles');
        jobRoles.value = response.data;
    } catch (err) {
        console.error('Failed to fetch job roles:', err);
    }
};

const fetchEmployees = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await axios.get('/api/employees', { params: filters.value });
        employees.value = response.data;
    } catch (err) {
        error.value = 'Failed to fetch employees.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchDepartments();
    fetchJobRoles();
    fetchEmployees();
});

watch(() => filters.value.departmentId, (newVal, oldVal) => {
    if (newVal !== oldVal) {
        filters.value.jobRoleId = ''; // Reset job role when department changes
        fetchJobRoles();
        fetchEmployees();
    }
});

watch(() => filters.value.jobRoleId, fetchEmployees);
watch(() => filters.value.status, fetchEmployees);
watch(() => filters.value.search, fetchEmployees);


const deleteEmployee = async (id) => {
    if (confirm('Are you sure you want to delete this employee?')) {
        try {
            await axios.delete(`/api/employees/${id}`);
            fetchEmployees(); // Refresh the list
        } catch (err) {
            error.value = 'Failed to delete employee.';
            console.error(err);
        }
    }
};
</script>

<template>
    <AppLayout title="Employees">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Employees
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex space-x-4">
                            <div>
                                <label for="departmentFilter" class="block text-sm font-medium text-gray-700">Department:</label>
                                <select id="departmentFilter" v-model="filters.departmentId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">All</option>
                                    <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="jobRoleFilter" class="block text-sm font-medium text-gray-700">Job Role:</label>
                                <select id="jobRoleFilter" v-model="filters.jobRoleId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">All</option>
                                    <option v-for="jobRole in jobRoles" :key="jobRole.id" :value="jobRole.id">{{ jobRole.title }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="statusFilter" class="block text-sm font-medium text-gray-700">Status:</label>
                                <select id="statusFilter" v-model="filters.status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">All</option>
                                    <option v-for="status in employeeStatuses" :key="status" :value="status">{{ status }}</option>
                                </select>
                            </div>
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search:</label>
                                <input type="text" id="search" v-model="filters.search" placeholder="Name or Employee ID" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        <Link :href="route('employees.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Employee
                        </Link>
                    </div>
                    <div v-if="loading">Loading employees...</div>
                    <div v-else-if="error" class="text-red-500">{{ error }}</div>
                    <div v-else>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Employee ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Job Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="employee in employees" :key="employee.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ employee.employee_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ employee.first_name }} {{ employee.last_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ employee.department ? employee.department.name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ employee.job_role ? employee.job_role.title : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ employee.status }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('employees.edit', employee.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                        <button @click="deleteEmployee(employee.id)" class="text-red-600 hover:text-red-900">Delete</button>
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
