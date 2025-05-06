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
            <div v-if="form.schedules_count > 0" class="w-full">
                <InlineMessage class="w-full" severity="success">Total {{ form.schedules_count }} refundable schedules found for this booking.</InlineMessage>
                <div class="w-full flex flex-col mt-6 mb-6">
                    <label class="pb-2 font-semibold text-gray-800">Refund Type <span class="ml-1 text-red-400">*</span></label>
                    <div class="field-radiobutton mb-2">
                        <RadioButton id="full" name="refund_type" value="full" v-model="form.refund_type"></RadioButton>
                        <label class="pl-2" for="full">Calculated Amount (₹{{ form.refund_amount }})</label>
                    </div>
                    <div class="field-radiobutton">
                        <RadioButton id="custom" name="refund_type" value="custom" v-model="form.refund_type"></RadioButton>
                        <label class="pl-2" for="custom">Custom Amount</label>
                    </div>
                </div>
                <!-- Custom Amount -->
                <div v-if="form.refund_type === 'custom'" class="w-full flex flex-col mb-6">
                    <label for="custom_amount" class="pb-2 font-semibold text-gray-800">Custom Amount <span class="ml-1 text-red-400">*</span></label>
                    <InputNumber id="custom_amount" v-model="form.custom_amount" placeholder="Enter Custom Amount" :class="[errors.custom_amount ? 'p-invalid' : '']"/>
                    <small id="custom_amount-help" v-if="errors.custom_amount" class="p-invalid">{{ errors.custom_amount }}</small>
                </div>
                <!-- Notes -->
                <div class="w-full flex flex-col mb-6">
                    <label for="notes" class="pb-2 font-semibold text-gray-800">Notes</label>
                    <Textarea id="notes" v-model="form.notes" placeholder="Enter Notes/Reason/Message" :class="[errors.notes ? 'p-invalid' : '']"/>
                    <small id="notes-help" v-if="errors.notes" class="p-invalid">{{ errors.notes }}</small>
                </div>

                <!-- Submit Button -->
                <div class="w-full flex justify-end">
                    <Button type="submit" :label="'Refund'" />
                </div>
            </div>
            <InlineMessage v-else class="w-full" v-if="message" severity="warn">{{ message }}</InlineMessage>
        </form>
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
    export default defineComponent({
        name: 'BookingRefundForm',
        components: {
            Textarea,
            Button,
            InputNumber,
            RadioButton,
            FormInputShimmer,
            FormSwitchShimmer,
            InlineMessage
        },
        props: {
            bookingId: Number,
            formErrors: Object,
            title: ''
        },
        data() {
            return {
                errors: {},
                form: {
                    refund_type: 'full', // full or custom
                    schedules_count: 0,
                    refund_amount: 0,
                    custom_amount: 0,
                    notes: '',
                },
                message: null,
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
                this.$inertia.post(route('refunds.initiate', { id: this.bookingId }), this.form, {
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
                axios.get(route('refunds.calculate', { id: this.bookingId }))
                    .then(function (response) {
                        let data = response.data;
                        if(data.success) {
                            _this.form.schedules_count = data.refund.schedules_count;
                            _this.form.refund_amount = data.refund.refund_amount;
                        } else {
                            _this.message = data.message;
                        }
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
            this.message = null;
            this.fetch();
        }
    })
</script>
