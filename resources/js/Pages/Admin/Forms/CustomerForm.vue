<template>
    <div class="h-screen px-2">
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
            <!-- First Name -->
            <div class="w-full flex flex-col mb-6">
                <label for="first_name" class="pb-2 font-semibold text-gray-800">First Name <span class="ml-1 text-red-400">*</span></label>
                <InputText type="text"
                           id="first_name"
                           v-model="form.first_name"
                           placeholder="Enter First Name" aria-describedby="first_name-help"
                           :class="[errors.first_name ? 'p-invalid' : '']"

                />
                <small id="first_name-help" v-if="errors.first_name" class="p-invalid">{{ errors.first_name }}</small>
            </div>

            <!-- Last Name -->
            <div class="w-full flex flex-col mb-6">
                <label for="last_name" class="pb-2 font-semibold text-gray-800">Last Name <span class="ml-1 text-red-400">*</span></label>
                <InputText type="text"
                           id="last_name"
                           v-model="form.last_name"
                           placeholder="Enter Last Name" aria-describedby="last_name-help"
                           :class="[errors.last_name ? 'p-invalid' : '']"

                />
                <small id="last_name-help" v-if="errors.last_name" class="p-invalid">{{ errors.last_name }}</small>
            </div>

            <!-- Email -->
            <div class="w-full flex flex-col mb-6">
                <label for="email" class="pb-2 font-semibold text-gray-800">Email <span class="ml-1 text-red-400">*</span></label>
                <InputText type="email"
                           id="email"
                           v-model="form.email"
                           placeholder="Enter Email" aria-describedby="email-help"
                           :class="[errors.email ? 'p-invalid' : '']"

                />
                <small id="email-help" v-if="errors.email" class="p-invalid">{{ errors.email }}</small>
            </div>

            <!-- Mobile -->
            <div class="w-full flex flex-col mb-6">
                <label for="mobile" class="pb-2 font-semibold text-gray-800">Mobile <span class="ml-1 text-red-400">*</span></label>
                <InputText type="number"
                           id="mobile"
                           v-model="form.mobile"
                           placeholder="Enter Mobile" aria-describedby="mobile-help"
                           :class="[errors.mobile ? 'p-invalid' : '']"

                />
                <small id="mobile-help" v-if="errors.mobile" class="p-invalid">{{ errors.mobile }}</small>
            </div>

            <!-- Password -->
            <div class="w-full flex flex-col mb-6">
                <label for="password" class="pb-2 font-semibold text-gray-800">Password <span v-if="!editFlag" class="ml-1 text-red-400">*</span></label>
                <InputText type="password"
                           id="password"
                           v-model="form.password"
                           placeholder="Enter Password" aria-describedby="password-help"
                           :class="[errors.password ? 'p-invalid' : '']"

                />
                <small id="password-help" v-if="errors.password" class="p-invalid">{{ errors.password }}</small>
            </div>

            <!-- User Group -->
            <div class="w-full flex flex-col mb-6">
                <label for="user_group_id" class="pb-2 font-semibold text-gray-800">User Group</label>
                <Dropdown id="user_group_id" v-model="form.user_group_id" :options="userGroups" optionValue="id" optionLabel="name" placeholder="Select a Group"
                          :filter="true" filterPlaceholder="Find Group" :showClear="true" :class="[errors.user_group_id ? 'p-invalid' : '']"/>
                <small id="user_group_id-help" v-if="errors.user_group_id" class="p-invalid">{{ errors.user_group_id }}</small>
            </div>

            <!-- City -->
            <div class="w-full flex flex-col mb-6">
                <label for="city_id" class="pb-2 font-semibold text-gray-800">City<span class="ml-1 text-red-400">*</span></label>
                <Dropdown id="city_id" v-model="form.city_id" :options="cities" optionValue="id" optionLabel="name" placeholder="Select a City"
                          :filter="true" filterPlaceholder="Find City" :class="[errors.city_id ? 'p-invalid' : '']"/>
                <small id="city_id-help" v-if="errors.city_id" class="p-invalid">{{ errors.city_id }}</small>
            </div>

            <!-- Service Unit -->
            <div class="w-full flex flex-col mb-6">
                <label for="service_unit_id" class="pb-2 font-semibold text-gray-800">Service Unit</label>
                <Dropdown id="service_unit_id" v-model="form.service_unit_id" :options="serviceUnits" optionValue="id" optionLabel="name" placeholder="Select a Unit" :filter="true" filterPlaceholder="Find Unit" :class="[errors.service_unit_id ? 'p-invalid' : '']"/>
                <small id="service_unit_id-help" v-if="errors.service_unit_id" class="p-invalid">{{ errors.service_unit_id }}</small>
            </div>

            <!-- Wallet Balance -->
            <div class="w-full flex flex-col mb-6">
                <label for="wallet_balance" class="pb-2 font-semibold text-gray-800">Wallet Balance <span class="ml-1 text-red-400">*</span></label>
                <div class="flex rounded border border-gray-300 bg-white overflow-hidden">
                    <span class="flex items-center px-3 text-gray-600 bg-gray-100 border-r border-gray-300">₹</span>
                    <InputText type="number" aria-disabled="true" disabled id="wallet_balance" v-model="form.wallet_balance" class="!border-0 !rounded-none flex-1 w-full" :placeholder="form.wallet_balance ? form.wallet_balance.toString() : 'Enter Wallet Balance'" aria-describedby="wallet_balance-help" :class="[errors.wallet_balance ? 'p-invalid' : '']" min="0"  max="5000" step="0.01" @input="limitWalletBalance" />
                </div>
                <small id="wallet_balance-help" v-if="errors.wallet_balance" class="p-invalid">{{ errors.wallet_balance }}</small>
            </div>
            <div class="w-full flex flex-col mb-6">
                <label class="pb-2 font-semibold text-gray-800">selected Transaction</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white overflow-hidden">
                        <div class="w-full flex items-center gap-4 mb-2">
                            <div class="field-radiobutton">
                                <RadioButton id="deposite" name="transaction" value="deposite" v-model="selectedTransactionType"></RadioButton>
                                <label class="ml-1" :for="'deposite'">Deposite</label>
                            </div>
                            <div class="field-radiobutton">
                                <RadioButton id="withdraw" name="transaction" value="withdraw" v-model="selectedTransactionType"></RadioButton>
                                <label class="ml-1" :for="'withdraw'">Withdraw</label>
                            </div>
                        </div>
                        <InputNumber id="wallet_transaction_amount" v-model="form.wallet_transaction_amount" inputId="horizontal-buttons" showButtons buttonLayout="horizontal" :min="0" :step="0.25" mode="currency" currency="INR" fluid class="!border-0 !rounded-none flex-1 w-full" @input="onTransactionInput">
                            <template #incrementbuttonicon>
                                <span class="pi pi-plus" />
                            </template>
                            <template #decrementbuttonicon>
                                <span class="pi pi-minus" />
                            </template>
                        </InputNumber>
                        <small id="wallet_transaction_amount-help" v-if="errors.wallet_transaction_amount" class="p-invalid">{{ errors.wallet_transaction_amount }}</small>
                    </div>
                    <div class="bg-white overflow-hidden">
                        <label class="font-semibold text-gray-800">Transaction Note</label>
                        <InputText type="text" id="wallet_transaction_note" v-model="form.wallet_transaction_note" aria-describedby="wallet_transaction_note-help" :class="[errors.wallet_transaction_note ? 'p-invalid w-full' : 'w-full']" />
                        <small id="wallet_transaction_note-help" v-if="errors.wallet_transaction_note" class="p-invalid">{{ errors.wallet_transaction_note }}</small>
                    </div>
                </div>
            </div>
            <!-- Reference -->
            <div class="w-full flex flex-col mb-6">
                <label for="reference" class="pb-2 font-semibold text-gray-800">Reference</label>
                <InputText type="text" id="reference" v-model="form.reference" placeholder="Enter Reference" aria-describedby="reference-help" :class="[errors.reference ? 'p-invalid w-full' : 'w-full']" />
                <small id="reference-help" v-if="errors.reference" class="p-invalid">{{ errors.reference }}</small>
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
    import InputNumber from 'primevue/inputnumber';
    import Button from 'primevue/button';
    import InputSwitch from 'primevue/inputswitch';
    import FormInputShimmer from "@/Components/Shimmers/FormInputShimmer";
    import FormSwitchShimmer from "@/Components/Shimmers/FormSwitchShimmer";
    import Dropdown from 'primevue/dropdown';
    import RadioButton from 'primevue/radiobutton';
    export default defineComponent({
        name: 'CustomerForm',
        components: {
            InputText,
            Button,
            InputSwitch,
            FormInputShimmer,
            FormSwitchShimmer,
            Dropdown,
            RadioButton,
            InputNumber,
        },
        props: {
            editFlag: Boolean,
            customerId: Number,
            userGroups: Array,
            cities: Array,
            serviceUnits: Array,
            formErrors: Object,
            title: '',
        },
        data() {
            return {
                errors: {},
                form: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    mobile: '',
                    password: '',
                    reference: '',
                    user_group_id: null,
                    city_id: null,
                    service_unit_id: null,
                    is_active: true, 
                    wallet_balance: 0.00,
                    wallet_transaction_amount: 0.00,
                    wallet_transaction_note: '',
                },
                selectedTransactionType: 'deposite',
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
                this.form.transaction_type = this.selectedTransactionType;
                this.editFlag ? this.update() : this.create();
            },
            create() {
                this.formValidated = true;
                this.$inertia.post(route('customers.store'), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
            update() {
                this.formValidated = true;
                this.$inertia.patch(route('customers.update', { id: this.customerId }), this.form, {
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
                    axios.get(route('customers.edit', { id: this.customerId }))
                        .then(function (response) {
                            let data = response.data;
                            _this.form.first_name = data.first_name;
                            _this.form.last_name = data.last_name;
                            _this.form.email = data.email;
                            _this.form.mobile = data.mobile;
                            _this.form.city_id = data.city_id;
                            _this.form.service_unit_id = data.service_unit_id;
                            _this.form.user_group_id = data.user_group_id;
                            _this.form.reference = data.reference;
                            _this.form.is_active = data.is_active;
                            _this.form.wallet_balance = parseFloat(data.wallet.balance / 100).toFixed(2);
                        })
                        .catch(function (error) {
                            _this.loading = false;
                        })
                        .then(function () {
                            _this.loading = false;
                        });
                }
            },
            // Method to limit wallet_balance to 5 digits, including decimal part
            limitWalletBalance() {
                const maxLength = 5;
                let value = this.form.wallet_balance.toString();
                const [integerPart, decimalPart] = value.split('.');
                if (integerPart.length > maxLength) {
                    this.form.wallet_balance = integerPart.substring(0, maxLength);
                }
                if (decimalPart && decimalPart.length > 2) {
                    this.form.wallet_balance = `${integerPart}.${decimalPart.substring(0, 2)}`;
                }
            },
            onTransactionInput(event) {
                let value = parseFloat(event.value);
                if (isNaN(value) || value < 0) {
                    this.form.wallet_transaction_amount = 0;
                    return;
                }
                value = parseFloat(value.toFixed(2));
                if (this.selectedTransactionType === 'withdraw') {
                    const currentBalance = parseFloat(this.form.wallet_balance);
                    if (value > currentBalance) {
                        value = currentBalance;
                    }
                } else if (this.selectedTransactionType === 'deposite') {
                    if (value > 5000) {
                        value = 5000;
                    }
                }
                this.form.wallet_transaction_amount = value;
            }
        },
        mounted() {
            if(this.editFlag === true) {
                this.fetch();
            }
        }
    })
</script>

<style scoped>
    #wallet_transaction_note {
        margin-top: 0.5rem !important;
    }
</style>