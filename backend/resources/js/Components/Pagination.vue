<template>
  <nav class="flex items-center justify-between">
    <div class="flex justify-between flex-1 sm:hidden">
      <Link
        v-if="links[0]?.url"
        :href="links[0].url"
        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:text-gray-400"
      >
        Previous
      </Link>
      <Link
        v-if="links[links.length - 1]?.url"
        :href="links[links.length - 1].url"
        class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:text-gray-400"
      >
        Next
      </Link>
    </div>
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Showing page {{ currentPage }} of {{ lastPage }}
        </p>
      </div>
      <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
          <template v-for="(link, index) in links" :key="index">
            <Link
              v-if="link.url"
              :href="link.url"
              :class="[
                'relative inline-flex items-center px-2 py-2 border text-sm font-medium',
                link.active
                  ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                  : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                index === 0 ? 'rounded-l-md' : '',
                index === links.length - 1 ? 'rounded-r-md' : ''
              ]"
              v-html="link.label"
            />
            <span
              v-else
              :class="[
                'relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-300',
                index === 0 ? 'rounded-l-md' : '',
                index === links.length - 1 ? 'rounded-r-md' : ''
              ]"
              v-html="link.label"
            />
          </template>
        </nav>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: {
    type: Array,
    required: true
  },
  currentPage: {
    type: Number,
    default: 1
  },
  lastPage: {
    type: Number,
    default: 1
  }
})
</script>
