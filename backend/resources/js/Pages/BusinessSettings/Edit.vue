<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import FormSection from '@/Components/FormSection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    business_name: props.settings.business_name || '',
    business_phone: props.settings.business_phone || '',
    business_email: props.settings.business_email || '',
    business_address: props.settings.business_address || '',
    time_zone: props.settings.time_zone || '',
});

const updateSettings = () => {
    form.put(route('settings.business.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Business Settings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Business Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <FormSection @submitted="updateSettings">
                    <template #title>
                        Business Information
                    </template>

                    <template #description>
                        Update your company's profile information and general settings.
                    </template>

                    <template #form>
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel for="business_name" value="Business Name" />
                                <TextInput id="business_name" v-model="form.business_name" type="text" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.business_name" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel for="time_zone" value="Time Zone" />
                                <TextInput id="time_zone" v-model="form.time_zone" type="text" class="mt-1 block w-full" required />
                                <InputError :message="form.errors.time_zone" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel for="business_email" value="Business Email" />
                                <TextInput id="business_email" v-model="form.business_email" type="email" class="mt-1 block w-full" />
                                <InputError :message="form.errors.business_email" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel for="business_phone" value="Business Phone" />
                                <TextInput id="business_phone" v-model="form.business_phone" type="text" class="mt-1 block w-full" />
                                <InputError :message="form.errors.business_phone" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-4">
                                <InputLabel for="business_address" value="Business Address" />
                                <TextInput id="business_address" v-model="form.business_address" type="text" class="mt-1 block w-full" />
                                <InputError :message="form.errors.business_address" class="mt-2" />
                            </div>
                        </div>
                    </template>

                    <template #actions>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save
                        </PrimaryButton>
                    </template>
                </FormSection>
            </div>
        </div>
    </AppLayout>
</template>