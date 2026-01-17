<template>
    <app-layout title="Payment Settings">
        <template #header>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Payment Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Razorpay -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 mb-8">
                    <div class="p-8 sm:p-10">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Razorpay Configuration</h3>
                                <p class="text-gray-500 text-sm mt-1">Configure your razorpay payment keys.</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-6 gap-x-6">
                            <div class="sm:col-span-6">
                                <label for="rzp_key" class="block text-sm font-semibold text-gray-700">Razorpay Key ID</label>
                                <div class="mt-1">
                                    <input type="text" name="rzp_key" id="rzp_key" class="font-mono text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full border-gray-300 rounded-lg p-3" value="rzp_test_1234567890abcdef">
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="rzp_secret" class="block text-sm font-semibold text-gray-700">Razorpay Key Secret</label>
                                <div class="mt-1">
                                    <input type="password" name="rzp_secret" id="rzp_secret" class="font-mono text-sm shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full border-gray-300 rounded-lg p-3" value="****************">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stripe (Disabled example) -->
                 <div class="bg-white overflow-hidden shadow-lg sm:rounded-3xl border border-gray-100 opacity-75">
                    <div class="p-8 sm:p-10 pointer-events-none">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-500">Stripe Configuration</h3>
                                <p class="text-gray-400 text-sm mt-1">Enable Stripe to configure keys.</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Disabled
                            </span>
                        </div>
                    </div>
                 </div>

                 <div class="mt-8 flex justify-end">
                     <button type="button" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">
                        Save Configurations
                    </button>
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
