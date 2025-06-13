<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const employees = ref([]);
const departments = ref([]);
const reportData = ref([]);
const loading = ref(false);
const error = ref(null);

const filters = ref({
    employeeIds: [],
    departmentId: '',
    startDate: '',
    endDate: '',
});

const fetchEmployeesAndDepartments = async () => {
    try {
        const [employeesResponse, departmentsResponse] = await Promise.all([
            axios.get('/api/employees'),
            axios.get('/api/departments')
        ]);
        employees.value = employeesResponse.data;
        departments.value = departmentsResponse.data;
    } catch (err) {
        console.error('Failed to fetch employees or departments:', err);
    }
};

const generateReport = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await axios.get('/api/reports/hours', { params: filters.value });
        reportData.value = response.data;
    } catch (err) {
        error.value = 'Failed to generate report.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

const exportReport = async (format) => {
    try {
        const response = await axios.get('/api/reports/hours/export', {
            params: { ...filters.value, format },
            responseType: 'blob', // Important for file downloads
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `hours_report.${format}`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
    } catch (err) {
        error.value = `Failed to export report as ${format}.`;
        console.error(err);
    }
};

onMounted(() => {
    fetchEmployeesAndDepartments();
});

watch(filters.value, (newFilters, oldFilters) => {
    // Only generate report if date filters are present
    if (newFilters.startDate && newFilters.endDate) {
        generateReport();
    }
}, { deep: true });
</script>

<template>
    <AppLayout title="Hours Report">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hours Report
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex flex-wrap gap-4 mb-4 items-end">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date:</label>
                            <input type="date" id="startDate" v-model="filters.startDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700">End Date:</label>
                            <input type="date" id="endDate" v-model="filters.endDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="employeeSelect" class="block text-sm font-medium text-gray-700">Employees:</label>
                            <select id="employeeSelect" v-model="filters.employeeIds" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm h-24">
                                <option value="">All Employees</option>
                                <option v-for="employee in employees" :key="employee.id" :value="employee.id">{{ employee.first_name }} {{ employee.last_name }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="departmentSelect" class="block text-sm font-medium text-gray-700">Department:</label>
                            <select id="departmentSelect" v-model="filters.departmentId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Departments</option>
                                <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                            </select>
                        </div>
                        <button @click="generateReport" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Generate Report
                        </button>
                        <button @click="exportReport('csv')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Export CSV
                        </button>
                        <button @click="exportReport('pdf')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Export PDF
                        </button>
                    </div>

                    <div v-if="loading">Generating report...</div>
                    <div v-else-if="error" class="text-red-500">{{ error }}</div>
                    <div v-else-if="reportData.length === 0">No report data available for the selected filters.</div>
                    <div v-else>
                        <div v-for="employeeReport in reportData" :key="employeeReport.employee_id" class="mb-8 border-b pb-4">
                            <h3 class="text-lg font-semibold mb-2">{{ employeeReport.employee_name }}</h3>
                            <div v-for="week in employeeReport.weeks" :key="week.week_start_date" class="mb-4">
                                <h4 class="text-md font-medium mb-2">Week: {{ week.week_start_date }} to {{ week.week_end_date }}</h4>
                                <table class="min-w-full divide-y divide-gray-200 mb-2">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Work Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross Hours</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Break Hours</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="daily in week.daily_punches" :key="daily.work_date">
                                            <td class="px-6 py-4 whitespace-nowrap">{{ daily.work_date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ daily.day }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ daily.check_in }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ daily.check_out }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ daily.gross_hours }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ daily.break_hours }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ daily.net_hours }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-right font-bold text-sm">
                                    Total Net Hours: {{ week.weekly_summary.total_net_hours }} |
                                    Overtime Hours: {{ week.weekly_summary.overtime_hours }} |
                                    Public Holiday Hours: {{ week.weekly_summary.public_holiday_hours }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
