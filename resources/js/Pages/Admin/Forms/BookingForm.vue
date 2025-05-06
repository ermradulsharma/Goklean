<template>
    <app-layout :title="editFlag ? 'Edit Booking': 'New Booking'">
        <template #header>
            <h4 class="page-heading">{{ editFlag ? 'Edit Booking': 'New Booking' }}</h4>
        </template>

        <div class="container max-w-7xl flex gap-4 mx-auto pt-4 px-4 sm:px-6 lg:px-8">
            <div class="w-1/3 card">
                <div class="card-body">
                    <div class="w-full flex flex-col mb-6">
                        <label class="pb-2 font-semibold text-gray-800">Invoice Details</label>
                        <table class="w-full table-auto">
                            <tbody>
                            <tr>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Invoice ID</td>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.invoice_id }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Customer</td>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.customer }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Customer Car</td>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.customer_car }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Booking Type</td>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.order_type }}</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Payment Details</td>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                    {{ invoice.total_price }} {{ invoice.status }} {{ invoice.payment_mode }} at {{ invoice.payment_date }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--Preferences-->
                    <div v-if="invoice.order_type === 'Bulk'" class="w-full flex flex-col mb-6">
                        <label class="pb-2 font-semibold text-gray-800">Preferred Days</label>
                        <table  class="w-full table-auto">
                            <thead class="bg-gray-100 border-gray-200">
                            <tr>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Day</td>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Time</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="day in preferences" :key="day.name">
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ day.name }}</td>
                                <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                    {{ day.time }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="w-full flex flex-col mb-6">
                        <label class="pb-2 font-semibold text-gray-800">Preferred Date & Time</label>
                        {{ preferences }}
                    </div>
                </div>
            </div>
            <div class="w-2/3 card">
                <form @submit.prevent="submitForm">
                    <div class="card-body">
                        <!--Products-->
                        <div class="w-full flex flex-col mb-6">
                            <label class="pb-2 font-semibold text-gray-800">Products</label>
                            <table  class="w-full table-auto">
                                <thead class="bg-gray-100 border-gray-200">
                                <tr>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Product</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Qty</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in invoice.items" :key="item.code">
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.name }}</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.qty }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="w-full flex items-center gap-4">
                            <!-- Service Unit -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="service_unit" class="pb-2 font-semibold text-gray-800">Service Unit <span class="ml-1 text-red-400">*</span></label>
                                <Dropdown id="service_unit" v-model="form.service_unit" :options="serviceUnits" optionValue="id" optionLabel="name" placeholder="Select a Unit"
                                          :filter="true" filterPlaceholder="Find Unit" :class="[errors.status ? 'p-invalid' : '']"/>
                                <small id="service_unit-help" v-if="errors.service_unit" class="p-invalid">{{ errors.service_unit }}</small>
                            </div>
                            <!--Booking Date -->
                            <div v-if="invoice.order_type === 'Single'" class="w-full flex flex-col mb-6">
                                <label for="date" class="pb-2 font-semibold text-gray-800">Booking Date</label>
                                <DatePicker class="w-full" :mode="'dateTime'" :popover="popover" v-model="form.dates" :is24hr="false" :masks="masks" :update-on-input="true"
                                            :model-config="modelConfig" :minute-increment="5">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <InputText type="text" id="date" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.dates ? 'p-invalid' : '']"/>
                                    </template>
                                </DatePicker>
                                <small id="date-help" v-if="errors.dates" class="p-invalid">{{ errors.dates }}</small>
                            </div>
                        </div>
                        <div v-if="invoice.order_type === 'Bulk'" class="w-full grid grid-cols-2 items-center gap-4">
                            <div v-for="(date, index) in form.dates" :key="'date_'+index" class="w-full flex flex-col mb-6">
                                <label :for="'date_'+index" class="pb-2 font-semibold text-gray-800">Wash {{ index+1 }} Booking Date</label>
                                <DatePicker class="w-full" :mode="'dateTime'" :popover="popover" v-model="dates[index]" :is24hr="false" :masks="masks" :update-on-input="true"
                                            :model-config="modelConfig" :minute-increment="5">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <InputText type="text" :id="'date_'+index" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.payment_date ? 'p-invalid' : '']"/>
                                    </template>
                                </DatePicker>
                            </div>
                        </div>
                        <div class="w-full flex justify-end">
                            <button type="submit" class="gk-btn gk-btn-success">
                                Create Bookings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Button from 'primevue/button';
    import RadioButton from 'primevue/radiobutton';
    import InputText from "primevue/inputtext";
    import InputSwitch from "primevue/inputswitch";
    import FormInputShimmer from "@/Components/Shimmers/FormInputShimmer";
    import FormSwitchShimmer from "@/Components/Shimmers/FormSwitchShimmer";
    import Dropdown from "primevue/dropdown";
    import {DatePicker} from "v-calendar";
    import VueSelect from "vue-select";
    import MultiSelect from 'primevue/multiselect';
    import Checkbox from "primevue/checkbox";
    export default defineComponent({
        components: {
            AppLayout,
            Button,
            RadioButton,
            InputText,
            InputSwitch,
            FormInputShimmer,
            FormSwitchShimmer,
            Dropdown,
            DatePicker,
            VueSelect,
            MultiSelect,
            Checkbox
        },
        props: {
            editFlag: {
                type: Boolean,
                default: false
            },
            invoice: Object,
            preferences: String|Array,
            dates: String|Array|Object,
            errors: Object,
            serviceUnits: Array,
            defaultServiceUnit: String|Number,
        },
        data() {
            return {
                form: {
                    invoice_id: this.invoice.id,
                    service_unit: this.defaultServiceUnit,
                    dates: this.dates,
                    booking_type: this.invoice.order_type
                },
                masks: {
                    inputDateTime: ['DD/MM/YYYY hh:mm A'],
                },
                modelConfig: {
                    type: 'string',
                    mask: 'YYYY-MM-DD HH:mm:ss', // Uses 'iso' if missing
                },
                timeModelConfig: {
                    type: 'string',
                    mask: 'HH:mm:ss', // Uses 'iso' if missing
                },
                popover: {
                    visibility: 'click'
                },
                loading: false,
            }
        },
        methods: {
            submitForm() {
                this.$inertia.post(route('bookings.store'), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
        },
    })
</script>
