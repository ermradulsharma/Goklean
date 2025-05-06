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
        <div class="w-full flex flex-col mb-6">
            <label class="pb-2 font-semibold text-gray-800">Schedules Type <span class="ml-1 text-red-400">*</span></label>
            <div class="w-full flex items-center gap-4">
                <div class="field-radiobutton">
                    <RadioButton id="new" name="category" value="new" v-model="selectedScheduleType"></RadioButton>
                    <label class="ml-1" :for="'new'">New Schedules</label>
                </div>
                <div class="field-radiobutton">
                    <RadioButton id="exist" name="category" value="exist" v-model="selectedScheduleType"></RadioButton>
                    <label class="ml-1" :for="'exist'">Exists Schedules</label>
                </div>
            </div>
        </div>
        <div v-if="selectedScheduleType === 'exist'" class="w-full flex flex-col">
            <label for="customer_car_id" class="pb-2 font-semibold text-gray-800">Schedules ID</label>
            <Dropdown id="exist_schedules" v-model="scheduleId" :options="schedules" optionValue="code" optionLabel="code" placeholder="Choose an existing schedule" />
        </div>
        
        <div v-else-if="selectedScheduleType === 'new'" class="overflow-y-auto h-screen px-2">
            <form class="my-6 w-11/12 mx-auto xl:w-full xl:mx-0" @submit.prevent="submitForm">
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

        <div v-else-if="selectedScheduleType === 'new'" class="overflow-y-auto h-screen px-2">
            <form class="my-6 w-11/12 mx-auto xl:w-full xl:mx-0" @submit.prevent="submitForm">
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
                <!-- Submit Button -->
                <div class="w-full flex">
                    <Button type="submit" :label="'Update'" />
                </div>
            </form>
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
            <tr v-for="item in schedules.items" :key="schedules.code">
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
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    import Textarea from 'primevue/textarea';
    import InputNumber from 'primevue/inputnumber';
    import InlineMessage from 'primevue/inlinemessage';
    import Button from 'primevue/button';
    import FormInputShimmer from "@/Components/Shimmers/FormInputShimmer";
    import FormSwitchShimmer from "@/Components/Shimmers/FormSwitchShimmer";
    import RadioButton from 'primevue/radiobutton';
    import VueSelect from "vue-select";
    import InputText from 'primevue/inputtext';
    import InputSwitch from 'primevue/inputswitch';
    import Dropdown from "primevue/dropdown";
    import Checkbox from "primevue/checkbox";
    import {DatePicker} from "v-calendar";
    export default defineComponent({
        name: 'ScheduleForm',
        components: {
            Textarea,
            Button,
            InputNumber,
            RadioButton,
            FormInputShimmer,
            FormSwitchShimmer,
            InlineMessage,
            VueSelect,
            InputText,
            Dropdown,
            InputSwitch,
            Checkbox,
            DatePicker
        },
        props: {
            schedules: Array,
            booking: Object,
            bookingId: Number,
            formErrors: Object,
            serviceUnits: Array,
            statuses: Array,
            reasons: Array,
            title: {
                type: String,
                default: 'Schedule Form'
            }
        },
        data() {
            return {
                selectedScheduleType: 'new',
                scheduleId: null,
                form: {
                    service_unit: null,
                    date: null,
                    status: null,
                    reason: null,
                    items: []
                },
                errors: {},
                message: null,
                formValidated: false,
                loading: false,
                masks: {
                    input: 'YYYY-MM-DD hh:mm A'
                },
                popover: {
                    visibility: 'click',
                    placement: 'bottom'
                },
                modelConfig: {
                    type: 'string',
                    mask: 'YYYY-MM-DD HH:mm'
                }
            };
        },
        computed: {

        },
        watch: {
            selectedScheduleType(val) {
                if (val === 'new') {
                    this.resetForm();
                }
            },
            formErrors(val) {
                this.errors = val;
            }
        },
        methods: {
            resetForm() {
                // Reset the form's data or perform necessary actions
                this.form = {
                    service_unit: null,
                    date: null,
                    status: null,
                    reason: null,
                    items: []
                };
                this.errors = {}; // Optionally clear any errors
            }
        },
    })
</script>
