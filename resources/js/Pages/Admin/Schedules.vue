<template>
    <app-layout title="Schedules">
        <template #header>
            <h4 class="font-bold text-2xl text-gray-900 leading-tight">Schedules</h4>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Toolbar -->
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex flex-col md:flex-row gap-4 items-center w-full md:w-auto">
                            <!-- Date Range Picker -->
                            <DatePicker v-model="serverParams.range" :mode="'date'" :popover="popover" :model-config="modelConfig" :masks="masks" is-range>
                                <template v-slot="{ inputValue, inputEvents }">
                                    <div class="flex items-center bg-white border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <input
                                            :value="inputValue.start"
                                            v-on="inputEvents.start"
                                            class="bg-transparent border-none p-0 text-sm text-gray-700 w-24 focus:ring-0 placeholder-gray-400"
                                            placeholder="Start Date"
                                        />
                                        <span class="mx-2 text-gray-400">-</span>
                                        <input
                                            :value="inputValue.end"
                                            v-on="inputEvents.end"
                                            class="bg-transparent border-none p-0 text-sm text-gray-700 w-24 focus:ring-0 placeholder-gray-400"
                                            placeholder="End Date"
                                        />
                                    </div>
                                </template>
                            </DatePicker>

                             <div class="flex gap-2">
                                <Button @click="searchRange" type="button" class="gk-btn gk-btn-primary" label="Filter" icon="pi pi-filter" />
                                <Button @click="resetRange" type="button" class="gk-btn bg-white border border-gray-300 text-gray-700 hover:bg-gray-50" label="Reset" icon="pi pi-refresh" />
                            </div>
                        </div>

                        <Button @click="exportCSV" type="button" class="gk-btn gk-btn-success" label="Export CSV" icon="pi pi-download" />
                    </div>

                    <!-- Table -->
                    <div class="p-0">
                        <vue-good-table
                            mode="remote"
                            :columns="columns"
                            :rows="schedules.data"
                            :totalRows="schedules.meta.pagination.total"
                            :pagination-options="options"
                            :rtl="$page.props.rtl"
                            styleClass="vgt-table"
                            @page-change="onPageChange"
                            @per-page-change="onPerPageChange"
                            @column-filter="onColumnFilter"
                            @sort-change="onSortChange"
                        >
                            <template #table-row="props">
                                <!-- Code Column -->
                                <div v-if="props.column.field === 'code'">
                                     <span class="font-mono text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded select-all cursor-pointer hover:bg-gray-200 transition-colors" v-clipboard:copy="props.row.code" v-clipboard:success="handleCopyStatus">
                                        {{ props.row.code }}
                                    </span>
                                </div>
                                <!-- Booking Column -->
                                <div v-else-if="props.column.field === 'booking_code'">
                                    <Link :href="route('bookings.show', { id: props.row.booking_id })" class="text-blue-600 hover:text-blue-800 font-medium hover:underline transition-colors">
                                        {{ props.row.booking_code }}
                                    </Link>
                                </div>
                                <!-- Status Column -->
                                <div v-else-if="props.column.field === 'status'">
                                    <span :class="getStatusClass(props.row.status)">
                                        {{ props.row.status }}
                                    </span>
                                </div>
                                <!-- Actions Column -->
                                <div v-else-if="props.column.field === 'actions'">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button @click="showDetails = true; currentId = props.row.id;" class="text-gray-500 hover:text-blue-600 transition-colors p-1" title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                         <button @click="editForm = true; currentId = props.row.id;" class="text-gray-500 hover:text-green-600 transition-colors p-1" title="Update">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <!-- Default -->
                                <span v-else class="text-gray-700 text-sm">{{ props.formattedRow[props.column.field] }}</span>
                            </template>

                            <template #emptystate>
                                <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-lg font-medium">No schedules found</p>
                                    <p class="text-sm">Try adjusting your filters or date range.</p>
                                </div>
                            </template>
                        </vue-good-table>
                    </div>
                </div>
            </div>

            <!-- Drawers -->
            <Sidebar position="right" v-model:visible="showDetails" class="p-sidebar-md" :baseZIndex="1000">
                <ScheduleDetails @close="showDetails = false;" :schedule-id="currentId" :title="'Schedule Details'" />
            </Sidebar>
            <Sidebar position="right" v-model:visible="editForm" class="p-sidebar-md" :baseZIndex="1000">
                <UpdateScheduleForm @close="editForm = false;" :schedule-id="currentId" :service-units="serviceUnits" :reasons="reasons" :statuses="statuses" :title="'Update Schedule'" />
            </Sidebar>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from 'primevue/button';
import Sidebar from 'primevue/sidebar';
import { Link } from "@inertiajs/inertia-vue3";
import UpdateScheduleForm from "@/Pages/Admin/Forms/UpdateScheduleForm";
import ScheduleDetails from "@/Pages/Admin/Details/ScheduleDetails";
import { DatePicker } from "v-calendar";

export default defineComponent({
    components: {
        AppLayout,
        Button,
        Sidebar,
        Link,
        UpdateScheduleForm,
        ScheduleDetails,
        DatePicker
    },
    props: {
        schedules: Object,
        statuses: Array,
        reasons: Array,
        serviceUnits: Array,
    },
    data() {
        return {
            editForm: false,
            showDetails: false,
            currentId: null,
            columns: [
                { label: 'Code', field: 'code', sortable: false, filterOptions: { enabled: true, placeholder: 'Search...' } },
                { label: 'Booking', field: 'booking_code', sortable: false },
                { label: 'Customer', field: 'customer', sortable: false, filterOptions: { enabled: true, placeholder: 'Search...' } },
                { label: 'Customer Car', field: 'customer_car', sortable: false },
                { label: 'Date', field: 'date', sortable: true },
                { label: 'Service Unit', field: 'service_unit', sortable: false, filterOptions: { enabled: true, placeholder: 'All Units', filterDropdownItems: this.serviceUnits } },
                { label: 'Status', field: 'status', sortable: false, filterOptions: { enabled: true, placeholder: 'All Statuses', filterDropdownItems: this.statuses } },
                { label: '', field: 'actions', sortable: false, width: '100px' }
            ],
            options: {
                enabled: true,
                mode: 'pages',
                perPage: this.schedules.meta.pagination.per_page,
                setCurrentPage: this.schedules.meta.pagination.current_page,
                perPageDropdown: [10, 20, 50],
                dropdownAllowAll: false,
                nextLabel: 'next',
                prevLabel: 'prev',
                rowsPerPageLabel: 'Rows per page',
                ofLabel: 'of',
            },
            serverParams: {
                columnFilters: {},
                sort: { field: '', type: '' },
                range: { start: null, end: null },
                page: 1,
                perPage: 10
            },
            masks: { input: ['DD/MM/YYYY'] },
            modelConfig: { type: 'string', mask: 'YYYY-MM-DD' },
            popover: { visibility: 'click' },
        }
    },
    methods: {
        updateParams(newProps) {
            this.serverParams = Object.assign({}, this.serverParams, newProps);
        },
        onPageChange(params) {
            this.updateParams({ page: params.currentPage });
            this.loadItems();
        },
        onPerPageChange(params) {
            this.updateParams({ perPage: params.currentPerPage });
            this.serverParams.page = 1;
            this.loadItems();
        },
        onSortChange(params) {
            this.updateParams({
                sort: [{
                    type: params[0].type,
                    field: params[0].field,
                }],
            });
            this.loadItems();
        },
        onColumnFilter(params) {
            this.updateParams(params);
            this.serverParams.page = 1;
            this.loadItems();
        },
        searchRange() {
            this.serverParams.page = 1;
            this.loadItems();
        },
        resetRange() {
            this.serverParams.range = { start: null, end: null };
            this.serverParams.page = 1;
            this.loadItems();
        },
        loadItems() {
             // Construct clean query params
            let params = {
                page: this.serverParams.page,
                perPage: this.serverParams.perPage
            };

            // Add range only if exists and complete
            if (this.serverParams.range && this.serverParams.range.start && this.serverParams.range.end) {
                params.range = this.serverParams.range;
            }

            // Add filters
            for (const [key, value] of Object.entries(this.serverParams.columnFilters)) {
                if (value) params[key] = value;
            }

            this.$inertia.get(route(route().current()), params, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
            });
        },
        exportCSV() {
            if (!this.schedules.data || this.schedules.data.length === 0) {
                 this.$toast.add({ severity: 'warn', summary: 'No Data', detail: 'Nothing to export', life: 3000 });
                 return;
            }

            const data = this.schedules.data.map(row => ({
                'Code': row.code,
                'Booking': row.booking_code,
                'Customer': row.customer,
                'Customer Car': row.customer_car,
                'Date': row.date,
                'Service Unit': row.service_unit,
                'Status': row.status
            }));

            const headers = Object.keys(data[0]);
            const csvContent = [
                headers.join(','),
                ...data.map(row => headers.map(fieldName => JSON.stringify(row[fieldName] || '')).join(','))
            ].join('\r\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'schedules_export.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },
        handleCopyStatus(status) {
            if (status) {
                this.$toast.add({ severity: 'success', summary: 'Copied', detail: 'Copied to clipboard', life: 2000 });
            }
        },
        getStatusClass(status) {
            switch(status.toLowerCase()) {
                case 'completed': return 'px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-bold uppercase';
                case 'pending': return 'px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs font-bold uppercase';
                case 'cancelled': return 'px-2 py-1 rounded bg-red-100 text-red-800 text-xs font-bold uppercase';
                default: return 'px-2 py-1 rounded bg-gray-100 text-gray-800 text-xs font-bold uppercase';
            }
        }
    }
})
</script>
