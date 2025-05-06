<template>
    <app-layout title="Customers">
        <template #header>
            <h4 class="page-heading">Customers</h4>
        </template>
        <template #actions>
            <Button @click="ExportExcel('xlsx')" type="button" class="export-btn p-button-success mx-5" :label="'Export'" />
            <button @click="createForm = true" class="gk-btn gk-btn-success">New Customer</button>
        </template>

        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <vue-good-table mode="remote" v-on:page-change="onPageChange" v-on:column-filter="onColumnFilter" v-on:per-page-change="onPerPageChange" :pagination-options="options" :columns="columns" :totalRows="customers.meta.pagination.total" :rows="customers.data" :rtl="$page.props.rtl">
                <template #table-row="props">

                    <!-- Status Column -->
                    <div v-if="props.column.field === 'status'">
                        <span :class="[props.row.status ? 'badge-success' : 'badge-danger', 'badge']">{{ props.row.status ? 'Active' : 'In-active' }}</span>
                    </div>

                    <!-- Actions Column -->
                    <div v-else-if="props.column.field === 'actions'">
                        <Button icon="pi pi-map-marker" class="p-button-rounded p-button-primary p-button-text p-mr-2" @click="addressForm = true; currentId = props.row.id;"/>
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-secondary p-button-text p-mr-2" @click="editForm = true; currentId = props.row.id;"/>
                        <Button icon="pi pi-wallet" class="p-button-rounded p-button-info p-button-text p-mr-2" @click="showTransactionHistory(props.row)" />
                        <Button icon="pi pi-trash" class="p-button-rounded p-button-danger p-button-text" @click="deleteCustomer(props.row.id)"/>
                    </div>

                    <!-- Remaining Columns -->
                    <span v-else>{{props.formattedRow[props.column.field]}}</span>
                </template>

                <template #emptystate>
                    <no-data-table>
                        <template slot="action">
                            <button @click="createForm = true" class="qt-btn-sm qt-btn-primary" type="button">New Customer</button>
                        </template>
                    </no-data-table>
                </template>
            </vue-good-table>

            <!-- Sidebar Forms -->
            <Sidebar position="right" v-model:visible="createForm" class="p-sidebar-md">
                <CustomerForm :form-errors="errors" :user-groups="userGroups" :service-units="serviceUnits" :cities="cities" @close="createForm = false;" :title="'New Customer'"/>
            </Sidebar>
            <Sidebar position="right" v-model:visible="editForm" class="p-sidebar-md">
                <CustomerForm :form-errors="errors" :user-groups="userGroups" :service-units="serviceUnits" :cities="cities" :edit-flag="editForm" @close="editForm = false;" :customer-id="currentId" :wallet-balance="currentWalletBalance" :title="'Edit Customer'"/>
            </Sidebar>
            <Sidebar position="right" v-model:visible="addressForm" class="p-sidebar-md">
                <CustomerAddressForm :form-errors="errors" :cities="cities" @close="addressForm = false;" :customer-id="currentId" :title="'Customer Address'"/>
            </Sidebar>
            <!-- Transaction History Modal -->
            <Transactions :visible="transactionHistoryModalVisible" :transactions="transactions" @update:visible="transactionHistoryModalVisible = $event" />
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import NoDataTable from "@/Components/NoDataTable";
    import Button from 'primevue/button';
    import Sidebar from 'primevue/sidebar';
    import CustomerForm from '@/Pages/Admin/Forms/CustomerForm';
    import CustomerAddressForm from '@/Pages/Admin/Forms/CustomerAddressForm';
    import Transactions from '../../Components/transactions.vue';

    export default defineComponent({
        components: {
            AppLayout,
            NoDataTable,
            Button,
            Sidebar,
            CustomerForm,
            CustomerAddressForm,
            Transactions
        },
        props: {
            customers: Object,
            errors: Object,
            userGroups: Array,
            cities: Array,
            serviceUnits: Array,
        },
        data() {
            return {
                createForm: false,
                editForm: false,
                addressForm: false,
                currentId: null,
                currentWalletBalance: 0,
                transactionHistoryModalVisible: false,
                transactions: [],
                columns: [
                    {
                        label: 'Unique Code',
                        field: 'code',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Search Name',
                            filterValue: null,
                            trigger: 'enter',
                        },
                        sortable: false,
                    },
                    {
                        label: 'Full Name',
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
                        label: 'Email',
                        field: 'email',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Search Email',
                            filterValue: null,
                            trigger: 'enter',
                        },
                        sortable: false,
                    },
                    {
                        label: 'Mobile',
                        field: 'mobile',
                        filterOptions: {
                            enabled: true,
                            placeholder: 'Search Mobile',
                            filterValue: null,
                            trigger: 'enter',
                        },
                        sortable: false,
                    },
                    {
                        label: 'City',
                        field: 'city',
                        sortable: false,
                    },
                    {
                        label: 'Wallet Balance',
                        field: 'balance',
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
                            filterDropdownItems: [{value: 1, text: 'Active'}, {value: 0, text: 'In-active'}],
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
                    perPage: this.customers.meta.pagination.per_page,
                    setCurrentPage: this.customers.meta.pagination.current_page,
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

            // Show transaction history in a modal for a specific customer
            showTransactionHistory(customer) {
                this.currentId = customer.id;
                this.transactions = customer.transactions || []; // Access transactions directly
                this.transactionHistoryModalVisible = true; // Open the modal
            },

            // Close the transaction history modal
            closeTransactionHistory() {
                this.transactionHistoryModalVisible = false;
                this.transactions = []; // Clear transactions when modal is closed
            },

           
            async ExportExcel() {
            // Fetch all the data at once
            await this.fetchAllData();

            // Extract the necessary data for export
            const exportData = this.customers.data.map(row => {
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
            link.setAttribute('download', 'customers');

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
            const totalRows = this.customers.meta.pagination.total;
            this.options.perPage = totalRows;
            this.options.currentPage = 1;

            // Wait for the table data to be fetched
            await new Promise(resolve => {
                this.$nextTick(resolve);
            });
        },
    //         loadItemsexport() {
           
    //        this.$inertia.get('http://laravel.test/admin/export-customer', this.getQueryParams(), {
    //           replace: false,
    //           preserveState: true,
    //           preserveScroll: true,
    //       });
         
    //    },
            deleteCustomer(id) {
                this.$confirm.require({
                    header: 'Confirm Delete',
                    message: 'Do you want to delete this record?',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Delete',
                    accept: () => {
                        this.$inertia.delete(route('customers.destroy', {id: id}), {}, {
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
