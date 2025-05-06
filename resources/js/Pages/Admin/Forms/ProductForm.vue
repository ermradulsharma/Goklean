<template>
    <div class="overflow-y-auto h-screen px-2">
        <div class="bg-gray-100 py-4 lg:py-4 rounded">
            <div class="container px-6 mx-auto flex flex-row">
                <div>
                    <h4 class="text-base font-semibold leading-tight text-gray-800">
                        {{ title }}
                    </h4>
                </div>
            </div>
        </div>
        <div v-if="loading" class="my-6 w-11/12 mx-auto xl:w-full xl:mx-0">
            <form-input-shimmer></form-input-shimmer>
            <form-input-shimmer></form-input-shimmer>
            <form-input-shimmer></form-input-shimmer>
            <form-switch-shimmer></form-switch-shimmer>
        </div>
        <form v-else class="my-6 w-11/12 mx-auto xl:w-full xl:mx-0" @submit.prevent="submitForm">
            <!-- Name -->
            <div class="w-full flex flex-col mb-6">
                <label for="name" class="pb-2 font-semibold text-gray-800">Product Name <span class="ml-1 text-red-400">*</span></label>
                <InputText type="text"
                           id="name"
                           v-model="form.name"
                           placeholder="Enter Name" aria-describedby="name-help"
                           :class="[errors.name ? 'p-invalid' : '']"

                />
                <small id="name-help" v-if="errors.name" class="p-invalid">{{ errors.name }}</small>
            </div>

            <!-- Code -->
            <div class="w-full flex flex-col mb-6">
                <label for="code" class="pb-2 font-semibold text-gray-800">Code <span class="ml-1 text-red-400">*</span></label>
                <InputText type="text"
                           id="code"
                           v-model="form.code"
                           placeholder="Enter Code" aria-describedby="code-help"
                           :class="[errors.code ? 'p-invalid' : '']" :disabled="editFlag"

                />
                <small id="code-help" v-if="errors.code" class="p-invalid">{{ errors.code }}</small>
            </div>

            <!-- Product Type -->
            <div class="w-full flex flex-col mb-6">
                <label for="product_type" class="pb-2 font-semibold text-gray-800">Product Type<span class="ml-1 text-red-400">*</span></label>
                <Dropdown id="product_type" v-model="form.product_type" :options="types" optionValue="id" optionLabel="name" placeholder="Select a Type"
                          :filter="true" filterPlaceholder="Find Type" :class="[errors.product_type ? 'p-invalid' : '']"/>
                <small id="product_type-help" v-if="errors.product_type" class="p-invalid">{{ errors.product_type }}</small>
            </div>

            <!-- Prices -->
            <div class="w-full flex flex-col mb-6">
                <label class="pb-2 font-semibold text-gray-800">Prices <span class="ml-1 text-red-400">*</span></label>
                <table>
                    <tbody>
                        <tr v-for="type in carTypes" :key="'price_'+type.id">
                            <th class="bg-white text-center border border-gray-200 px-4 py-2">
                                {{ type.name }}
                            </th>
                            <td class="text-center border border-gray-200 px-4 py-2">
                                <InputNumber v-model="form.prices[type.id]" mode="currency" currency="INR" currency-display="symbol"
                                             :minFractionDigits="0" :maxFractionDigits="0" locale="en-IN"></InputNumber>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Description -->
            <div class="w-full flex flex-col mb-6">
                <label for="description" class="pb-2 font-semibold text-gray-800">Description</label>
                <TextArea id="description"
                           v-model="form.description"
                           placeholder="Enter Description" aria-describedby="code-help"
                           :class="[errors.description ? 'p-invalid' : '']"

                />
                <small id="description-help" v-if="errors.description" class="p-invalid">{{ errors.description }}</small>
            </div>

            <!-- Sort Order -->
            <div class="w-full flex flex-col mb-6">
                <label for="sort_order" class="pb-2 font-semibold text-gray-800">Sort Order</label>
                <InputNumber id="sort_order"
                           v-model="form.sort_order"
                           placeholder="Enter Category Name" aria-describedby="sort_order-help"
                           :class="[errors.sort_order ? 'p-invalid' : '']"

                />
                <small id="sort_order-help" v-if="errors.sort_order" class="p-invalid">{{ errors.sort_order }}</small>
            </div>

            <!-- Is Popular -->
            <div class="w-full">
                <div class="flex justify-between items-center mb-8">
                    <div class="w-9/12">
                        <label for="is_popular" class="font-semibold text-gray-800 pb-1" v-html="form.is_popular ? 'Popular (Yes)' : 'Popular (No)'"></label>
                        <p class="text-gray-500">Yes (Shown as Popular)</p>
                    </div>
                    <div class="cursor-pointer rounded-full relative shadow-sm">
                        <InputSwitch id="is_popular" v-model="form.is_popular" />
                    </div>
                </div>
            </div>

            <!-- Is Active Switch -->
            <div class="w-full">
                <div class="flex justify-between items-center mb-8">
                    <div class="w-9/12">
                        <label for="is_active" class="font-semibold text-gray-800 pb-1" v-html="form.is_active ? 'Active' : 'In-active'"></label>
                        <p class="text-gray-500">Active (Shown Everywhere). In-active (Hidden Everywhere).</p>
                    </div>
                    <div class="cursor-pointer rounded-full relative shadow-sm">
                        <InputSwitch id="is_active" v-model="form.is_active" />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="w-full flex">
                <Button type="submit" :label="editFlag ? 'Update' : 'Create'" />
            </div>
        </form>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    import InputText from 'primevue/inputtext';
    import TextArea from 'primevue/textarea';
    import InputNumber from 'primevue/inputnumber';
    import Button from 'primevue/button';
    import InputSwitch from 'primevue/inputswitch';
    import FormInputShimmer from "@/Components/Shimmers/FormInputShimmer";
    import FormSwitchShimmer from "@/Components/Shimmers/FormSwitchShimmer";
    import Dropdown from 'primevue/dropdown';
    export default defineComponent({
        name: 'ProductForm',
        components: {
            InputText,
            InputNumber,
            TextArea,
            Button,
            InputSwitch,
            FormInputShimmer,
            FormSwitchShimmer,
            Dropdown
        },
        props: {
            editFlag: Boolean,
            productId: Number,
            formErrors: Object,
            carTypes: Array,
            title: '',
        },
        data() {
            return {
                errors: {},
                form: {
                    name: '',
                    code: '',
                    description: '',
                    product_type: null,
                    sort_order: 1,
                    is_popular: false,
                    is_active: true,
                    prices: []
                },
                types: [
                    {id: 'primary', name: 'Primary'},
                    {id: 'addon', name: 'Addon'},
                ],
                formValidated: false,
                loading: false,
            }
        },
        watch: {
            formErrors(val) {
                this.errors = val;
            },
        },
        methods: {
            submitForm() {
                this.editFlag ? this.update() : this.create();
            },
            create() {
                this.formValidated = true;
                this.$inertia.post(route('products.store'), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
            update() {
                this.formValidated = true;
                this.$inertia.patch(route('products.update', { id: this.productId }), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
            fetch() {
                if(this.editFlag) {
                    let _this = this;
                    _this.loading = true;
                    axios.get(route('products.edit', { id: this.productId }))
                        .then(function (response) {
                            let data = response.data.product;
                            _this.form.name = data.name;
                            _this.form.code = data.code;
                            _this.form.description = data.description;
                            _this.form.product_type = data.product_type;
                            _this.form.sort_order = data.sort_order;
                            _this.form.is_popular = data.is_popular;
                            _this.form.is_active = data.is_active;
                            _this.form.prices = response.data.prices;
                        })
                        .catch(function (error) {
                            _this.loading = false;
                        })
                        .then(function () {
                            _this.loading = false;
                        });
                }
            }
        },
        mounted() {
            if(this.editFlag === true) {
                this.fetch();
            }
        }
    })
</script>
