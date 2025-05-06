<template>
    <app-layout title="Products">
        <template #header>
            <h4 class="page-heading">Products</h4>
        </template>
        <template #actions>
            <button @click="createForm = true" class="gk-btn gk-btn-success">
                New Product
            </button>
        </template>

        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <vue-good-table mode="remote" v-on:page-change="onPageChange" v-on:column-filter="onColumnFilter" v-on:per-page-change="onPerPageChange"
                            :pagination-options="options" :columns="columns"
                            :totalRows="products.meta.pagination.total"
                            :rows="products.data" :rtl="$page.props.rtl">
                <template #table-row="props">
                    <!-- Status Column -->
                    <div v-if="props.column.field === 'status'">
                        <span :class="[props.row.status ? 'badge-success' : 'badge-danger', 'badge']">{{ props.row.status ? 'Active' : 'In-active' }}</span>
                    </div>

                    <!-- Actions Column -->
                    <div v-else-if="props.column.field === 'actions'">
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-secondary p-button-text p-mr-2"
                                @click="editForm = true; currentId = props.row.id;"/>
                        <Button icon="pi pi-trash" class="p-button-rounded p-button-danger p-button-text"
                                @click="deleteProduct(props.row.id)"/>
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
                                New Product
                            </button>
                        </template>
                    </no-data-table>
                </template>
            </vue-good-table>

            <!-- Sidebar Forms -->
            <Sidebar position="right" v-model:visible="createForm" class="p-sidebar-md">
                <ProductForm :form-errors="errors" :car-types="carTypes" @close="createForm = false;" :title="'New Product'"/>
            </Sidebar>
            <Sidebar position="right" v-model:visible="editForm" class="p-sidebar-md">
                <ProductForm :form-errors="errors" :car-types="carTypes" :edit-flag="editForm" @close="editForm = false;"
                         :product-id="currentId" :title="'Edit Product'"/>
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
    import ProductForm from '@/Pages/Admin/Forms/ProductForm';

    export default defineComponent({
        components: {
            AppLayout,
            NoDataTable,
            Button,
            Sidebar,
            ProductForm
        },
        props: {
            products: Object,
            errors: Object,
            carTypes: Array,
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
                        label: 'Type',
                        field: 'type',
                        sortable: false,
                    },
                    {
                        label: 'Popular',
                        field: 'popular',
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
                    perPage: this.products.meta.pagination.per_page,
                    setCurrentPage: this.products.meta.pagination.current_page,
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
            deleteProduct(id) {
                this.$confirm.require({
                    header: 'Confirm Delete',
                    message: 'Do you want to delete this record?',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Delete',
                    accept: () => {
                        this.$inertia.delete(route('products.destroy', {id: id}), {}, {
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
        },
    })
</script>
