<template>
    <app-layout title="Booking Dashboard">

        <div class="pt-5 pb-10">
            <!-- Page header -->
            <div class="max-w-3xl mx-auto px-4 sm:px-6 md:flex md:items-center md:justify-between md:space-x-5 lg:max-w-7xl lg:px-8">
                <div class="flex items-center space-x-5">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ booking.code }}</h1>
                        <p class="text-sm font-medium text-gray-900">
                            Booking Status: <span :class="[booking.status === 'Active' ? 'text-green-500' : 'text-red-500']" class="font-semibold">{{ booking.status }}</span>
                        </p>
                    </div>
                </div>
                <div class="mt-6 flex flex-col-reverse justify-stretch space-y-4 space-y-reverse sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">
                    <button type="button" @click="editForm = true; currentId = booking.id;" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                        Update
                    </button>
                    <!-- <button type="button" @click.stop="showScheduleForm = true;" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-sm shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                        Schedule
                    </button> -->
                    <button type="button" @click="showRefund = true; currentId = booking.id;" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-sm shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                        Initiate Refund
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
                                    Booking Information
                                </h2>
                            </div>
                            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Customer
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ booking.customer }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Customer Car
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ booking.customer_car }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Booking Type
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ booking.booking_type }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Invoice
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <button class="cursor-pointer text-blue-500 hover:underline" @click="showInvoice = true; currentId = booking.invoice_id">
                                                {{ booking.invoice_code }}
                                            </button>
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Created By
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ booking.created_by }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">
                                            Created On
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ booking.created_at }}
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <h2 class="text-lg font-medium text-gray-900 mb-2">Wash Schedules</h2>
                                        <div class="w-full flex flex-col mb-6">
                                            <table  class="w-full table-auto">
                                                <thead class="bg-gray-100 border-gray-200">
                                                <tr>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Schedule ID</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Date</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Items</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Items Status</td>
                                                    <!-- <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Status</td> -->
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Reason (If any)</td>
                                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Actions</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <template v-for="(schedule, index) in schedules" :key="schedule.code">
                                                        <tr v-for="(item, itemIndex) in schedule.items" :key="`${schedule.code}-${itemIndex}`">
                                                            <!-- Code (rowspan) -->
                                                            <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                                                <button class="cursor-pointer text-blue-500 hover:underline" @click="showSchedule = true; currentId = schedule.id">
                                                                    {{ schedule.code }}
                                                                </button>
                                                            </td>

                                                            <!-- Date & Overdue (rowspan) -->
                                                            <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                                                {{ schedule.date }}<br>
                                                                <span v-if="schedule.overdue" class="text-red-500">(Overdue)</span>
                                                            </td>

                                                            <!-- Item (always shown) -->
                                                            <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.name }}</td>
                                                            <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.pivot.status }}</td>

                                                            <!-- Status (rowspan) -->
                                                            <!-- <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                                                {{ schedule.status }}
                                                            </td> -->

                                                            <!-- Reason (rowspan) -->
                                                            <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                                                {{ schedule.reason }}
                                                            </td>

                                                            <!-- Update Button (rowspan) -->
                                                            <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                                                <button class="cursor-pointer text-blue-500 hover:underline" @click="editSchedule = true; currentId = schedule.id">
                                                                    Update
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>

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
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Revision History</h2>
                        <ol class="relative border-l border-gray-200 dark:border-gray-700">
                            <li v-for="revision in revisions" class="ml-4">
                                <div class="absolute w-3 h-3 bg-gray-200 rounded-full -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{ revision.time }}</time>
                                <p v-html="revision.message" class="text-base font-normal text-gray-500 dark:text-gray-400"></p>
                            </li>
                        </ol>
                    </div>
                </section>
            </div>
        </div>
        <Sidebar position="right" v-model:visible="showInvoice" class="p-sidebar-md">
            <InvoiceDetails @close="showInvoice = false;" :invoice-id="currentId" :title="'Invoice Details'"/>
        </Sidebar>
        <Sidebar position="right" v-model:visible="showSchedule" class="p-sidebar-md">
            <ScheduleDetails @close="showSchedule = false;" :schedule-id="currentId" :title="'Schedule Details'"/>
        </Sidebar>
        <Sidebar position="right" v-model:visible="editForm" class="p-sidebar-md">
            <UpdateBookingForm @close="editForm = false;" :booking-id="currentId" :service-units="serviceUnits" :statuses="statuses" :title="'Update Booking'"/>
        </Sidebar>
        <Sidebar position="right" v-model:visible="editSchedule" class="p-sidebar-md">
            <UpdateScheduleForm @close="editSchedule = false;" :schedule-id="currentId" :service-units="serviceUnits" :reasons="reasons" :statuses="statuses" :title="'Update Schedule'"/>
        </Sidebar>
        <Sidebar position="right" v-model:visible="showRefund" class="p-sidebar-md">
            <BookingRefundForm @close="showRefund = false;" :booking-id="currentId" :title="'Booking Refund'"/>
        </Sidebar>
        <!-- <Sidebar position="right" v-model:visible="showScheduleForm" class="p-sidebar-md">
            <ScheduleForm @close="showScheduleForm = false;" :booking-id="currentId" :schedules="schedules" :service-units="serviceUnits" :reasons="reasons" :statuses="statuses" :title="'Schedule Form'" />
        </Sidebar> -->
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Button from 'primevue/button';
    import {Link} from "@inertiajs/inertia-vue3";
    import Sidebar from 'primevue/sidebar';
    import InvoiceDetails from "@/Pages/Admin/Details/InvoiceDetails";
    import UpdateBookingForm from "@/Pages/Admin/Forms/UpdateBookingForm";
    import ScheduleDetails from "@/Pages/Admin/Details/ScheduleDetails";
    import UpdateScheduleForm from "@/Pages/Admin/Forms/UpdateScheduleForm";
    import BookingRefundForm from "@/Pages/Admin/Forms/BookingRefundForm";
    import ScheduleForm from "@/Pages/Admin/Forms/ScheduleForm";
    export default defineComponent({
        components: {
            AppLayout,
            Button,
            Link,
            Sidebar,
            InvoiceDetails,
            UpdateBookingForm,
            ScheduleDetails,
            UpdateScheduleForm,
            BookingRefundForm,
            ScheduleForm
        },
        props: {
            booking: Object,
            errors: Object,
            schedules: Array,
            serviceUnits: Array,
            statuses: Array,
            reasons: Array,
            revisions: Array,
        },
        data() {
            return {
                currentId: null,
                editForm: false,
                editSchedule: false,
                showInvoice: false,
                showSchedule: false,
                showScheduleForm: false,
                showRefund: false,
                loading: false,
            }
        },
        methods: {
            initiateRefund() {
                this.$inertia.post(route('refunds.calculate', {id: this.booking.id}), {}, {
                    onSuccess: () => {
                        //
                    },
                });
            }
        },
    })
</script>
