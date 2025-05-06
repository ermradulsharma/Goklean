<template>
    <app-layout title="Subscription Dashboard">

        <div class="pt-5 pb-10">
            <!-- Page header -->
            <div class="max-w-3xl mx-auto px-4 sm:px-6 md:flex md:items-center md:justify-between md:space-x-5 lg:max-w-7xl lg:px-8">
                <div class="flex items-center space-x-5">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ subscription.code }}</h1>
                        <p class="text-sm font-medium text-gray-900">
                            Subscription Status: <span :class="[subscription.status === 'Active' ? 'text-green-500' : 'text-red-500']" class="font-semibold">{{ subscription.status }}</span>
                        </p>
                    </div>
                </div>
                <div class="mt-6 flex flex-col-reverse justify-stretch space-y-4 space-y-reverse sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">
                    <Link :href="route('subscriptions.edit', {id: subscription.id})" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                        Edit
                    </Link>
                    <button v-if="subscription.invoices_count > 0" @click="renewSubscription(subscription.id)" type="button" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-sm shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                        Renew Subscription
                    </button>
                    <button v-else type="button" @click="startSubscription(subscription.id)" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-sm shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                        Start Subscription
                    </button>
                </div>
            </div>

            <div class="mt-8 max-w-3xl mx-auto grid grid-cols-1 gap-6 sm:px-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
                <div class="space-y-6 lg:col-start-1 lg:col-span-2">
                    <!-- Description list-->
                    <section aria-labelledby="applicant-information-title">
                        <div class="bg-white shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <h2 id="applicant-information-title" class="text-lg leading-6 font-medium text-gray-900">
                                    Subscription Information
                                </h2>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Customer
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ subscription.customer }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Customer Car
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ subscription.customer_car }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Plan
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ subscription.plan }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Billing Cycles
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ subscription.completed_billing_cycles }}/{{ subscription.total_billing_cycles }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Last Paid Date
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ subscription.last_renewed_date }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Next Renewal Date
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ subscription.next_renew_date }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <h2 class="text-lg font-medium text-gray-900 mb-2">Billing Cycles</h2>
                                        <div class="w-full flex flex-col mb-6">
                                            <table  class="w-full table-auto">
                                                <thead class="bg-gray-100 border-gray-200">
                                                <tr>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Cycle</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Invoice No</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Start</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">End</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Status</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Booking</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="invoice in invoices" :key="invoice.code">
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.billing_cycle }}</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                                        <button class="cursor-pointer text-blue-500 hover:underline" @click="showInvoice = true; currentId = invoice.id">
                                                            {{ invoice.code }}
                                                        </button>
                                                    </td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.billing_cycle_starts }}</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.billing_cycle_ends }}</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.status }}</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.booking_status }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </section>
                </div>

                <section aria-labelledby="timeline-title" class="lg:col-start-3 lg:col-span-1">
                    <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:px-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Products</h2>
                        <div class="w-full flex flex-col mb-6">
                            <table  class="w-full table-auto">
                                <thead class="bg-gray-100 border-gray-200">
                                <tr>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Product</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Qty</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in items" :key="item.code">
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.name }}</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.qty }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Preferences</h2>
                        <div class="w-full flex flex-col mb-6">
                            <table  class="w-full table-auto">
                                <thead class="bg-gray-100 border-gray-200">
                                <tr>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Day</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Time</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="day in days" :key="day.name">
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ day.name }}</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                        {{ day.time }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <Sidebar position="right" v-model:visible="showInvoice" class="p-sidebar-md">
            <InvoiceDetails @close="showInvoice = false;" :invoice-id="currentId" :title="'Invoice Details'"/>
        </Sidebar>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Button from 'primevue/button';
    import {Link} from "@inertiajs/inertia-vue3";
    import Sidebar from 'primevue/sidebar';
    import InvoiceDetails from "@/Pages/Admin/Details/InvoiceDetails";
    export default defineComponent({
        components: {
            AppLayout,
            Button,
            Link,
            Sidebar,
            InvoiceDetails
        },
        props: {
            subscription: Object,
            errors: Object,
            items: Array,
            invoices: Array,
            days: Object|Array,
        },
        data() {
            return {
                currentId: null,
                showInvoice: false,
                loading: false,
            }
        },
        methods: {
            startSubscription(id) {
                this.$confirm.require({
                    header: 'Confirm Start',
                    message: 'Do you want to start this subscription? Invoice will be created.',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Yes! Renew',
                    accept: () => {
                        this.$inertia.post(route('start_subscription', {id: id}), {}, {
                            onSuccess: () => {
                                //
                            },
                        });
                    },
                    reject: () => {

                    }
                });
            },
            renewSubscription(id) {
                this.$confirm.require({
                    header: 'Confirm Renew',
                    message: 'Do you want to renew this subscription? Invoice will be created.',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Yes! Renew',
                    accept: () => {
                        this.$inertia.post(route('renew_subscription', {id: id}), {}, {
                            onSuccess: () => {
                                //
                            },
                        });
                    },
                    reject: () => {

                    }
                });
            },
        },
    })
</script>
