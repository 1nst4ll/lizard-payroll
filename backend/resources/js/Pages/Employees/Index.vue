<template>
  <AppLayout title="Employees">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Employee Management
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Actions -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
          <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <div>
                <h3 class="text-lg font-medium text-gray-900">Employees</h3>
                <p class="mt-1 text-sm text-gray-600">
                  Manage restaurant staff and employee information
                </p>
              </div>
              <div class="flex space-x-3">
                <Button
                  as="a"
                  :href="route('employees.expiring-work-permits')"
                  variant="outline"
                  class="inline-flex items-center"
                >
                  <AlertTriangle class="w-4 h-4 mr-2" />
                  Work Permit Alerts
                </Button>
                <Button
                  as="a"
                  :href="route('employees.create')"
                  class="inline-flex items-center"
                >
                  <Plus class="w-4 h-4 mr-2" />
                  Add Employee
                </Button>
              </div>
            </div>
          </div>

          <!-- Filters -->
          <div class="p-6 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4">
              <div class="md:col-span-2">
                <Label for="search">Search</Label>
                <Input
                  id="search"
                  v-model="filters.search"
                  placeholder="Search by name, ID, or email..."
                  class="mt-1"
                />
              </div>

              <div>
                <Label for="department">Department</Label>
                <Select v-model="filters.department">
                  <SelectTrigger class="mt-1">
                    <SelectValue placeholder="All Departments" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="">All Departments</SelectItem>
                    <SelectItem
                      v-for="dept in departments"
                      :key="dept.value"
                      :value="dept.value"
                    >
                      {{ dept.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div>
                <Label for="status">Legal Status</Label>
                <Select v-model="filters.status">
                  <SelectTrigger class="mt-1">
                    <SelectValue placeholder="All Statuses" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="">All Statuses</SelectItem>
                    <SelectItem
                      v-for="status in statuses"
                      :key="status.value"
                      :value="status.value"
                    >
                      {{ status.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div>
                <Label for="employment_status">Employment</Label>
                <Select v-model="filters.employment_status">
                  <SelectTrigger class="mt-1">
                    <SelectValue placeholder="All Employment" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="">All Employment</SelectItem>
                    <SelectItem
                      v-for="empStatus in employmentStatuses"
                      :key="empStatus.value"
                      :value="empStatus.value"
                    >
                      {{ empStatus.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div class="flex items-end">
                <div class="flex items-center space-x-2">
                  <Checkbox
                    id="show_all"
                    v-model="filters.show_all"
                  />
                  <Label for="show_all" class="text-sm">
                    Show inactive
                  </Label>
                </div>
              </div>
            </div>

            <div class="flex justify-end mt-4">
              <Button
                @click="clearFilters"
                variant="outline"
                size="sm"
              >
                Clear Filters
              </Button>
            </div>
          </div>
        </div>

        <!-- Employees Table -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Employee</TableHead>
                <TableHead>Department</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Contract</TableHead>
                <TableHead>Rate</TableHead>
                <TableHead>Legal Status</TableHead>
                <TableHead class="text-right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="employee in employees.data"
                :key="employee.id"
                class="hover:bg-gray-50"
              >
                <TableCell>
                  <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                      <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                        <User class="w-5 h-5 text-gray-600" />
                      </div>
                    </div>
                    <div>
                      <div class="text-sm font-medium text-gray-900">
                        {{ employee.display_name }}
                      </div>
                      <div class="text-sm text-gray-500">
                        ID: {{ employee.employee_id }}
                      </div>
                    </div>
                  </div>
                </TableCell>

                <TableCell>
                  <div class="text-sm text-gray-900">{{ formatDepartment(employee.department) }}</div>
                  <div class="text-sm text-gray-500">{{ employee.position }}</div>
                </TableCell>

                <TableCell>
                  <Badge
                    :variant="getEmploymentStatusVariant(employee.employment_status)"
                  >
                    {{ formatEmploymentStatus(employee.employment_status) }}
                  </Badge>
                  <div class="mt-1">
                    <Badge
                      v-if="!employee.can_work_legally"
                      variant="destructive"
                      class="text-xs"
                    >
                      <AlertCircle class="w-3 h-3 mr-1" />
                      Work Authorization
                    </Badge>
                    <Badge
                      v-if="employee.is_work_permit_expiring_soon"
                      variant="destructive"
                      class="text-xs"
                    >
                      <Clock class="w-3 h-3 mr-1" />
                      Permit Expiring
                    </Badge>
                  </div>
                </TableCell>

                <TableCell>
                  <div class="text-sm text-gray-900">
                    {{ formatContractType(employee.contract_type) }}
                  </div>
                </TableCell>

                <TableCell>
                  <div class="text-sm text-gray-900">
                    ${{ employee.hourly_rate.toFixed(2) }}/hr
                  </div>
                </TableCell>

                <TableCell>
                  <Badge
                    :variant="getLegalStatusVariant(employee.status)"
                  >
                    {{ formatLegalStatus(employee.status) }}
                  </Badge>
                </TableCell>

                <TableCell class="text-right">
                  <div class="flex justify-end space-x-2">
                    <Button
                      as="a"
                      :href="route('employees.show', employee.id)"
                      variant="outline"
                      size="sm"
                    >
                      <Eye class="w-4 h-4" />
                    </Button>
                    <Button
                      as="a"
                      :href="route('employees.edit', employee.id)"
                      variant="outline"
                      size="sm"
                    >
                      <Edit class="w-4 h-4" />
                    </Button>
                  </div>
                </TableCell>
              </TableRow>

              <TableRow v-if="employees.data.length === 0">
                <TableCell colspan="7" class="text-center py-8">
                  <div class="text-gray-500">
                    <Users class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                    <p class="text-lg font-medium">No employees found</p>
                    <p class="text-sm">Try adjusting your filters or add a new employee.</p>
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>

          <!-- Pagination -->
          <div class="px-6 py-4 border-t border-gray-200" v-if="employees.data.length > 0">
            <Pagination 
              :links="employees.links" 
              :currentPage="employees.current_page"
              :lastPage="employees.last_page"
            />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table'
import { Badge } from '@/Components/ui/badge'
import Checkbox from '@/Components/Checkbox.vue'
import Pagination from '@/Components/Pagination.vue'
import {
  Plus,
  Search,
  Eye,
  Edit,
  User,
  Users,
  AlertTriangle,
  AlertCircle,
  Clock
} from 'lucide-vue-next'

const props = defineProps({
  employees: Object,
  filters: Object,
  departments: Array,
  statuses: Array,
  employmentStatuses: Array,
  contractTypes: Array
})

// Create reactive filters
const filters = reactive({ ...props.filters })

// Watch for filter changes and update URL with debouncing
let timeout = null
watch(filters, () => {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    router.get(route('employees.index'), filters, {
      preserveState: true,
      replace: true,
    })
  }, 300)
})

// Helper functions
const clearFilters = () => {
  Object.keys(filters).forEach(key => {
    if (key === 'show_all') {
      filters[key] = false
    } else {
      filters[key] = null
    }
  })
}

const formatDepartment = (department) => {
  return props.departments.find(d => d.value === department)?.label || department
}

const formatEmploymentStatus = (status) => {
  return props.employmentStatuses.find(s => s.value === status)?.label || status
}

const formatContractType = (type) => {
  return props.contractTypes.find(t => t.value === type)?.label || type
}

const formatLegalStatus = (status) => {
  return props.statuses.find(s => s.value === status)?.label || status
}

const getEmploymentStatusVariant = (status) => {
  switch (status) {
    case 'active': return 'default'
    case 'inactive': return 'secondary'
    case 'terminated': return 'destructive'
    case 'on_leave': return 'outline'
    default: return 'secondary'
  }
}

const getLegalStatusVariant = (status) => {
  switch (status) {
    case 'citizen':
    case 'belonger': return 'default'
    case 'resident': return 'secondary'
    case 'work_permit_holder': return 'outline'
    default: return 'secondary'
  }
}
</script>
