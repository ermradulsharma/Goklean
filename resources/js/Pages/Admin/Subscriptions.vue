<template>
    <app-layout title="Subscriptions">
        <template #header>
            <h4 class="page-heading">Subscriptions</h4>
        </template>
        <template #actions>
            <Button @click="ExportExcel('xlsx')" type="button" class="export-btn p-button-success mx-5" :label="'Export'" />
            <button @click="createForm = true" class="gk-btn gk-btn-success">
                New Subscription
            </button>
        </template>

        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <vue-good-table mode="remote" v-on:page-change="onPageChange" v-on:column-filter="onColumnFilter" v-on:per-page-change="onPerPageChange"
                            :pagination-options="options" :columns="columns"
                            :totalRows="subscriptions.meta.pagination.total"
                            :rows="subscriptions.data" :rtl="$page.props.rtl">
                <template #table-row="props">
                    <!-- Billing Cycles -->
                    <div v-if="props.column.field === 'total_billing_cycles'">
                        <span>{{ props.row.completed_billing_cycles }}/{{ props.row.total_billing_cycles }}</span>
                    </div>

                    <!-- Status Column -->
                    <div v-else-if="props.column.field === 'status'">
                        <span :class="[props.row.status === 'Active' ? 'badge-success' : 'badge-danger', 'badge']">{{ props.row.status }}</span>
                    </div>

                    <!-- Actions Column -->
                    <div v-else-if="props.column.field === 'actions'">
                        <actions-dropdown>
                            <template #actions>
                                <Link :href="route('subscriptions.show', {id: props.row.id})" class="action-item">Dashboard</Link>
                                <Link :href="route('subscriptions.edit', {id: props.row.id})" class="action-item">Edit</Link>
                                <button @click="deleteSubscription(props.row.id)" class="action-item">Delete</button>
                            </template>
                        </actions-dropdown>
                    </div>

                    <!-- Remaining Columns -->
                    <span v-else>
                              {{props.formattedRow[props.column.field]}}
                            </span>
                </template>

                <template #emptystate>
                    <no-data-table>
                        <template slot="action">
                            <button @click="createForm = true" class="qt-btn-sm qt-btn-primary" type="button">
                                New Subscription
                            </button>
                        </template>
                    </no-data-table>
                </template>
            </vue-good-table>

            <!-- Create Modal -->
            <jet-modal :show="createForm" @close="createForm = false;">
                <div class="h-96 w-full flex flex-col p-6">
                    <div class="w-full flex justify-between border-b border-gray-100 mb-4">
                        <h4 class="pb-2 text-lg font-semibold text-gray-800">New Subscription</h4>
                        <button @click="createForm = false">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="w-full flex flex-col mb-6">
                        <label for="customer_car_id" class="pb-2 font-semibold text-gray-800">Customer Car</label>
                        <VueSelect id="customer_car_id" v-model="customerCarId" :options="customerCars" @search="searchCustomerCars"
                                   :reduce="car => car.id" label="name">
                            <template v-slot:no-options="{ search, searching }">
                                <template v-if="searching">No results were found for this search.</template>
                                <em v-else class="opacity-50">Start typing to search.</em>
                            </template>
                        </VueSelect>
                    </div>
                    <div class="w-full flex flex-col mb-6">
                        <label for="plan_id" class="pb-2 font-semibold text-gray-800">Plan</label>
                        <VueSelect id="plan_id" v-model="planId" :options="plans"
                                   :reduce="plan => plan.id" label="name">
                            <template v-slot:no-options="{ search, searching }">
                                <template v-if="searching">No results were found for this search.</template>
                                <em v-else class="opacity-50">Start typing to search.</em>
                            </template>
                        </VueSelect>
                    </div>
                    <div class="flex justify-end">
                        <button @click="createSubscription" class="gk-btn gk-btn-success" :disable="!customerCarId">
                            Create Subscription
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
    import {Link} from "@inertiajs/inertia-vue3";
    import ActionsDropdown from "@/Components/ActionsDropdown";

    export default defineComponent({
        components: {
            AppLayout,
            NoDataTable,
            Button,
            Sidebar,
            JetModal,
            VueSelect,
            Tag,
            ActionsDropdown,
            Link
        },
        props: {
            subscriptions: Object,
            errors: Object,
            plans: Array
        },
        data() {
            return {
                createForm: false,
                editForm: false,
                currentId: null,
                customerCarId: null,
                customerCars: [],
                planId: null,
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
                            placeholder: 'Search Name',
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
                        label: 'Billing Cycles',
                        field: 'total_billing_cycles',
                        sortable: false,
                    },
                    {
                        label: 'Last Paid',
                        field: 'last_renewed_date',
                        sortable: false,
                    },
                    {
                        label: 'Next Renewal',
                        field: 'next_renew_date',
                        sortable: false,
                    },
                    {
                        label: 'Plan',
                        field: 'plan',
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
                    perPage: this.subscriptions.meta.pagination.per_page,
                    setCurrentPage: this.subscriptions.meta.pagination.current_page,
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

            async ExportExcel() {
            // Fetch all the data at once
            await this.fetchAllData();

            // Extract the necessary data for export
            const exportData = this.subscriptions.data.map(row => {
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
            link.setAttribute('download', 'subscriptions');

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
            const totalRows = this.subscriptions.meta.pagination.total;
            this.options.perPage = totalRows;
            this.options.currentPage = 1;

            // Wait for the table data to be fetched
            await new Promise(resolve => {
                this.$nextTick(resolve);
            });
        },

    //         loadItemsexport() {
           
    //        this.$inertia.get('http://laravel.test/admin/export-subscription', this.getQueryParams(), {
    //           replace: false,
    //           preserveState: true,
    //           preserveScroll: true,
    //       });
         
    //    },
            deleteSubscription(id) {
                this.$confirm.require({
                    header: 'Confirm Delete',
                    message: 'Do you want to delete this record?',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Delete',
                    accept: () => {
                        this.$inertia.delete(route('subscriptions.destroy', {id: id}), {}, {
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
            createSubscription() {
                if(this.customerCarId) {
                    this.$inertia.get(route('subscriptions.create'), {
                        customer_car_id: this.customerCarId,
                        plan_id: this.planId
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
