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
                <label for="name" class="pb-2 font-semibold text-gray-800">Plan Name <span class="ml-1 text-red-400">*</span></label>
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
                           :class="[errors.code ? 'p-invalid' : '']"

                />
                <small id="code-help" v-if="errors.code" class="p-invalid">{{ errors.code }}</small>
            </div>

            <!-- Plan Type -->
            <div class="w-full flex flex-col mb-6">
                <label for="wash_qty_per" class="pb-2 font-semibold text-gray-800">Wash Type<span class="ml-1 text-red-400">*</span></label>
                <Dropdown id="wash_qty_per" v-model="form.wash_qty_per" :options="types" optionValue="id" optionLabel="name" placeholder="Select a Type"
                          :filter="true" filterPlaceholder="Find Type" :class="[errors.wash_qty_per ? 'p-invalid' : '']"/>
                <small id="wash_qty_per-help" v-if="errors.wash_qty_per" class="p-invalid">{{ errors.wash_qty_per }}</small>
            </div>

            <!-- Qty -->
            <div class="w-full flex flex-col mb-6">
                <label for="wash_qty" class="pb-2 font-semibold text-gray-800">Wash Qty <span class="ml-1 text-red-400">*</span></label>
                <InputNumber
                           id="wash_qty"
                           v-model="form.wash_qty"
                           placeholder="Enter Wash Qty" aria-describedby="wash_qty-help"
                           :class="[errors.wash_qty ? 'p-invalid' : '']"

                />
                <small id="wash_qty-help" v-if="errors.wash_qty" class="p-invalid">{{ errors.wash_qty }}</small>
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
                           placeholder="Enter Value" aria-describedby="sort_order-help"
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
        name: 'PlanForm',
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
            planId: Number,
            formErrors: Object,
            title: '',
        },
        data() {
            return {
                errors: {},
                form: {
                    name: '',
                    code: '',
                    description: '',
                    wash_qty_per: null,
                    wash_qty: 1,
                    sort_order: 1,
                    is_popular: false,
                    is_active: true,
                },
                types: [
                    {id: 'weekly', name: 'Weekly'},
                    {id: 'monthly', name: 'Monthly'},
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
                this.$inertia.post(route('plans.store'), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
            update() {
                this.formValidated = true;
                this.$inertia.patch(route('plans.update', { id: this.planId }), this.form, {
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
                    axios.get(route('plans.edit', { id: this.planId }))
                        .then(function (response) {
                            let data = response.data;
                            _this.form.name = data.name;
                            _this.form.code = data.code;
                            _this.form.description = data.description;
                            _this.form.wash_qty_per = data.wash_qty_per;
                            _this.form.wash_qty = data.wash_qty;
                            _this.form.sort_order = data.sort_order;
                            _this.form.is_popular = data.is_popular;
                            _this.form.is_active = data.is_active;
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
