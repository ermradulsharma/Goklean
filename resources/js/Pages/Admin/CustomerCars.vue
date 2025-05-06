<template>
    <app-layout title="Customer Cars">
        <template #header>
            <h4 class="page-heading">Customer Cars</h4>
        </template>
        <template #actions>
            <Button @click="ExportExcel('xlsx')" type="button" class="export-btn p-button-success mx-5" :label="'Export'" />
            <button @click="createForm = true" class="gk-btn gk-btn-success">
                New Car
            </button>
        </template>
       
        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <vue-good-table mode="remote" v-on:page-change="onPageChange" v-on:column-filter="onColumnFilter"
                v-on:per-page-change="onPerPageChange" :pagination-options="options" :columns="columns"
                :totalRows="customerCars.meta.pagination.total" :rows="customerCars.data" :rtl="$page.props.rtl">
                <template #table-row="props">
                    <!-- Status Column -->
                    <div v-if="props.column.field === 'status'">
                        <span :class="[props.row.status ? 'badge-success' : 'badge-danger', 'badge']">{{ props.row.status ?
                            'Active' : 'In-active' }}</span>
                    </div>

                    <!-- Actions Column -->
                    <div v-else-if="props.column.field === 'actions'">
                        <actions-dropdown>
                            <template #actions>
                                <Link :href="route('invoices.create', { id: props.row.id })" class="action-item">New Invoice
                                </Link>
                                <button @click="editForm = true; currentId = props.row.id;"
                                    class="action-item">Edit</button>
                                <button @click="deleteCar(props.row.id)" class="action-item">Delete</button>
                            </template>
                        </actions-dropdown>
                    </div>

                    <!-- Remaining Columns -->
                    <span v-else>
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                </template>

                <template #emptystate>
                    <no-data-table>
                        <template slot="action">
                            <button @click="createForm = true" class="qt-btn-sm qt-btn-primary" type="button">
                                New Car
                            </button>
                        </template>
                    </no-data-table>
                </template>
            </vue-good-table>

            <!-- Sidebar Forms -->
            <Sidebar position="right" v-model:visible="createForm" class="p-sidebar-md">
                <CustomerCarForm :form-errors="errors" :cars="cars" :customers="customers" :colors="colors"
                    @close="createForm = false;" :title="'New Car'" />
            </Sidebar>
            <Sidebar position="right" v-model:visible="editForm" class="p-sidebar-md">
                <CustomerCarForm :form-errors="errors" :cars="cars" :customers="customers" :colors="colors"
                    :edit-flag="editForm" @close="editForm = false;" :customer-car-id="currentId" :title="'Edit Car'" />
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
import CustomerCarForm from '@/Pages/Admin/Forms/CustomerCarForm';
import { Link } from "@inertiajs/inertia-vue3";
import ActionsDropdown from "@/Components/ActionsDropdown";

export default defineComponent({
    components: {
        AppLayout,
        NoDataTable,
        Button,
        Sidebar,
        CustomerCarForm,
        Link,
        ActionsDropdown
    },
    props: {
        customerCars: Object,
        errors: Object,
        customers: Array,
        cars: Array,
        colors: Array
    },
    data() {
        return {
            createForm: false,
            editForm: false,
            currentId: null,
            columns: [
                {
                    label: 'Name',
                    field: 'name',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Search Name',
                        filterValue: null,
                        trigger: 'enter',
                    },
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
                    label: 'Color',
                    field: 'color',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Search Color',
                        filterValue: null,
                        trigger: 'enter',
                    },
                    sortable: false,
                },
                {
                    label: 'Number Plate',
                    field: 'number',
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Search Number',
                        filterValue: null,
                        trigger: 'enter',
                    },
                    sortable: false,
                },
                {
                    label: 'Status',
                    field: 'status',
                    sortable: false,
                    filterOptions: {
                        enabled: true,
                        placeholder: 'Search Status',
                        filterValue: null,
                        filterDropdownItems: [{ value: 1, text: 'Active' }, { value: 0, text: 'In-active' }],
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
                perPage: this.customerCars.meta.pagination.per_page,
                setCurrentPage: this.customerCars.meta.pagination.current_page,
                perPageDropdown: [10, 20, 50, 100],
                dropdownAllowAll: false,
            },
            serverParams: {
                columnFilters: {},
                sort: {
                    field: '',
                    type: '',
                },
                page: 1,
                perPage: 10
            },
            loading: false,
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
        searchExport() {
            this.serverParams.page = 1;
            this.loadItemsexport();
        },
        getQueryParams() {
            let data = {
                'page': this.serverParams.page,
                'perPage': this.serverParams.perPage,
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
            // Fetch all the data at once
            await this.fetchAllData();

            // Extract the necessary data for export
            const exportData = this.customerCars.data.map(row => {
                const rowData = {};
                for (const column of this.columns) {
                    if (column.field === 'code') {
                        rowData[column.label] = row.code;
                    } else if (column.field === 'actions') {
                        rowData[column.label] = 'N/A'; // Or any other default value for actions column
                    } else {
                        rowData[column.label] = row[column.field];
                    }
                }
                return rowData;
            });

            // Convert exportData to CSV format
            const csvContent = this.convertToCsv(exportData);

            // Create a temporary anchor element
            const link = document.createElement('a');
            link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvContent);
            link.setAttribute('download', 'customerCars');

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
        async fetchAllData() {
            const totalRows = this.customerCars.meta.pagination.total;
            this.options.perPage = totalRows;
            this.options.currentPage = 1;

            // Wait for the table data to be fetched
            await new Promise(resolve => {
                this.$nextTick(resolve);
            });
        },
        //loadItemsexport() {

        //        this.$inertia.get('http://laravel.test/admin/export-customer-car', this.getQueryParams(), {
        //           replace: false,
        //           preserveState: true,
        //           preserveScroll: true,
        //       });

        //    },
        deleteCar(id) {
            this.$confirm.require({
                header: 'Confirm Delete',
                message: 'Do you want to delete this record?',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                rejectLabel: 'Cancel',
                acceptLabel: 'Delete',
                accept: () => {
                    this.$inertia.delete(route('customer-cars.destroy', { id: id }), {}, {
                        onSuccess: () => {
                            this.$toast.add({
                                severity: 'info',
                                summary: 'Confirmed',
                                detail: 'Record deleted',
                                life: 3000
                            });
                        },
                    });
                },
                reject: () => {

                }
            });
        },
    }
})
</script>
