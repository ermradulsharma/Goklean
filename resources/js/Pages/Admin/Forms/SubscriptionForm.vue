<template>
    <app-layout :title="editFlag ? 'Edit Subscription': 'New Subscription'">
        <template #header>
            <h4 class="page-heading">{{ editFlag ? 'Edit Subscription': 'New Subscription'}}</h4>
        </template>

        <div class="container max-w-7xl flex gap-4 mx-auto pt-4 px-4 sm:px-6 lg:px-8">
            <div class="w-1/3 card">
                <div class="card-body">
                    <div class="w-full flex flex-col mb-6">
                        <label class="pb-2 font-semibold text-gray-800">Customer Details</label>
                        <ul>
                            <li>Name: {{ customer.name }}</li>
                            <li>Email: {{ customer.email }}</li>
                            <li>Mobile: {{ customer.mobile }}</li>
                        </ul>
                    </div>
                    <div class="w-full flex flex-col mb-6">
                        <label class="pb-2 font-semibold text-gray-800">Customer Car Details</label>
                        <ul>
                            <li>{{ car.name }}</li>
                            <li>Number Plate: {{ car.number }}</li>
                            <li>Color: {{ car.color }}</li>
                        </ul>
                    </div>
                    <!--Preferences -->
                    <table class="w-full table-auto">
                        <thead class="bg-gray-100 border-gray-200">
                        <tr>
                            <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Select</td>
                            <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Day</td>
                            <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Time</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="day in form.preferences.days" :key="day.name">
                            <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                <Checkbox :value="day.name" v-model="day.selected" :binary="true"/>
                            </td>
                            <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ day.name }}</td>
                            <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                <DatePicker class="w-full" mode="time" v-model="day.time" :model-config="timeModelConfig" :is24hr="false" :update-on-input="true" :minute-increment="5">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <InputText type="text" class="w-full" v-on="inputEvents" :value="inputValue"/>
                                    </template>
                                </DatePicker>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="w-2/3 card">
                <form @submit.prevent="submitForm">
                    <div class="card-body">
                        <div class="w-full flex items-center gap-4">
                            <!-- Order Type -->
                            <div class="w-full flex flex-col mb-6">
                                <label class="pb-2 font-semibold text-gray-800">Selected Plan</label>
                                <div class="w-full">
                                    {{ plan.name }}
                                </div>
                            </div>
                        </div>
                        <div v-if="form.products" class="w-full mb-6">
                            <table class="w-full table-auto">
                                <thead class="bg-gray-100 border-gray-200">
                                <tr>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Select</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Item</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Qty</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="product in form.products" :key="product.code">
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                        <Checkbox :value="product.name" v-model="product.selected" :binary="true" :disabled="product.code === 'GK-EXT-1W'"/>
                                    </td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ product.name }}</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                        <!-- <span v-if="product.code === 'GK-EXT-1W'">{{ product.qty }}</span> -->
                                        <InputNumber v-model="product.qty" showButtons buttonLayout="horizontal" :min="1" :max="4" decrementButtonClass="p-button-danger" incrementButtonClass="p-button-success" incrementButtonIcon="pi pi-plus" decrementButtonIcon="pi pi-minus" />
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <small id="product-help" v-if="errors.products" class="p-invalid">{{ errors.products }}</small>
                        </div>
                        <div class="w-full flex items-center gap-4">
                            <!-- Payment Mode -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="payment_mode" class="pb-2 font-semibold text-gray-800">Payment Mode <span class="ml-1 text-red-400">*</span></label>
                                <Dropdown id="payment_mode" v-model="form.payment_mode" :options="paymentModes" optionValue="code" optionLabel="name" placeholder="Select a Mode"
                                          :filter="true" filterPlaceholder="Find Mode" :class="[errors.payment_mode ? 'p-invalid' : '']"/>
                                <small id="payment_mode-help" v-if="errors.payment_mode" class="p-invalid">{{ errors.payment_mode }}</small>
                            </div>
                            <!-- Start Date -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="starts_at" class="pb-2 font-semibold text-gray-800">Start Date<span class="ml-1 text-red-400">*</span></label>
                                <DatePicker class="w-full" :mode="'dateTime'" :popover="popover" v-model="form.starts_at" :is24hr="false" :masks="masks" :update-on-input="true"
                                            :model-config="modelConfig" :minute-increment="5">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <InputText type="text" id="starts_at" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.starts_at ? 'p-invalid' : '']"/>
                                    </template>
                                </DatePicker>
                                <small id="starts_at-help" v-if="errors.starts_at" class="p-invalid">{{ errors.starts_at }}</small>
                            </div>
                        </div>
                        <div v-if="editFlag" class="w-full flex items-center gap-4">
                            <!-- Last Paid Date -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="last_renewed_date" class="pb-2 font-semibold text-gray-800">Last Paid Date</label>
                                <DatePicker class="w-full" :mode="'dateTime'" :popover="popover" v-model="form.last_renewed_date" :is24hr="false" :masks="masks" :update-on-input="true"
                                            :model-config="modelConfig" :minute-increment="5">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <InputText type="text" id="last_renewed_date" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.last_renewed_date ? 'p-invalid' : '']"/>
                                    </template>
                                </DatePicker>
                                <small id="last_renewed_date-help" v-if="errors.last_renewed_date" class="p-invalid">{{ errors.last_renewed_date }}</small>
                            </div>
                            <!-- Next Renew Date -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="next_renew_date" class="pb-2 font-semibold text-gray-800">Next Renew Date <span class="ml-1 text-red-400">*</span></label>
                                <DatePicker class="w-full" :mode="'dateTime'" :popover="popover" v-model="form.next_renew_date" :is24hr="false" :masks="masks" :update-on-input="true"
                                            :model-config="modelConfig" :minute-increment="5">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <InputText type="text" id="next_renew_date" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.next_renew_date ? 'p-invalid' : '']"/>
                                    </template>
                                </DatePicker>
                                <small id="next_renew_date-help" v-if="errors.next_renew_date" class="p-invalid">{{ errors.next_renew_date }}</small>
                            </div>
                        </div>
                        <div class="w-full flex items-center gap-4">
                            <!-- Status -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="status" class="pb-2 font-semibold text-gray-800">Status <span class="ml-1 text-red-400">*</span></label>
                                <Dropdown id="status" v-model="form.status" :options="statuses" optionValue="code" optionLabel="name" placeholder="Select a Status"
                                          :filter="true" filterPlaceholder="Find Status" :class="[errors.status ? 'p-invalid' : '']"/>
                                <small id="status-help" v-if="errors.status" class="p-invalid">{{ errors.status }}</small>
                            </div>
                            <!--Payment Date -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="total_billing_cycles" class="pb-2 font-semibold text-gray-800">Total Billing Cycles</label>
                                <InputNumber id="total_billing_cycles" v-model="form.total_billing_cycles" />
                                <small id="total_billing_cycles-help" v-if="errors.total_billing_cycles" class="p-invalid">{{ errors.total_billing_cycles }}</small>
                            </div>
                        </div>
                        <div class="w-full flex justify-end">
                            <button type="submit" class="gk-btn gk-btn-success">
                                {{ editFlag ? 'Update': 'Create'}} Subscription
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
    import InputNumber from "primevue/inputnumber";
    import InputSwitch from "primevue/inputswitch";
    import FormInputShimmer from "@/Components/Shimmers/FormInputShimmer";
    import FormSwitchShimmer from "@/Components/Shimmers/FormSwitchShimmer";
    import Dropdown from "primevue/dropdown";
    import {DatePicker} from "v-calendar";
    import VueSelect from "vue-select";
    import Checkbox from 'primevue/checkbox';
    export default defineComponent({
        components: {
            AppLayout,
            Button,
            RadioButton,
            InputText,
            InputNumber,
            InputSwitch,
            FormInputShimmer,
            FormSwitchShimmer,
            Dropdown,
            DatePicker,
            VueSelect,
            Checkbox
        },
        props: {
            editFlag: {
                type: Boolean,
                default: false
            },
            subscription: Object,
            errors: Object,
            customer: Object,
            car: Object,
            plan: Object,
            products: Array,
            paymentModes: Array,
            statuses: Array,
            preferences: Object|Array,
        },
        data() {
            return {
                form: {
                    customer_id: this.customer.id,
                    customer_car_id: this.car.id,
                    plan_id: this.plan.id,
                    payment_mode: this.editFlag ? this.subscription.payment_mode : 'offline',
                    starts_at: this.editFlag ? this.subscription.starts_at : '',
                    last_renewed_date: this.editFlag ? this.subscription.last_renewed_date : '',
                    next_renew_date: this.editFlag ? this.subscription.next_renew_date : '',
                    total_billing_cycles: this.editFlag ? this.subscription.total_billing_cycles : 12,
                    status: this.editFlag ? this.subscription.status : 'created',
                    products: this.products,
                    preferences: this.editFlag ? this.subscription.preferences : this.preferences,
                },
                days: [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'Sunday',
                ],
                masks: {
                    inputDateTime: ['DD/MM/YYYY HH:mm A'],
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
                this.editFlag ? this.update() : this.create();
            },
            create() {
                this.$inertia.post(route('subscriptions.store', {id: this.car.id}), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
            update() {
                this.$inertia.patch(route('subscriptions.update', {id: this.subscription.id}), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            }
        },
    })
</script>
