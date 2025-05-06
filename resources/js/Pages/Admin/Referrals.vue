<template>
    <app-layout title="Referrals">
        <template #header>
            <h4 class="page-heading">Referrals</h4>
        </template>

        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <vue-good-table mode="remote" v-on:page-change="onPageChange" v-on:column-filter="onColumnFilter" v-on:per-page-change="onPerPageChange"
                            :pagination-options="options" :columns="columns"
                            :totalRows="referrals.meta.pagination.total"
                            :rows="referrals.data" :rtl="$page.props.rtl">
                <template #table-row="props">
                    <!-- Code Column -->
                    <div v-if="props.column.field === 'code'">
                        <Tag v-clipboard:copy="props.row.code" v-clipboard:success="handleCopyStatus" :value="props.row.code" icon="pi pi-copy"
                             class="w-full p-mr-2 cursor-pointer"/>
                    </div>

                    <!-- Actions Column -->
                    <div v-else-if="props.column.field === 'actions'">
                        <Button v-if="!props.row.approved" class="p-button-sm p-button-success p-mr-2 mr-2" label="Approve" icon="pi pi-check" iconPos="right"
                                @click="approveReferral(props.row.id)"/>
                        <Button icon="pi pi-trash" class="p-button-rounded p-button-danger p-button-text"
                                @click="deleteReferral(props.row.id)"/>
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
                                New Referral
                            </button>
                        </template>
                    </no-data-table>
                </template>
            </vue-good-table>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import NoDataTable from "@/Components/NoDataTable";
    import Button from 'primevue/button';
    import Sidebar from 'primevue/sidebar';
    import Tag from "primevue/tag";

    export default defineComponent({
        components: {
            AppLayout,
            NoDataTable,
            Button,
            Sidebar,
            Tag
        },
        props: {
            referrals: Object,
            errors: Object,
            statuses: Array
        },
        data() {
            return {
                createForm: false,
                editForm: false,
                currentId: null,
                columns: [
                    {
                        label: 'Referral ID',
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
                        sortable: false,
                    },
                    {
                        label: 'Referred By',
                        field: 'referred_by',
                        sortable: false,
                    },
                    {
                        label: 'Amount',
                        field: 'amount',
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
                    perPage: this.referrals.meta.pagination.per_page,
                    setCurrentPage: this.referrals.meta.pagination.current_page,
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
            approveReferral(id) {
                this.$confirm.require({
                    header: 'Confirm Referral',
                    message: 'Do you want to approve this referral? Referral amount will be credit to customer wallet.',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Approve',
                    accept: () => {
                        this.$inertia.post(route('referrals.approve', {id: id}), {}, {
                            onSuccess: () => {
                                this.$toast.add({
                                    severity: 'info',
                                    summary: 'Confirmed',
                                    detail: 'Referral Approved',
                                    life: 3000
                                });
                            },
                        });
                    },
                    reject: () => {

                    }
                });
            },
            deleteReferral(id) {
                this.$confirm.require({
                    header: 'Confirm Delete',
                    message: 'Do you want to delete this record?',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Delete',
                    accept: () => {
                        this.$inertia.delete(route('referrals.destroy', {id: id}), {}, {
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
                if (status) {
                    this.$toast.add({
                        severity: 'success',
                        summary: 'Copied',
                        detail: status.text+' Copied Successfully!',
                        life: 3000
                    });
                }
            }
        }
    })
</script>
