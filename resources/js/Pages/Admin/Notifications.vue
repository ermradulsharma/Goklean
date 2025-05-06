<template>
    <app-layout title="Customers">
        <template #header>
            <h4 class="page-heading">Notification</h4>
        </template>
        <template #actions>
           
            <button @click="sendNotification()" class="gk-btn gk-btn-success">
                Send Notification
            </button>
        </template>

        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <vue-good-table mode="remote" v-on:page-change="onPageChange" v-on:column-filter="onColumnFilter" v-on:per-page-change="onPerPageChange"
                            :pagination-options="options" :columns="columns"
                            :totalRows="customers.meta.pagination.total"
                            :rows="customers.data" :rtl="$page.props.rtl">
                <template #table-row="props">

                    <!-- Status Column -->
                    <div v-if="props.column.field === 'status'">
                        <span :class="[props.row.status ? 'badge-success' : 'badge-danger', 'badge']">{{ props.row.status ? 'Active' : 'In-active' }}</span>
                    </div>

                    <!-- Actions Column -->
                    

                    <!-- Remaining Columns -->
                    <span v-else>
                              {{props.formattedRow[props.column.field]}}
                            </span>
                </template>

                <template #emptystate>
                    <no-data-table>
                        <template slot="action">
                            <button @click="createForm = true" class="qt-btn-sm qt-btn-primary" type="button">
                                Send Notification
                            </button>
                        </template>
                    </no-data-table>
                </template>
            </vue-good-table>

            <!-- Sidebar Forms -->
            <Sidebar position="right" v-model:visible="createForm" class="p-sidebar-md">
                <CustomerForm :form-errors="errors" :user-groups="userGroups" :service-units="serviceUnits" :cities="cities"
                              @close="createForm = false;" :title="'New Customer'"/>
            </Sidebar>
            <Sidebar position="right" v-model:visible="editForm" class="p-sidebar-md">
                <CustomerForm :form-errors="errors" :user-groups="userGroups" :service-units="serviceUnits" :cities="cities"
                              :edit-flag="editForm" @close="editForm = false;" :customer-id="currentId" :title="'Edit Customer'"/>
            </Sidebar>
            <Sidebar position="right" v-model:visible="addressForm" class="p-sidebar-md">
                <CustomerAddressForm :form-errors="errors" :cities="cities"
                              @close="addressForm = false;" :customer-id="currentId" :title="'Customer Address'"/>
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

    export default defineComponent({
        components: {
            AppLayout,
            NoDataTable,
            Button,
            Sidebar,
        },
        props: {
            customers: Object,
            errors: Object,
            userGroups: Array,
            cities: Array,
            serviceUnits: Array
        },
        data() {
            return {
                createForm: false,
                editForm: false,
                addressForm: false,
                currentId: null,
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
            sendNotification(){
                this.notification(); 
            },
            notification()
            {
                window.location.href = "http://laravel.test/admin/send-notification";
            },
            
            
        }
    })
</script>
