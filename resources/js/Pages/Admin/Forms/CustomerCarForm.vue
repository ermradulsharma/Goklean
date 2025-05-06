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
            <!-- Customer -->
            <div class="w-full flex flex-col mb-6">
                <label for="customer_id" class="pb-2 font-semibold text-gray-800">Customer <span class="ml-1 text-red-400">*</span></label>
                <Dropdown id="customer_id" v-model="form.customer_id" :options="customers" optionValue="id" optionLabel="name" placeholder="Select a Customer"
                          :filter="true" filterPlaceholder="Find Customer" :class="[errors.customer_id ? 'p-invalid' : '']"/>
                <small id="customer_id-help" v-if="errors.customer_id" class="p-invalid">{{ errors.customer_id }}</small>
            </div>

            <!-- Car Model -->
            <div class="w-full flex flex-col mb-6">
                <label for="car_id" class="pb-2 font-semibold text-gray-800">Car Model <span class="ml-1 text-red-400">*</span></label>
                <Dropdown id="car_id" v-model="form.car_id" :options="cars" optionValue="id" optionLabel="name" placeholder="Select a Car"
                          :filter="true" filterPlaceholder="Find Car" :class="[errors.car_id ? 'p-invalid' : '']"/>
                <small id="car_id-help" v-if="errors.car_id" class="p-invalid">{{ errors.car_id }}</small>
            </div>

            <!-- Color -->
            <div class="w-full flex flex-col mb-6">
                <label for="color" class="pb-2 font-semibold text-gray-800">Color <span class="ml-1 text-red-400">*</span></label>
                <Dropdown id="color" v-model="form.color" :options="colors" optionValue="id" optionLabel="name" placeholder="Select a Color"
                          :filter="true" filterPlaceholder="Find Color" :class="[errors.color ? 'p-invalid' : '']"/>
                <small id="color-help" v-if="errors.color" class="p-invalid">{{ errors.color }}</small>
            </div>

            <!-- Number Plate -->
            <div class="w-full flex flex-col mb-6">
                <label for="number_plate" class="pb-2 font-semibold text-gray-800">Car Reg. No <span class="ml-1 text-red-400">*</span></label>
                <InputText type="text"
                           id="number_plate"
                           v-model="form.number_plate"
                           placeholder="Enter Number" aria-describedby="number_plate-help"
                           :class="[errors.number_plate ? 'p-invalid' : '']"

                />
                <small id="name-help" v-if="errors.number_plate" class="p-invalid">{{ errors.number_plate }}</small>
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
    import Button from 'primevue/button';
    import InputSwitch from 'primevue/inputswitch';
    import FormInputShimmer from "@/Components/Shimmers/FormInputShimmer";
    import FormSwitchShimmer from "@/Components/Shimmers/FormSwitchShimmer";
    import Dropdown from 'primevue/dropdown';
    export default defineComponent({
        name: 'CustomerCarForm',
        components: {
            InputText,
            Button,
            InputSwitch,
            FormInputShimmer,
            FormSwitchShimmer,
            Dropdown
        },
        props: {
            editFlag: Boolean,
            customerCarId: Number,
            customers: Array,
            cars: Array,
            colors: Array,
            formErrors: Object,
            title: '',
        },
        data() {
            return {
                errors: {},
                form: {
                    customer_id: null,
                    car_id: null,
                    color: '',
                    number_plate: '',
                    is_active: true,
                },
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
                this.$inertia.post(route('customer-cars.store'), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
            update() {
                this.formValidated = true;
                this.$inertia.patch(route('customer-cars.update', { id: this.customerCarId }), this.form, {
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
                    axios.get(route('customer-cars.edit', { id: this.customerCarId }))
                        .then(function (response) {
                            let data = response.data;
                            _this.form.customer_id = data.customer_id;
                            _this.form.car_id = data.car_id;
                            _this.form.number_plate = data.number_plate;
                            _this.form.color = data.color;
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
