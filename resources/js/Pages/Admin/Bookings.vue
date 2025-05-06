<template>
    <app-layout title="Bookings">
        <template #header>
            <h4 class="page-heading">Bookings</h4>
        </template>
        <template #actions>
            <Button @click="ExportExcel('xlsx')" type="button" class="export-btn p-button-success" :label="'Export'" />
            <button @click="createForm = true" class="gk-btn gk-btn-success">
                New Booking
            </button>
        </template>

        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <vue-good-table mode="remote" v-on:page-change="onPageChange" v-on:column-filter="onColumnFilter"
                v-on:per-page-change="onPerPageChange" :pagination-options="options" :columns="columns"
                :totalRows="bookings.meta.pagination.total" :rows="bookings.data" :rtl="$page.props.rtl">
                <template #table-row="props">
                    <!-- Code Column -->
                    <div v-if="props.column.field === 'code'">
                        <Tag v-clipboard:copy="props.row.code" v-clipboard:success="handleCopyStatus"
                            :value="props.row.code" icon="pi pi-copy" class="w-full p-mr-2 cursor-pointer" />
                    </div>
                    <!-- Invoice Column -->
                    <div v-else-if="props.column.field === 'invoice_id'">
                        <button class="cursor-pointer text-blue-500 hover:underline"
                            @click="showInvoice = true; currentId = props.row.invoice_id">
                            {{ props.row.invoice_code }}
                        </button>
                    </div>
                    <!-- Actions Column -->
                    <div v-else-if="props.column.field === 'actions'">
                        <actions-dropdown>
                            <template #actions>
                                <Link :href="route('bookings.show', { id: props.row.id })" class="action-item">Dashboard
                                </Link>
                                <button @click="editForm = true; currentId = props.row.id;"
                                    class="action-item">Update</button>
                                <button @click="deleteBooking(props.row.id)" class="action-item">Delete</button>
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
                                New Booking
                            </button>
                        </template>
                    </no-data-table>
                </template>
            </vue-good-table>
            <Sidebar position="right" v-model:visible="showInvoice" class="p-sidebar-md">
                <InvoiceDetails @close="showInvoice = false;" :invoice-id="currentId" :title="'Invoice Details'" />
            </Sidebar>
            <Sidebar position="right" v-model:visible="editForm" class="p-sidebar-md">
                <UpdateBookingForm @close="editForm = false;" :booking-id="currentId" :service-units="serviceUnits"
                    :statuses="statuses" :title="'Update Booking'" />
            </Sidebar>
            <!-- Create Modal -->
            <jet-modal :show="createForm" @close="createForm = false;">
                <div class="h-96 w-full flex flex-col p-6">
                    <div class="w-full flex justify-between border-b border-gray-100 mb-4">
                        <h4 class="pb-2 text-lg font-semibold text-gray-800">New Booking</h4>
                        <button @click="createForm = false">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <label for="invoice_id" class="pb-2 font-semibold text-gray-800">Invoice</label>
                    <VueSelect id="invoice_id" v-model="invoiceId" :options="invoices" @search="searchInvoices"
                        :reduce="invoice => invoice.id" label="name">
                        <template v-slot:no-options="{ search, searching }">
                            <template v-if="searching">No results were found for this search.</template>
                            <em v-else class="opacity-50">Start typing to search.</em>
                        </template>
                    </VueSelect>
                    <div class="flex justify-end mt-6">
                        <button @click="createBooking" class="gk-btn gk-btn-success" :disable="!invoiceId">
                            Create Booking
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
import JetModal from "@/Jetstream/Modal";
import VueSelect from "vue-select";
import Tag from "primevue/tag";
import { Link } from "@inertiajs/inertia-vue3";
import ActionsDropdown from "@/Components/ActionsDropdown";
import InvoiceDetails from "@/Pages/Admin/Details/InvoiceDetails";
import UpdateBookingForm from "@/Pages/Admin/Forms/UpdateBookingForm";

export default defineComponent({
    components: {
        AppLayout,
        NoDataTable,
        Button,
        Sidebar,
        JetModal,
        VueSelect,
        Tag,
        Link,
        ActionsDropdown,
        InvoiceDetails,
        UpdateBookingForm
    },
    props: {
        bookings: Object,
        errors: Object,
        statuses: Array,
        serviceUnits: Array,
    },
    data() {
        return {
            createForm: false,
            editForm: false,
            showInvoice: false,
            currentId: null,
            invoiceId: null,
            invoices: [],
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
                    label: 'Booking Type',
                    field: 'booking_type',
                    sortable: false,
                },
                {
                    label: 'Invoice No',
                    field: 'invoice_id',
                    sortable: false,
                },
                {
                    label: 'Created By',
                    field: 'created_by',
                    sortable: false,
                },
                {
                    label: 'Created On',
                    field: 'created_at',
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
                perPage: this.bookings.meta.pagination.per_page,
                setCurrentPage: this.bookings.meta.pagination.current_page,
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
            const totalRows = this.bookings.meta.pagination.total;
            const rowsPerPage = this.options.perPage;
            const totalPages = Math.ceil(totalRows / rowsPerPage);
            const exportData = [];

            // Iterate over each page and fetch the data
           
               
                for (const row of this.bookings.data) {
                    const rowData = {};

                    // Iterate over the columns and extract the data for export
                    for (const column of this.columns) {
                        // Check if special handling is required for certain columns
                        if (column.field === 'code') {
                            rowData[column.label] = row.code;
                        } else if (column.field === 'invoice_id') {
                            rowData[column.label] = row.invoice_code;
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
            link.setAttribute('download', 'booking');

            // Append the link to the document body and click it programmatically to trigger the download
            document.body.appendChild(link);
            link.click();

            // Clean up by removing the temporary link
            document.body.removeChild(link);
        },
        convertToCsv(data) {
            const separator = ',';
            const keys = Object.keys(data[0]);
            const header = keys.join(separator);
            const content = data.map(row => {
                return keys.map(key => row[key]).join(separator);
            });
            return [header, ...content].join('\n');
        },
        createBooking() {
            if (this.invoiceId) {
                this.$inertia.get(route('bookings.create'), {
                    invoice_id: this.invoiceId
                });
            }
        },
        searchInvoices(search, loading) {
            if (search !== '') {
                let _this = this;
                loading(true);
                clearTimeout(this.debounce);
                _this.invoices = [];
                this.debounce = setTimeout(() => {
                    axios.get(route('search_invoices', { query: search }))
                        .then(function (response) {
                            _this.invoices = response.data.invoices;
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
        deleteBooking(id) {
            this.$confirm.require({
                header: 'Confirm Delete',
                message: 'Do you want to delete this booking? All the associated schedules will be deleted.',
                icon: 'pi pi-info-circle',
                acceptClass: 'p-button-danger',
                rejectLabel: 'Cancel',
                acceptLabel: 'Delete',
                accept: () => {
                    this.$inertia.delete(route('bookings.destroy', { id: id }), {}, {
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
        handleCopyStatus(status) {
            console.log(status);
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
})
</script>
