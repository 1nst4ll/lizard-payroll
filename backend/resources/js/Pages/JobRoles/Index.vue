<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const jobRoles = ref([]);
const departments = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedDepartment = ref('');

const fetchDepartments = async () => {
    try {
        const response = await axios.get('/api/departments');
        departments.value = response.data;
    } catch (err) {
        console.error('Failed to fetch departments:', err);
    }
};

const fetchJobRoles = async () => {
    loading.value = true;
    error.value = null;
    try {
        const params = selectedDepartment.value ? { departmentId: selectedDepartment.value } : {};
        const response = await axios.get('/api/departments/' + (selectedDepartment.value || 'all') + '/job-roles', { params });
        jobRoles.value = response.data;
    } catch (err) {
        error.value = 'Failed to fetch job roles.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchDepartments();
    fetchJobRoles();
});

const deleteJobRole = async (id) => {
    if (confirm('Are you sure you want to delete this job role?')) {
        try {
            await axios.delete(`/api/job-roles/${id}`);
            fetchJobRoles(); // Refresh the list
        } catch (err) {
            error.value = 'Failed to delete job role.';
            console.error(err);
        }
    }
};
</script>

<template>
    <AppLayout title="Job Roles">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Job Roles
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <label for="departmentFilter" class="block text-sm font-medium text-gray-700">Filter by Department:</label>
                            <select id="departmentFilter" v-model="selectedDepartment" @change="fetchJobRoles" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">All Departments</option>
                                <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                            </select>
                        </div>
                        <Link :href="route('job-roles.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Job Role
                        </Link>
                    </div>
                    <div v-if="loading">Loading job roles...</div>
                    <div v-else-if="error" class="text-red-500">{{ error }}</div>
                    <div v-else>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="jobRole in jobRoles" :key="jobRole.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ jobRole.title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ jobRole.description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ jobRole.department ? jobRole.department.name : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('job-roles.edit', jobRole.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                        <button @click="deleteJobRole(jobRole.id)" class="text-red-600 hover:text-red-900">Delete</button>
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
