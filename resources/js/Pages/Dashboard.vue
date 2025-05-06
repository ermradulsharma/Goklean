<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!--<div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <StatCard title="Schedules this month" subtitle="1000"></StatCard>
                    <StatCard title="Schedules this month" subtitle="1000"></StatCard>
                    <StatCard title="Schedules this month" subtitle="1000"></StatCard>
                    <StatCard title="Schedules this month" subtitle="1000"></StatCard>
                </div>-->
                <div class="w-full grid grid-cols-1 gap-4">
                    <div class="w-full">
                        <h2 class="mt-8 text-lg leading-6 font-medium text-gray-900">
                            Invoices Dues (This Month)
                        </h2>
                        <div class="flex flex-col mt-6">
                            <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Invoice ID
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th class="hidden px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:block">
                                            Customer Car
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Due Date
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="invoice in invoice_dues" class="bg-white">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ invoice.invoice_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ invoice.customer }}
                                        </td>
                                        <td class="hidden px-6 py-4 whitespace-nowrap text-sm text-gray-500 md:block">
                                            {{ invoice.customer_car }}
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                            {{ invoice.due_date }}
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                            {{ invoice.status }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <h2 class="mt-8 text-lg leading-6 font-medium text-gray-900">
                            Bookings Dues (This Month)
                        </h2>
                        <div class="flex flex-col mt-6">
                            <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Invoice ID
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th class="hidden px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider md:block">
                                            Customer Car
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Due Date
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="invoice in booking_dues" class="bg-white">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ invoice.invoice_id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ invoice.customer }}
                                        </td>
                                        <td class="hidden px-6 py-4 whitespace-nowrap text-sm text-gray-500 md:block">
                                            {{ invoice.customer_car }}
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                            {{ invoice.payment_date }}
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                            {{ invoice.status }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
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
    import StatCard from "@/Components/StatCard";

    export default defineComponent({
        components: {
            AppLayout,
            StatCard
        },
        props: {
            invoice_dues: Array,
            booking_dues: Array
        }
    })
</script>
