<template>
    <app-layout title="General Settings">
        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                General Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                    <div class="p-8 sm:p-10">
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-gray-900">Application Information</h3>
                            <p class="text-gray-500 text-sm mt-1">Update generic details about your application.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- App Name -->
                            <div class="sm:col-span-4">
                                <label for="app_name" class="block text-sm font-semibold text-gray-700">App Name</label>
                                <div class="mt-1">
                                    <input type="text" name="app_name" id="app_name" autocomplete="given-name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg p-3" value="GoKlean" placeholder="e.g. GoKlean">
                                </div>
                            </div>

                            <!-- Initial Version -->
                            <div class="sm:col-span-2">
                                <label for="version" class="block text-sm font-semibold text-gray-700">Current Version</label>
                                <div class="mt-1">
                                    <input type="text" name="version" id="version" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg p-3 bg-gray-50 text-gray-500" value="1.0.0" disabled>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-6">
                                <label for="about" class="block text-sm font-semibold text-gray-700">About</label>
                                <div class="mt-1">
                                    <textarea id="about" name="about" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg p-3" placeholder="Brief description of the app services..."></textarea>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Write a few sentences about app services.</p>
                            </div>

                             <!-- Support Email -->
                            <div class="sm:col-span-3">
                                <label for="support_email" class="block text-sm font-semibold text-gray-700">Support Email</label>
                                <div class="mt-1">
                                    <input type="email" id="support_email" name="support_email" autocomplete="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg p-3" placeholder="support@goklean.com">
                                </div>
                            </div>

                             <!-- Contact Phone -->
                            <div class="sm:col-span-3">
                                <label for="contact_phone" class="block text-sm font-semibold text-gray-700">Contact Phone</label>
                                <div class="mt-1">
                                    <input type="text" id="contact_phone" name="contact_phone" autocomplete="tel" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg p-3" placeholder="+91 98765 43210">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-4 bg-gray-50 sm:px-6 flex justify-end">
                        <button type="button" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'

    export default defineComponent({
        components: {
            AppLayout,
        },
    })
</script>
