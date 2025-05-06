<template>
    <app-layout title="Invoices">
        <template #header>
            <h4 class="page-heading">Invoices</h4>
        </template>
        <template #actions>
            <Link :href="route('invoices.index')" class="p-button p-button-danger">Back</Link>
        </template>

        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <vue-good-table mode="remote" v-on:page-change="onPageChange" v-on:column-filter="onColumnFilter" v-on:per-page-change="onPerPageChange" :pagination-options="options" :columns="columns" :totalRows="invoices.meta.pagination.total" :rows="invoices.data" :rtl="$page.props.rtl">
                <template #table-row="props">
                    <!-- Code Column -->
                    <div v-if="props.column.field === 'invoice_id'">
                        <Tag v-clipboard:copy="props.row.invoice_id" v-clipboard:success="handleCopyStatus" :value="props.row.invoice_id" icon="pi pi-copy" class="w-full p-mr-2 cursor-pointer"/>
                    </div>
                    <!-- Booking Column -->
                    <div v-else-if="props.column.field === 'booking_completed'">
                        <span :class="[props.row.booking_completed ? 'badge-success' : 'badge-danger', 'badge']">{{ props.row.booking_completed ? 'Yes' : 'No' }}</span>
                    </div>
                    <!-- Status Column -->
                    <div v-else-if="props.column.field === 'status'">
                        <span :class="[props.row.status === 'Paid' ? 'badge-success' : 'badge-danger', 'badge']">{{ props.row.status }}</span>
                    </div>

                    <!-- Actions Column -->
                    <div v-else-if="props.column.field === 'actions'">
                        <actions-dropdown>
                            <template #actions>
                                <button @click="showInvoice = true; currentId = props.row.id" class="action-item">Details</button>
                                <button @click="restoreInvoice(props.row.id)" class="action-item">Restore</button>
                                <button @click="deleteInvoice(props.row.id)" class="action-item">Delete</button>
                            </template>
                        </actions-dropdown>
                    </div>

                    <!-- Remaining Columns -->
                    <span v-else>{{props.formattedRow[props.column.field]}}</span>
                </template>

                <template #emptystate>
                    <no-data-table>
                        <template slot="action">
                            <button @click="createForm = true" class="qt-btn-sm qt-btn-primary" type="button">
                                New Invoice
                            </button>
                        </template>
                    </no-data-table>
                </template>
            </vue-good-table>
            <Sidebar position="right" v-model:visible="showInvoice" class="p-sidebar-md">
                <InvoiceDetails @close="showInvoice = false;" :invoice-id="currentId" :title="'Invoice Details'"/>
            </Sidebar>

            <!-- Create Modal -->
            <jet-modal :show="createForm" @close="createForm = false;">
                <div class="h-96 w-full flex flex-col p-6">
                    <div class="w-full flex justify-between border-b border-gray-100 mb-4">
                        <h4 class="pb-2 text-lg font-semibold text-gray-800">New Invoice</h4>
                        <button @click="createForm = false">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <label for="customer_car_id" class="pb-2 font-semibold text-gray-800">Customer Car</label>
                    <VueSelect id="customer_car_id" v-model="customerCarId" :options="customerCars" @search="searchCustomerCars"
                              :reduce="car => car.id" label="name">
                        <template v-slot:no-options="{ search, searching }">
                            <template v-if="searching">No results were found for this search.</template>
                            <em v-else class="opacity-50">Start typing to search.</em>
                        </template>
                    </VueSelect>
                    <div class="flex justify-end mt-6">
                        <button @click="createInvoice" class="gk-btn gk-btn-success" :disable="!customerCarId">
                            Create Invoice
                        </button>
                    </div>
                </div>
            </jet-modal>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import NoDataTable from "@/Components/NoDataTable";
    import Button from 'primevue/button';
    import Sidebar from 'primevue/sidebar';
    import {Link} from "@inertiajs/inertia-vue3";
    import ActionsDropdown from "@/Components/ActionsDropdown";
    import JetModal from "@/Jetstream/Modal";
    import VueSelect from "vue-select";
    import Tag from 'primevue/tag';
    import InvoiceDetails from "@/Pages/Admin/Details/InvoiceDetails";

    export default defineComponent({
        components: {
            AppLayout,
            NoDataTable,
            Button,
            Sidebar,
            Link,
            ActionsDropdown,
            JetModal,
            VueSelect,
            Tag,
            InvoiceDetails
        },
        props: {
            invoices: Object,
            errors: Object,
            statuses: Array,
        },
        data() {
            return {
                createForm: false,
                editForm: false,
                currentId: null,
                showInvoice: false,
                customerCarId: null,
                customerCars: [],
                columns: [
                    {
                        label: 'Invoice No',
                        field: 'invoice_id',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Search Invoice No',
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
                        label: 'Customer Car',
                        field: 'customer_car',
                        sortable: false,
                    },
                    {
                        label: 'Order Type',
                        field: 'order_type',
                        sortable: false,
                    },
                    {
                        label: 'Total Price',
                        field: 'total_price',
                        sortable: false,
                    },
                    {
                        label: 'Payment Mode',
                        field: 'payment_mode',
                        sortable: false,
                    },
                    {
                        label: 'Due Date',
                        field: 'due_date',
                        sortable: false,
                    },
                    {
                        label: 'Payment Date',
                        field: 'payment_date',
                        sortable: false,
                    },
                    {
                        label: 'Booking Completed',
                        field: 'booking_completed',
                        sortable: false,
                    },
                    {
                        label: 'Status',
                        field: 'status',
                        sortable: false,
                        filterOptions: {
                            enabled: false,
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
                    perPage: this.invoices.meta.pagination.per_page,
                    setCurrentPage: this.invoices.meta.pagination.current_page,
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
                debounce: null,
            }
        },
        methods: {
            updateParams(newProps) {
                this.serverParams = Object.assign({}, this.serverParams, newProps);
            },
            onPageChange(params) {
                this.updateParams({page: params.currentPage});
                this.loadItems();
            },
            onPerPageChange(params) {
                this.updateParams({perPage: params.currentPerPage});
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
            searchExport(){
                this.serverParams.page = 1;
                this.loadItemsexport(); 
            },
            async ExportExcel() {
            // Fetch all the data at once
            await this.fetchAllData();

            // Extract the necessary data for export
            const exportData = this.invoices.data.map(row => {
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
            link.setAttribute('download', 'invoices');

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
            const totalRows = this.invoices.meta.pagination.total;
            this.options.perPage = totalRows;
            this.options.currentPage = 1;

            // Wait for the table data to be fetched
            await new Promise(resolve => {
                this.$nextTick(resolve);
            });
        },
    //         loadItemsexport() {
           
    //        this.$inertia.get('http://laravel.test/admin/export-invoice', this.getQueryParams(), {
    //           replace: false,
    //           preserveState: true,
    //           preserveScroll: true,
    //       });
         
    //    },
            deleteInvoice(id) {
                this.$confirm.require({
                    header: 'Confirm Delete',
                    message: 'Do you want to delete this record?',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Delete',
                    accept: () => {
                        this.$inertia.delete(route('invoices.destroy', {id: id}), {}, {
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
            restoreInvoice(id) {
                this.$confirm.require({
                    header: 'Confirm Restore',
                    message: 'Do you want to restore this record?',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-success',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Restore',
                    accept: () => {
                        this.$inertia.put(route('invoices.restore', { id: id }), {}, {
                            onSuccess: () => {
                                this.$toast.add({
                                    severity: 'success',
                                    summary: 'Restored',
                                    detail: 'Record restored successfully',
                                    life: 3000
                                });
                            },
                        });
                    },
                    reject: () => {
                        // Do nothing
                    }
                });
            },
            createInvoice() {
                if(this.customerCarId) {
                    this.$inertia.get(route('invoices.create'), {
                        customer_car_id: this.customerCarId
                    });
                }
            },
            searchCustomerCars(search, loading) {
                if(search !== '') {
                    let _this = this;
                    loading(true);
                    clearTimeout(this.debounce);
                    _this.customerCars = [];
                    this.debounce = setTimeout(() => {
                        axios.get(route('search_customer_cars', {query: search}))
                            .then(function (response) {
                                _this.customerCars = response.data.customerCars;
                            })
                            .catch(function (error) {
                                loading(false);
                            })
                            .then(function () {
                                loading(false);
                            });
                    }, 600)
                }
            },
            handleCopyStatus(status) {
                if (status) {
                    this.$toast.add({
                        severity: 'success',
                        summary: 'Copied',
                        detail: status.text+' Copied Successfully!',
                        life: 3000
                    });
                }
            }
        },
    })
</script>
<style>
.mx-5 {
    margin: auto 15px;
}
.invoice-btn {
    background-color: rgb(34 197 94 / var(--tw-bg-opacity)) !important;
    border: 1px solid rgb(34 197 94 / var(--tw-bg-opacity)) !important;
}
</style>