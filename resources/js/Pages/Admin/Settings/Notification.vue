<template>
    <app-layout title="Notification Settings">
        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Notification Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                    <div class="p-8 sm:p-10">
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-gray-900">Notifications Preferences</h3>
                            <p class="text-gray-500 text-sm mt-1">Decide how the system communicates with users.</p>
                        </div>
                        
                        <div class="space-y-6">
                            
                            <!-- Email Notifications -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900">Email Notifications</h4>
                                    <p class="text-sm text-gray-500">Send booking confirmations and invoices via email.</p>
                                </div>
                                <div class="flex items-center">
                                    <button 
                                        type="button" 
                                        class="bg-indigo-600 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                                        role="switch" 
                                        aria-checked="true"
                                    >
                                        <span aria-hidden="true" class="translate-x-5 pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- SMS Notifications -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900">SMS Alerts</h4>
                                    <p class="text-sm text-gray-500">Send OTPs and urgent alerts via SMS.</p>
                                </div>
                                <div class="flex items-center">
                                    <button 
                                        type="button" 
                                        class="bg-indigo-600 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                                        role="switch" 
                                        aria-checked="true"
                                    >
                                        <span aria-hidden="true" class="translate-x-5 pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Push Notifications -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                <div>
                                    <h4 class="text-base font-semibold text-gray-900">Push Notifications</h4>
                                    <p class="text-sm text-gray-500">Send app push notifications for service updates.</p>
                                </div>
                                <div class="flex items-center">
                                    <button 
                                        type="button" 
                                        class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                                        role="switch" 
                                        aria-checked="false"
                                    >
                                        <span aria-hidden="true" class="translate-x-0 pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"></span>
                                    </button>
                                </div>
                            </div>

                        </div>
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
