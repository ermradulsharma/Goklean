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
                <Dropdown id="service_unit" v-model="form.service_unit" :options="serviceUnits" optionValue="id" optionLabel="name" placeholder="Select a Unit" :filter="true" filterPlaceholder="Find Unit" :class="[errors.status ? 'p-invalid' : '']"/>
                <small id="service_unit-help" v-if="errors.service_unit" class="p-invalid">{{ errors.service_unit }}</small>
            </div>
            <!--Booking Date -->
            <div class="w-full flex flex-col mb-6">
                <label for="date" class="pb-2 font-semibold text-gray-800">Booking Date</label>
                <DatePicker class="w-full" :mode="'dateTime'" :popover="popover" v-model="form.date" :is24hr="false" :masks="masks" :update-on-input="true" :model-config="modelConfig" :minute-increment="5">
                    <template v-slot="{ inputValue, inputEvents }">
                        <InputText type="text" id="date" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.payment_date ? 'p-invalid' : '']"/>
                    </template>
                </DatePicker>
                <small id="date-help" v-if="errors.date" class="p-invalid">{{ errors.date }}</small>
            </div>
            <div class="w-full flex items-center gap-4">
                <!-- Status -->
                <div class="w-full flex flex-col mb-6">
                    <label for="status" class="pb-2 font-semibold text-gray-800">Status <span class="ml-1 text-red-400">*</span></label>
                    <Dropdown id="status" v-model="form.status" :options="statuses" optionValue="value" optionLabel="text" placeholder="Select a Status" :filter="true" filterPlaceholder="Find Status" :class="[errors.status ? 'p-invalid' : '']"/>
                    <small id="status-help" v-if="errors.status" class="p-invalid">{{ errors.status }}</small>
                </div>
                <!-- Reason -->
                <div class="w-full flex flex-col mb-6">
                    <label for="reason" class="pb-2 font-semibold text-gray-800">Reasons (If any)</label>
                    <Dropdown id="reason" v-model="form.reason" :options="reasons" optionValue="value" optionLabel="text" placeholder="Select a Reason" :filter="true" :showClear="true" filterPlaceholder="Find Reason" :class="[errors.reason ? 'p-invalid' : '']"/>
                    <small id="reason-help" v-if="errors.reason" class="p-invalid">{{ errors.reason }}</small>
                </div>
            </div>
            <!--Preferences -->
            <table class="w-full table-auto">
                <thead class="bg-gray-100 border-gray-200">
                <tr>
                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Select</td>
                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Product</td>
                    <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Status</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in form.items" :key="item.code">
                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                        <Checkbox v-model="item.enabled" :binary="true"/>
                    </td>
                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.name }}</td>
                    <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">
                        <!-- Status -->
                        <div class="w-full flex flex-col mb-6">
                            <label :for="'status_'+item.code" class="pb-2 font-semibold text-gray-800">Status <span class="ml-1 text-red-400">*</span></label>
                            <Dropdown :id="'status_'+item.code" v-model="item.status" :options="statuses" optionValue="value" optionLabel="text" placeholder="Select a Status" :filter="true" filterPlaceholder="Find Status"/>
                        </div>
                        <!-- Reason -->
                        <div class="w-full flex flex-col mb-6">
                            <label :for="'reason_'+item.code" class="pb-2 font-semibold text-gray-800">Reasons (If any)</label>
                            <Dropdown :id="'reason_'+item.code" v-model="item.reason" :options="reasons" optionValue="value" optionLabel="text" placeholder="Select a Reason" :filter="true" :showClear="true" filterPlaceholder="Find Reason"/>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

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
    import Checkbox from "primevue/checkbox";
    import {DatePicker} from "v-calendar";
    export default defineComponent({
        name: 'UpdateScheduleForm',
        components: {
            InputText,
            Button,
            Dropdown,
            InputSwitch,
            FormInputShimmer,
            FormSwitchShimmer,
            Checkbox,
            DatePicker
        },
        props: {
            editFlag: Boolean,
            scheduleId: Number,
            formErrors: Object,
            statuses: Array,
            reasons: Array,
            serviceUnits: Array,
            title: ''
        },
        data() {
            return {
                errors: {},
                form: {
                    service_unit: '',
                    status: '',
                    date: '',
                    items: [],
                },
                masks: {
                    inputDateTime: ['DD/MM/YYYY hh:mm A'],
                },
                modelConfig: {
                    type: 'string',
                    mask: 'YYYY-MM-DD HH:mm:ss', // Uses 'iso' if missing
                },
                popover: {
                    visibility: 'click'
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
                this.$inertia.patch(route('schedules.update', { id: this.scheduleId }), this.form, {
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
                axios.get(route('schedules.edit', { id: this.scheduleId }))
                    .then(function (response) {
                        let data = response.data.schedule;
                        _this.form.service_unit = data.service_unit_id;
                        _this.form.date = data.date;
                        _this.form.status = data.status;
                        _this.form.items = response.data.items;
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
