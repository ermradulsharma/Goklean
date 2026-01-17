<template>
    <app-layout title="Car Brands">
        <template #header>
            <h4 class="font-bold text-2xl text-gray-900 leading-tight">Car Brands</h4>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Toolbar -->
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1"></div>
                        <button @click="createForm = true" class="gk-btn gk-btn-primary">
                            <i class="pi pi-plus mr-2"></i> New Brand
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="p-0">
                        <vue-good-table
                            mode="remote"
                            :columns="columns"
                            :rows="carBrands.data"
                            :totalRows="carBrands.meta.pagination.total"
                            :pagination-options="options"
                            :rtl="$page.props.rtl"
                            styleClass="vgt-table"
                            @page-change="onPageChange"
                            @per-page-change="onPerPageChange"
                            @column-filter="onColumnFilter"
                            @sort-change="onSortChange"
                        >
                            <template #table-row="props">
                                <!-- Name Column -->
                                <div v-if="props.column.field === 'name'" class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden border border-gray-200">
                                        <img v-if="props.row.logo_path" :src="props.row.logo_path" :alt="props.row.name" class="w-full h-full object-contain p-1">
                                        <span v-else class="text-gray-400 text-xs">No img</span>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ props.row.name }}</span>
                                </div>

                                <!-- Status Column -->
                                <div v-else-if="props.column.field === 'status'">
                                    <span :class="getStatusClass(props.row.status)">
                                        {{ props.row.status ? 'Active' : 'In-active' }}
                                    </span>
                                </div>

                                <!-- Actions Column -->
                                <div v-else-if="props.column.field === 'actions'">
                                    <div class="flex items-center gap-2 justify-end">
                                        <button @click="editForm = true; currentId = props.row.id;" class="text-gray-500 hover:text-blue-600 transition-colors p-1" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                         <button @click="deleteSection(props.row.id)" class="text-gray-500 hover:text-red-600 transition-colors p-1" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <span v-else class="text-gray-700 text-sm">{{ props.formattedRow[props.column.field] }}</span>
                            </template>

                            <template #emptystate>
                                <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <p class="text-lg font-medium">No car brands found</p>
                                </div>
                            </template>
                        </vue-good-table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Forms -->
            <Sidebar position="right" v-model:visible="createForm" class="p-sidebar-md" :baseZIndex="1000">
                <BrandForm :form-errors="errors" @close="createForm = false;" :title="'New Brand'"/>
            </Sidebar>
            <Sidebar position="right" v-model:visible="editForm" class="p-sidebar-md" :baseZIndex="1000">
                <BrandForm :form-errors="errors" :edit-flag="editForm" @close="editForm = false;" :brand-id="currentId" :title="'Edit Brand'"/>
            </Sidebar>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Button from 'primevue/button';
    import Sidebar from 'primevue/sidebar'
    import BrandForm from '@/Pages/Admin/Forms/BrandForm'

    export default defineComponent({
        components: {
            AppLayout,
            Button,
            Sidebar,
            BrandForm
        },
        props: {
            carBrands: Object,
            errors: Object,
        },
        data() {
            return {
                createForm: false,
                editForm: false,
                currentId: null,
                columns: [
                    { label: 'Name', field: 'name', sortable: false, filterOptions: { enabled: true, placeholder: 'Search...' } },
                    { label: 'Status', field: 'status', sortable: false, filterOptions: { enabled: true, placeholder: 'All', filterDropdownItems: [{value: 1, text: 'Active'}, {value: 0, text: 'In-active'}] } },
                    { label: '', field: 'actions', sortable: false, width: '100px' }
                ],
                options: {
                    enabled: true,
                    mode: 'pages',
                    perPage: this.carBrands.meta.pagination.per_page,
                    setCurrentPage: this.carBrands.meta.pagination.current_page,
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
            loadItems() {
                // Construct clean query params
                let params = {
                    page: this.serverParams.page,
                    perPage: this.serverParams.perPage
                };

                for (const [key, value] of Object.entries(this.serverParams.columnFilters)) {
                    if (value) params[key] = value;
                }

                this.$inertia.get(route(route().current()), params, {
                    replace: true,
                    preserveState: true,
                    preserveScroll: true,
                });
            },
            deleteSection(id) {
                this.$confirm.require({
                    header: 'Confirm Delete',
                    message: 'Do you want to delete this brand?',
                    icon: 'pi pi-exclamation-triangle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Delete',
                    accept: () => {
                        this.$inertia.delete(route('brands.destroy', id), {
                            onSuccess: () => {
                                this.$toast.add({ severity: 'info', summary: 'Deleted', detail: 'Brand deleted successfully', life: 3000 });
                            },
                        });
                    }
                });
            },
            getStatusClass(status) {
                return status
                    ? 'px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-bold uppercase'
                    : 'px-2 py-1 rounded bg-red-100 text-red-800 text-xs font-bold uppercase';
            }
        }
    })
</script>
