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
            <!-- Service Unit -->
            <div class="w-full flex flex-col mb-6">
                <label for="service_unit" class="pb-2 font-semibold text-gray-800">Service Unit <span class="ml-1 text-red-400">*</span></label>
                <Dropdown id="service_unit" v-model="form.service_unit" :options="serviceUnits" optionValue="id" optionLabel="name" placeholder="Select a Unit"
                          :filter="true" filterPlaceholder="Find Unit" :class="[errors.status ? 'p-invalid' : '']"/>
                <small id="service_unit-help" v-if="errors.service_unit" class="p-invalid">{{ errors.service_unit }}</small>
            </div>
            <!-- Status -->
            <div class="w-full flex flex-col mb-6">
                <label for="status" class="pb-2 font-semibold text-gray-800">Status <span class="ml-1 text-red-400">*</span></label>
                <Dropdown id="status" v-model="form.status" :options="statuses" optionValue="value" optionLabel="text" placeholder="Select a Status"
                          :filter="true" filterPlaceholder="Find Status" :class="[errors.status ? 'p-invalid' : '']"/>
                <small id="status-help" v-if="errors.status" class="p-invalid">{{ errors.status }}</small>
            </div>

            <!-- Submit Button -->
            <div class="w-full flex">
                <Button type="submit" :label="'Update'" />
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
    import Dropdown from "primevue/dropdown";
    export default defineComponent({
        name: 'UpdateBookingForm',
        components: {
            InputText,
            Button,
            Dropdown,
            InputSwitch,
            FormInputShimmer,
            FormSwitchShimmer
        },
        props: {
            editFlag: Boolean,
            bookingId: Number,
            formErrors: Object,
            statuses: Array,
            serviceUnits: Array,
            title: ''
        },
        data() {
            return {
                errors: {},
                form: {
                    service_unit: '',
                    status: '',
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
                this.formValidated = true;
                this.$inertia.patch(route('bookings.update', { id: this.bookingId }), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
            fetch() {
                let _this = this;
                _this.loading = true;
                axios.get(route('bookings.edit', { id: this.bookingId }))
                    .then(function (response) {
                        let data = response.data;
                        _this.form.service_unit = data.service_unit_id;
                        _this.form.status = data.status;
                    })
                    .catch(function (error) {
                        _this.loading = false;
                    })
                    .then(function () {
                        _this.loading = false;
                    });
            }
        },
        mounted() {
            this.fetch();
        }
    })
</script>
