<template>
    <app-layout :title="editFlag ? 'Edit Invoice': 'New Invoice'">
        <template #header>
            <h4 class="page-heading">{{ editFlag ? 'Edit Invoice': 'New Invoice'}}</h4>
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
                    <!-- Products -->
                    <div v-if="!editFlag" class="w-full flex flex-col mb-6">
                        <label for="products" class="pb-2 font-semibold text-gray-800">Choose Products <span class="ml-1 text-red-400">*</span></label>
                        <VueSelect taggable multiple id="products" v-model="form.products" :options="products" label="name" placeholder="Select Products" />
                        <small id="products-help" v-if="errors.products" class="p-invalid">{{ errors.products }}</small>                       
                    </div>
                    <!--Preferences -->
                    <table v-if="invoice && invoice.subscription_id" class="w-full table-auto">
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
                    <div v-else class="w-full flex flex-col mb-6">
                        <label for="preferred_date_time" class="pb-2 font-semibold text-gray-800">Preferred Date & Time</label>
                        <DatePicker class="w-full" :mode="'dateTime'" :popover="popover" v-model="form.preferences.date_time" :is24hr="false" :masks="masks" :update-on-input="true" :model-config="modelConfig" :minute-increment="5">
                            <template v-slot="{ inputValue, inputEvents }">
                                <InputText type="text" id="preferred_date_time" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.preferred_date_time ? 'p-invalid' : '']"/>
                            </template>
                        </DatePicker>
                        <small id="preferred_date_time-help" v-if="errors.preferred_date_time" class="p-invalid">{{ errors.preferred_date_time }}</small>
                    </div>
                </div>
            </div>
            <div class="w-2/3 card">
                <form @submit.prevent="submitForm">
                    <div class="card-body">
                        <div class="w-full flex items-center gap-4">
                            <!-- Order Type -->
                            <div class="w-full flex flex-col mb-6">
                                <label class="pb-2 font-semibold text-gray-800">Order Type <span class="ml-1 text-red-400">*</span></label>
                                <div class="w-full flex items-center gap-4">
                                    <div v-for="type of orderTypes" :key="type.code" class="field-radiobutton">
                                        <RadioButton :id="type.code" name="category" :value="type.code" v-model="form.order_type" :disabled="editFlag"></RadioButton>
                                        <label class="ml-1" :for="type.code">{{type.name}}</label>
                                    </div>
                                </div>
                            </div>
                            <!-- Booking Status -->
                            <div class="w-full">
                                <div class="flex justify-between items-center mb-8">
                                    <div class="w-9/12">
                                        <label for="booking_completed" class="font-semibold text-gray-800 pb-1">Booking Completed</label>
                                        <p class="text-gray-500">{{ form.booking_completed ? 'Yes' : 'No' }}</p>
                                    </div>
                                    <div class="cursor-pointer rounded-full relative shadow-sm">
                                        <InputSwitch id="booking_completed" v-model="form.booking_completed" :disabled="true"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="form.products" class="w-full mb-6">
                            <table class="w-full table-auto">
                                <thead class="bg-gray-100 border-gray-200">
                                <tr>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Item</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm text-right">Price</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Qty</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm text-right">Total Price</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="product in form.products" :key="product.code">
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ product.name }}</td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm text-right">
                                        ₹{{ product.price }}
                                    </td>
                                    <td v-if="invoice && invoice.status === 'paid'" class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ product.qty }}</td>
                                    <td v-else class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                                        <InputNumber v-model="product.qty" showButtons buttonLayout="horizontal" :min="1" :max="4" decrementButtonClass="p-button-danger" incrementButtonClass="p-button-success" incrementButtonIcon="pi pi-plus" decrementButtonIcon="pi pi-minus" />
                                    </td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm text-right">
                                        <span v-if="product.has_discount"><span class="line-through">₹{{ product.qty * product.price }}</span> ₹{{ product.qty * product.discounted_price }}</span>
                                        <span v-else>₹{{ product.qty * product.price }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="px-4 py-2 text-gray-600 text-sm"></td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm text-right"><strong>Total</strong></td>
                                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm text-right">₹{{ totalPrice }}</td>
                                </tr>
                                </tbody>
                            </table>
                            <small id="product-help" v-if="errors.products" class="p-invalid">{{ errors.products }}</small>
                        </div>
                        <div class="w-full flex items-center gap-4">
                            <!-- Payment Mode -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="payment_mode" class="pb-2 font-semibold text-gray-800">Payment Mode <span class="ml-1 text-red-400">*</span></label>
                                <Dropdown id="payment_mode" v-model="form.payment_mode" :options="paymentModes" optionValue="code" optionLabel="name" placeholder="Select a Mode" :filter="true" filterPlaceholder="Find Mode" :class="[errors.payment_mode ? 'p-invalid' : '']"/>
                                <small id="payment_mode-help" v-if="errors.payment_mode" class="p-invalid">{{ errors.payment_mode }}</small>
                            </div>
                            <!-- Due Date -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="due_date" class="pb-2 font-semibold text-gray-800">Due Date<span class="ml-1 text-red-400">*</span></label>
                                <DatePicker class="w-full" :mode="'dateTime'" :popover="popover" v-model="form.due_date" :is24hr="false" :masks="masks" :update-on-input="true" :model-config="modelConfig" :minute-increment="5">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <InputText type="text" id="due_date" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.due_date ? 'p-invalid' : '']"/>
                                    </template>
                                </DatePicker>
                                <small id="due_date-help" v-if="errors.due_date" class="p-invalid">{{ errors.due_date }}</small>
                            </div>
                        </div>
                        <div class="w-full flex items-center gap-4">
                            <!-- Razorpay Order Reference ID -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="reference_id" class="pb-2 font-semibold text-gray-800">Razorpay Order ID</label>
                                <InputText type="text" id="reference_id" v-model="form.reference_id" aria-describedby="reference_id-help" :class="[errors.reference_id ? 'p-invalid' : '']" />
                                <small id="reference_id-help" v-if="errors.reference_id" class="p-invalid">{{ errors.reference_id }}</small>
                            </div>
                            <!-- Transaction ID -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="transaction_id" class="pb-2 font-semibold text-gray-800">Transaction ID <span v-if="form.payment_mode !== 'offline'" class="text-red-400">*</span></label>
                                <InputText type="text" id="transaction_id" v-model="form.transaction_id" aria-describedby="transaction_id-help" :class="[errors.transaction_id ? 'p-invalid' : '']" />
                                <small id="transaction_id-help" v-if="errors.transaction_id && form.payment_mode !== 'offline'" class="p-invalid">{{ errors.transaction_id }}</small>
                            </div>
                        </div>
                        <div class="w-full flex items-center gap-4">
                            <!-- Status -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="status" class="pb-2 font-semibold text-gray-800">Status <span class="ml-1 text-red-400">*</span></label>
                                    <Dropdown id="status" v-model="form.status" :options="statuses" optionValue="code" optionLabel="name" placeholder="Select a Status" :filter="true" filterPlaceholder="Find Status" :class="[errors.status ? 'p-invalid' : '']" />
                                <small id="status-help" v-if="errors.status" class="p-invalid">{{ errors.status }}</small>
                            </div>
                            <!--Payment Date -->
                            <div class="w-full flex flex-col mb-6">
                                <label for="payment_date" class="pb-2 font-semibold text-gray-800">Payment Date</label>
                                <DatePicker class="w-full" :mode="'dateTime'" :popover="popover" v-model="form.payment_date" :is24hr="false" :masks="masks" :update-on-input="true" :model-config="modelConfig" :minute-increment="5">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <InputText type="text" id="payment_date" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.payment_date ? 'p-invalid' : '']"/>
                                    </template>
                                </DatePicker>
                                <small id="payment_date-help" v-if="errors.payment_date" class="p-invalid">{{ errors.payment_date }}</small>
                            </div>
                        </div>
                        <div class="w-full flex justify-end">
                            <button type="submit" class="gk-btn gk-btn-success">{{ editFlag ? 'Update': 'Generate'}} Invoice</button>
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
    import InputNumber from "primevue/inputnumber";
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
            Checkbox,
            InputNumber
        },
        props: {
            editFlag: {
                type: Boolean,
                default: false
            },
            invoice: Object,
            errors: Object,
            customer: Object,
            car: Object,
            products: Array,
            orderTypes: Array,
            paymentModes: Array,
            statuses: Array,
            preferences: Object|Array,
        },
        data() {
            return {
                form: {
                    customer_id: this.customer.id,
                    customer_car_id: this.car.id,
                    order_type: this.editFlag ? this.invoice.order_type : 'single',
                    due_date: this.editFlag ? this.invoice.due_date : '',
                    payment_date: this.editFlag ? this.invoice.payment_date : '',
                    payment_mode: this.editFlag ? this.invoice.payment_mode : 'offline',
                    reference_id: this.editFlag ? this.invoice.reference_id : '',
                    transaction_id: this.editFlag ? this.invoice.transaction_id : '',
                    status: this.editFlag ? this.invoice.status : 'created',
                    booking_completed: this.editFlag ? this.invoice.booking_completed : false,
                    products: this.editFlag ? this.invoice?.data?.items || [] : [],
                    preferences: this.editFlag ? this.invoice.preferences : this.preferences,
                },
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
                if (this.form.payment_mode !== 'offline' && !this.form.transaction_id) {
                    this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Transaction ID is required for online payments.', life: 3000 });
                    return;
                }
                if (!this.form.products.length) {
                    this.$toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'Please select at least one product.',
                        life: 3000
                    });
                    return;
                }
                this.editFlag ? this.update() : this.create();
            },
            create() {
                this.$inertia.post(route('invoices.store'), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
            update() {
                this.$inertia.patch(route('invoices.update', {id: this.invoice.id}), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            }
        },
        computed: {
            totalPrice() {
                return this.form.products.reduce((total, product) => {
                    const qty = Number(product.qty) || 0;
                    const price = product.has_discount ? product.discounted_price : product.price;
                    return total + qty * price;
                }, 0);
            }
        }
    })
</script>
