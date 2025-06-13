<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const departments = ref([]);
const loading = ref(true);
const error = ref(null);

const fetchDepartments = async () => {
    try {
        const response = await axios.get('/api/departments');
        departments.value = response.data;
    } catch (err) {
        error.value = 'Failed to fetch departments.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchDepartments);

const deleteDepartment = async (id) => {
    if (confirm('Are you sure you want to delete this department?')) {
        try {
            await axios.delete(`/api/departments/${id}`);
            fetchDepartments(); // Refresh the list
        } catch (err) {
            error.value = 'Failed to delete department.';
            console.error(err);
        }
    }
};
</script>

<template>
    <AppLayout title="Departments">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Departments
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div v-if="loading">Loading departments...</div>
                    <div v-else-if="error" class="text-red-500">{{ error }}</div>
                    <div v-else>
                        <div class="flex justify-end mb-4">
                            <Link :href="route('departments.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add New Department
                            </Link>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Department Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="department in departments" :key="department.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ department.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ department.description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('departments.edit', department.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                        <button @click="deleteDepartment(department.id)" class="text-red-600 hover:text-red-900">Delete</button>
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
