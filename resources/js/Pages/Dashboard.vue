<template>
    <app-layout title="Dashboard">
        <template #header>
            <div class="flex flex-col">
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Welcome back, {{ $page.props.user.first_name }}!
                </h2>
                <p class="text-gray-500 text-sm mt-1">Here's what's happening with GoKlean today.</p>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Statistics Overview -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-10">
                    <StatCard title="Total Revenue" :subtitle="'₹ ' + stats.total_revenue">
                        <template #icon>
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </template>
                    </StatCard>
                    <StatCard title="Active Subs" :subtitle="stats.active_subscriptions">
                        <template #icon>
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </template>
                    </StatCard>
                    <StatCard title="Total Customers" :subtitle="stats.total_customers">
                        <template #icon>
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </template>
                    </StatCard>
                    <StatCard title="Monthly Bookings" :subtitle="stats.monthly_bookings">
                        <template #icon>
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </template>
                    </StatCard>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Invoices Dues Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                            <h3 class="text-lg font-bold text-gray-900">Invoices Dues</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">This Month</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <tr v-for="invoice in invoice_dues" :key="invoice.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 cursor-pointer">
                                            #{{ invoice.invoice_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ invoice.customer }}</div>
                                            <div class="text-xs text-gray-500">{{ invoice.customer_car }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm text-gray-900 font-semibold">{{ invoice.due_date }}</div>
                                            <span class="text-xs px-2 py-0.5 rounded bg-yellow-100 text-yellow-800 uppercase">{{ invoice.status }}</span>
                                        </td>
                                    </tr>
                                    <tr v-if="invoice_dues.length === 0">
                                        <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">No due invoices this month.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bookings Dues Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                            <h3 class="text-lg font-bold text-gray-900">Bookings Dues</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Pending Execution</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <tr v-for="invoice in booking_dues" :key="invoice.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 cursor-pointer">
                                            #{{ invoice.invoice_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ invoice.customer }}</div>
                                            <div class="text-xs text-gray-500">{{ invoice.customer_car }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="text-sm text-gray-900 font-semibold">{{ invoice.payment_date }}</div>
                                            <span class="text-xs px-2 py-0.5 rounded bg-green-100 text-green-800 uppercase">Paid</span>
                                        </td>
                                    </tr>
                                    <tr v-if="booking_dues.length === 0">
                                        <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">No pending bookings this month.</td>
                                    </tr>
                                </tbody>
                            </table>
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
    import StatCard from "@/Components/StatCard.vue"

    export default defineComponent({
        components: {
            AppLayout,
            StatCard
        },
        props: {
            invoice_dues: Array,
            booking_dues: Array,
            stats: Object
        }
    })
</script>
