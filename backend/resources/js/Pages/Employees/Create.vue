<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const departments = ref([]);
const jobRoles = ref([]);
const loadingDepartments = ref(true);
const loadingJobRoles = ref(true);

const employeeStatuses = [
    'Work Permit Holder',
    'Resident',
    'Citizen',
    'Belonger'
];

const paymentMethods = [
    'CIBC',
    'Scotiabank',
    'RBC',
    'Check'
];

const form = useForm({
    employee_id: '',
    first_name: '',
    last_name: '',
    nickname: '',
    gender: '',
    dob: '',
    phone_number: '',
    email_address: '',
    address: '',
    passport_number: '',
    status: '',
    nib_number: '',
    nib_deduction_override: false,
    nhib_number: '',
    nhib_deduction_override: false,
    payment_method: '',
    starting_date: '',
    contract_type: '',
    rate: '',
    contract_signed: false,
    uniform_size: '',
    department_id: '',
    job_role_id: '',
});

const fetchDepartments = async () => {
    try {
        const response = await axios.get('/api/departments');
        departments.value = response.data;
    } catch (err) {
        console.error('Failed to fetch departments:', err);
    } finally {
        loadingDepartments.value = false;
    }
};

const fetchJobRoles = async (departmentId) => {
    loadingJobRoles.value = true;
    try {
        const url = departmentId ? `/api/departments/${departmentId}/job-roles` : '/api/job-roles';
        const response = await axios.get(url);
        jobRoles.value = response.data;
    } catch (err) {
        console.error('Failed to fetch job roles:', err);
    } finally {
        loadingJobRoles.value = false;
    }
};

onMounted(() => {
    fetchDepartments();
    fetchJobRoles(); // Fetch all job roles initially
});

const onDepartmentChange = () => {
    form.job_role_id = ''; // Reset job role when department changes
    fetchJobRoles(form.department_id);
};

const submit = () => {
    form.post(route('employees.store'), {
        onSuccess: () => form.reset(),
        onError: (errors) => console.error(errors),
    });
};
</script>

<template>
    <AppLayout title="Add Employee">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add Employee
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee ID:</label>
                                <input type="text" id="employee_id" v-model="form.employee_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <div v-if="form.errors.employee_id" class="text-red-500 text-xs mt-1">{{ form.errors.employee_id }}</div>
                            </div>
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                                <input type="text" id="first_name" v-model="form.first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <div v-if="form.errors.first_name" class="text-red-500 text-xs mt-1">{{ form.errors.first_name }}</div>
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                                <input type="text" id="last_name" v-model="form.last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <div v-if="form.errors.last_name" class="text-red-500 text-xs mt-1">{{ form.errors.last_name }}</div>
                            </div>
                            <div>
                                <label for="nickname" class="block text-sm font-medium text-gray-700">Nickname:</label>
                                <input type="text" id="nickname" v-model="form.nickname" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <div v-if="form.errors.nickname" class="text-red-500 text-xs mt-1">{{ form.errors.nickname }}</div>
                            </div>
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Gender:</label>
                                <select id="gender" v-model="form.gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div v-if="form.errors.gender" class="text-red-500 text-xs mt-1">{{ form.errors.gender }}</div>
                            </div>
                            <div>
                                <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth:</label>
                                <input type="date" id="dob" v-model="form.dob" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <div v-if="form.errors.dob" class="text-red-500 text-xs mt-1">{{ form.errors.dob }}</div>
                            </div>
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number:</label>
                                <input type="text" id="phone_number" v-model="form.phone_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <div v-if="form.errors.phone_number" class="text-red-500 text-xs mt-1">{{ form.errors.phone_number }}</div>
                            </div>
                            <div>
                                <label for="email_address" class="block text-sm font-medium text-gray-700">Email Address:</label>
                                <input type="email" id="email_address" v-model="form.email_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <div v-if="form.errors.email_address" class="text-red-500 text-xs mt-1">{{ form.errors.email_address }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Address:</label>
                                <textarea id="address" v-model="form.address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                <div v-if="form.errors.address" class="text-red-500 text-xs mt-1">{{ form.errors.address }}</div>
                            </div>
                            <div>
                                <label for="passport_number" class="block text-sm font-medium text-gray-700">Passport Number:</label>
                                <input type="text" id="passport_number" v-model="form.passport_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <div v-if="form.errors.passport_number" class="text-red-500 text-xs mt-1">{{ form.errors.passport_number }}</div>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status:</label>
                                <select id="status" v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">Select Status</option>
                                    <option v-for="status in employeeStatuses" :key="status" :value="status">{{ status }}</option>
                                </select>
                                <div v-if="form.errors.status" class="text-red-500 text-xs mt-1">{{ form.errors.status }}</div>
                            </div>
                            <div>
                                <label for="nib_number" class="block text-sm font-medium text-gray-700">NIB Number:</label>
                                <input type="text" id="nib_number" v-model="form.nib_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <div v-if="form.errors.nib_number" class="text-red-500 text-xs mt-1">{{ form.errors.nib_number }}</div>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="nib_deduction_override" v-model="form.nib_deduction_override" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="nib_deduction_override" class="ml-2 block text-sm text-gray-900">NIB Deduction Override</label>
                            </div>
                            <div>
                                <label for="nhib_number" class="block text-sm font-medium text-gray-700">NHIB Number:</label>
                                <input type="text" id="nhib_number" v-model="form.nhib_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <div v-if="form.errors.nhib_number" class="text-red-500 text-xs mt-1">{{ form.errors.nhib_number }}</div>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="nhib_deduction_override" v-model="form.nhib_deduction_override" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="nhib_deduction_override" class="ml-2 block text-sm text-gray-900">NHIB Deduction Override</label>
                            </div>
                            <div>
                                <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method:</label>
                                <select id="payment_method" v-model="form.payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">Select Payment Method</option>
                                    <option v-for="method in paymentMethods" :key="method" :value="method">{{ method }}</option>
                                </select>
                                <div v-if="form.errors.payment_method" class="text-red-500 text-xs mt-1">{{ form.errors.payment_method }}</div>
                            </div>
                            <div>
                                <label for="starting_date" class="block text-sm font-medium text-gray-700">Starting Date:</label>
                                <input type="date" id="starting_date" v-model="form.starting_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <div v-if="form.errors.starting_date" class="text-red-500 text-xs mt-1">{{ form.errors.starting_date }}</div>
                            </div>
                            <div>
                                <label for="contract_type" class="block text-sm font-medium text-gray-700">Contract Type:</label>
                                <select id="contract_type" v-model="form.contract_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">Select Contract Type</option>
                                    <option value="Hourly">Hourly</option>
                                    <option value="Salary">Salary</option>
                                </select>
                                <div v-if="form.errors.contract_type" class="text-red-500 text-xs mt-1">{{ form.errors.contract_type }}</div>
                            </div>
                            <div>
                                <label for="rate" class="block text-sm font-medium text-gray-700">Rate:</label>
                                <input type="number" id="rate" v-model="form.rate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <div v-if="form.errors.rate" class="text-red-500 text-xs mt-1">{{ form.errors.rate }}</div>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="contract_signed" v-model="form.contract_signed" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="contract_signed" class="ml-2 block text-sm text-gray-900">Contract Signed</label>
                            </div>
                            <div>
                                <label for="uniform_size" class="block text-sm font-medium text-gray-700">Uniform Size:</label>
                                <input type="text" id="uniform_size" v-model="form.uniform_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <div v-if="form.errors.uniform_size" class="text-red-500 text-xs mt-1">{{ form.errors.uniform_size }}</div>
                            </div>
                            <div>
                                <label for="department_id" class="block text-sm font-medium text-gray-700">Department:</label>
                                <select id="department_id" v-model="form.department_id" @change="onDepartmentChange" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Select Department</option>
                                    <option v-for="department in departments" :key="department.id" :value="department.id">{{ department.name }}</option>
                                </select>
                                <div v-if="form.errors.department_id" class="text-red-500 text-xs mt-1">{{ form.errors.department_id }}</div>
                            </div>
                            <div>
                                <label for="job_role_id" class="block text-sm font-medium text-gray-700">Job Role:</label>
                                <select id="job_role_id" v-model="form.job_role_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Select Job Role</option>
                                    <option v-for="jobRole in jobRoles" :key="jobRole.id" :value="jobRole.id">{{ jobRole.title }}</option>
                                </select>
                                <div v-if="form.errors.job_role_id" class="text-red-500 text-xs mt-1">{{ form.errors.job_role_id }}</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" :disabled="form.processing" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Add Employee
                            </button>
                            <Link :href="route('employees.index')" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 ml-4">
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
