<template>
    <app-layout title="Schedules">
        <template #header>
            <h4 class="page-heading">Schedules</h4>
        </template>

        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8" id="appp">
            <div class="w-full flex justify-start items-center gap-4 mb-6">
                <DatePicker v-model="serverParams.range" :mode="'date'" :popover="popover" :model-config="modelConfig"
                    :masks="masks" :update-on-input="true" is-range>
                    <template v-slot="{ inputValue, inputEvents }">
                        <div class="flex justify-center items-center">
                            <InputText :value="inputValue.start" v-on="inputEvents.start"
                                class="border px-2 py-1 w-32 rounded focus:outline-none focus:border-indigo-300" />
                            <svg class="w-4 h-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            <InputText :value="inputValue.end" v-on="inputEvents.end"
                                class="border px-2 py-1 w-32 rounded focus:outline-none focus:border-indigo-300" />
                        </div>
                    </template>
                    <!--  -->
                </DatePicker>
                <Button @click="searchRange" type="button" class="p-button-success" :label="'Filter'" />
                <Button @click="resetRange" type="button" class="p-button-outlined" :label="'Reset'" />
                <Button @click="ExportExcel('xlsx')" type="button" class="p-button-success" :label="'Export'" />
            </div>
            <vue-good-table mode="remote" ref="exportable_table" id="app" v-on:page-change="onPageChange"
                v-on:column-filter="onColumnFilter" v-on:per-page-change="onPerPageChange" :pagination-options="options"
                :columns="columns" :totalRows="schedules.meta.pagination.total" :rows="schedules.data"
                :rtl="$page.props.rtl">
                <template #table-row="props">
                    <!-- Code Column -->
                    <div v-if="props.column.field === 'code'">
                        <Tag v-clipboard:copy="props.row.code" v-clipboard:success="handleCopyStatus"
                            :value="props.row.code" icon="pi pi-copy" class="w-full p-mr-2 cursor-pointer" />
                    </div>
                    <!-- Booking Column -->
                    <div v-else-if="props.column.field === 'booking_code'">
                        <Link :href="route('bookings.show', { id: props.row.booking_id })"
                            class="cursor-pointer text-blue-500 hover:underline">
                        {{ props.row.booking_code }}
                        </Link>
                    </div>
                    <!-- Actions Column -->
                    <div v-else-if="props.column.field === 'actions'">
                        <actions-dropdown>
                            <template #actions>
                                <button @click="showDetails = true; currentId = props.row.id;"
                                    class="action-item">Details</button>
                                <button @click="editForm = true; currentId = props.row.id;"
                                    class="action-item">Update</button>
                            </template>
                        </actions-dropdown>
                    </div>

                    <!-- Remaining Columns -->
                    <span v-else>
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                </template>

                <template #emptystate>
                    <no-data-table></no-data-table>
                </template>
            </vue-good-table>
            <Sidebar position="right" v-model:visible="showDetails" class="p-sidebar-md">
                <ScheduleDetails @close="showDetails = false;" :schedule-id="currentId" :title="'Schedule Details'" />
            </Sidebar>
            <Sidebar position="right" v-model:visible="editForm" class="p-sidebar-md">
                <UpdateScheduleForm @close="editForm = false;" :schedule-id="currentId" :service-units="serviceUnits"
                    :reasons="reasons" :statuses="statuses" :title="'Update Schedule'" />
            </Sidebar>
        </div>
    </app-layout>
</template>

<script>

import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import NoDataTable from "@/Components/NoDataTable";
import Button from 'primevue/button';
import Sidebar from 'primevue/sidebar';
import InputText from 'primevue/inputtext';
import { Link } from "@inertiajs/inertia-vue3";
import ActionsDropdown from "@/Components/ActionsDropdown";
import Tag from 'primevue/tag';
import UpdateScheduleForm from "@/Pages/Admin/Forms/UpdateScheduleForm";
import ScheduleDetails from "@/Pages/Admin/Details/ScheduleDetails";
import { DatePicker } from "v-calendar";
import { createApp } from 'vue';

export default defineComponent({
    components: {
        AppLayout,
        NoDataTable,
        Button,
        Sidebar,
        InputText,
        Link,
        ActionsDropdown,
        Tag,
        UpdateScheduleForm,
        ScheduleDetails,
        DatePicker
    },
    props: {
        schedules: Object,
        errors: Object,
        statuses: Array,
        reasons: Array,
        serviceUnits: Array,
    },
    data() {
        return {
            createForm: false,
            editForm: false,
            currentId: null,
            showDetails: false,
            columns: [
                {
                    label: 'Code',
                    field: 'code',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Search Code',
                        filterValue: null,
                        trigger: 'enter',
                    },
                    sortable: false,
                },
                {
                    label: 'Booking Code',
                    field: 'booking_code',
                    sortable: false,
                },
                {
                    label: 'Customer',
                    field: 'customer',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Search Customer',
                        filterValue: null,
                        trigger: 'enter',
                    },
                    sortable: false,
                },
                {
                    label: 'Customer Car',
                    field: 'customer_car',
                    sortable: false,
                },
                {
                    label: 'Date',
                    field: 'date',
                    sortable: false,
                },
                {
                    label: 'Service Unit',
                    field: 'service_unit',
                    sortable: false,
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Search Unit',
                        filterValue: null,
                        filterDropdownItems: this.serviceUnits,
                    },
                },
                /*{
                    label: 'Items',
                    field: 'items',
                    sortable: false,
                },*/
                {
                    label: 'Status',
                    field: 'status',
                    sortable: false,
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Search Status',
                        filterValue: null,
                        filterDropdownItems: this.statuses,
                    },
                },
                {
                    label: 'Actions',
                    field: 'actions',
                    sortable: false,
                }
            ],
            options: {
                enabled: true,
                mode: 'pages',
                perPage: this.schedules.meta.pagination.per_page,
                setCurrentPage: this.schedules.meta.pagination.current_page,
                perPageDropdown: [10, 20, 50, 100],
                dropdownAllowAll: false,
            },
            serverParams: {
                columnFilters: {},
                sort: {
                    field: '',
                    type: '',
                },
                range: {
                    start: null,
                    end: null,
                },
                page: 1,
                perPage: 10
            },
            masks: {
                input: ['DD/MM/YYYY'],
            },
            modelConfig: {
                type: 'string',
                mask: 'YYYY-MM-DD', // Uses 'iso' if missing
            },
            popover: {
                visibility: 'click'
            },
            loading: false,
            debounce: null,
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
                    type: params.sortType,
                    field: this.columns[params.columnIndex].field,
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
        searchExport() {
            this.serverParams.page = 1;
            this.loadItemsexport();
        },
        resetRange() {
            this.serverParams.range = {
                start: null,
                end: null
            }
            this.serverParams.page = 1;
            this.loadItems();
        },
        getQueryParams() {
            let data = {
                'page': this.serverParams.page,
                'perPage': this.serverParams.perPage,
                'range': this.serverParams.range
            }

            for (const [key, value] of Object.entries(this.serverParams.columnFilters)) {
                if (value) {
                    data[key] = value;
                }
            }

            return data;
        },
        loadItems() {
            this.$inertia.get(route(route().current()), this.getQueryParams(), {
                replace: false,
                preserveState: true,
                preserveScroll: true,
            });
        },
        async ExportExcel() {
            const totalRows = this.schedules.meta.pagination.total;
            const rowsPerPage = this.options.perPage;
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            const exportData = [];

            // Iterate over each page and fetch the data
           

                // Iterate over each row on the current page and extract the necessary data
                for (const row of this.schedules.data) {
                    const rowData = {};

                    // Iterate over the columns and extract the data for export
                    for (const column of this.columns) {
                        // Check if special handling is required for certain columns
                        if (column.field === 'code') {
                            rowData[column.label] = row.code;
                        } else if (column.field === 'booking_code') {
                            rowData[column.label] = row.booking_code;
                        } else if (column.field === 'actions') {
                            rowData[column.label] = 'N/A'; // Or any other default value for actions column
                        } else {
                            rowData[column.label] = row[column.field];
                        }
                    }

                    exportData.push(rowData);
                }
          

            // Convert exportData to CSV format
            const csvContent = this.convertToCsv(exportData);

            // Create a temporary anchor element
            const link = document.createElement('a');
            link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvContent);
            link.setAttribute('download', 'schedules');

            // Append the link to the document body and click it programmatically to trigger the download
            document.body.appendChild(link);
            link.click();

            // Clean up by removing the temporary link
            document.body.removeChild(link);
        },
        convertToCsv(data) {
            // ... Code to convert the data to CSV format ...
            // Here's a basic example of converting an array of objects to CSV format:
            const headers = Object.keys(data[0]).join(',');
            const rows = data.map(obj => Object.values(obj).join(','));
            return [headers, ...rows].join('\n');
        },


        handleCopyStatus(status) {

            if (status) {
                this.$toast.add({
                    severity: 'success',
                    summary: 'Copied',
                    detail: status.text + ' Copied Successfully!',
                    life: 3000
                });
            }
        }
    },
    mounted() {
        const recaptchaScript = document.createElement("script");
        recaptchaScript.setAttribute(
            "src",
            "https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.1/xlsx.full.min.js"
        );
        document.head.appendChild(recaptchaScript);
    },

})
</script>
