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
                <label for="name" class="pb-2 font-semibold text-gray-800">Discount Name <span class="ml-1 text-red-400">*</span></label>
                <InputText type="text"
                           id="name"
                           v-model="form.name"
                           placeholder="Enter Name" aria-describedby="name-help"
                           :class="[errors.name ? 'p-invalid' : '']"

                />
                <small id="name-help" v-if="errors.name" class="p-invalid">{{ errors.name }}</small>
            </div>

            <div class="w-full flex gap-4">
                <!-- Item Type -->
                <div class="w-full flex flex-col mb-6">
                    <label class="pb-2 font-semibold text-gray-800">Discount On <span class="ml-1 text-red-400">*</span></label>
                    <div class="flex items-center gap-4">
                        <div class="field-radiobutton flex items-center gap-2" v-for="item of items" :key="item.id">
                            <RadioButton :id="item.id" name="item_type" :value="item.id" v-model="form.item_type"></RadioButton>
                            <label :for="item.id">{{item.name}}</label>
                        </div>
                    </div>
                    <small id="code-help" v-if="errors.item_type" class="p-invalid">{{ errors.item_type }}</small>
                </div>

                <!-- Products -->
                <div v-if="form.item_type === 'App\\Models\\Product'" class="w-full flex flex-col mb-6">
                    <label for="product_id" class="pb-2 font-semibold text-gray-800">Product<span class="ml-1 text-red-400">*</span></label>
                    <Dropdown id="product_id" v-model="form.item_id" :options="products" optionValue="id" optionLabel="name" placeholder="Select a Product"
                              :filter="true" filterPlaceholder="Find Product" :class="[errors.item_id ? 'p-invalid' : '']"/>
                    <small id="product_id-help" v-if="errors.item_id" class="p-invalid">{{ errors.item_id }}</small>
                </div>

                <!-- Plans -->
                <div v-if="form.item_type === 'App\\Models\\Plan'" class="w-full flex flex-col mb-6">
                    <label for="plan_id" class="pb-2 font-semibold text-gray-800">Plan<span class="ml-1 text-red-400">*</span></label>
                    <Dropdown id="plan_id" v-model="form.item_id" :options="plans" optionValue="id" optionLabel="name" placeholder="Select a Plan"
                              :filter="true" filterPlaceholder="Find Plan" :class="[errors.item_id ? 'p-invalid' : '']"/>
                    <small id="plan_id-help" v-if="errors.item_id" class="p-invalid">{{ errors.item_id }}</small>
                </div>
            </div>

            <div class="w-full flex gap-4">
                <!-- Discount Type -->
                <div class="w-full flex flex-col mb-6">
                    <label class="pb-2 font-semibold text-gray-800">Discount Type <span class="ml-1 text-red-400">*</span></label>
                    <div class="flex items-center gap-4">
                        <div class="field-radiobutton flex items-center gap-2" v-for="type of types" :key="type.id">
                            <RadioButton :id="type.id" name="discount_type" :value="type.id" v-model="form.discount_type"></RadioButton>
                            <label :for="type.id">{{type.name}}</label>
                        </div>
                    </div>
                    <small id="discount_type-help" v-if="errors.discount_type" class="p-invalid">{{ errors.discount_type }}</small>
                </div>

                <!-- Value -->
                <div class="w-full flex flex-col mb-6">
                    <label for="discount_value" class="pb-2 font-semibold text-gray-800">Discount Value</label>
                    <InputNumber id="discount_value"
                                 v-model="form.discount_value"
                                 placeholder="Enter Amount" aria-describedby="discount_value-help"
                                 :class="[errors.discount_value ? 'p-invalid' : '']"

                    />
                    <small id="sort_order-help" v-if="errors.discount_value" class="p-invalid">{{ errors.discount_value }}</small>
                </div>
            </div>

            <div class="w-full flex gap-4">
                <!-- Max. Discount Value -->
                <div class="w-full flex flex-col mb-6">
                    <label for="maximum_discount_value" class="pb-2 font-semibold text-gray-800">Max Discount Value <span class="ml-1 text-red-400">*</span></label>
                    <InputText type="text"
                               id="maximum_discount_value"
                               v-model="form.maximum_discount_value"
                               placeholder="Enter Value" aria-describedby="minimum_cart_amount-help"
                               :class="[errors.maximum_discount_value ? 'p-invalid' : '']"

                    />
                    <small id="maximum_discount_value-help" v-if="errors.maximum_discount_value" class="p-invalid">{{ errors.maximum_discount_value }}</small>
                </div>

                <!-- Min. Cart Amount -->
                <div class="w-full flex flex-col mb-6">
                    <label for="minimum_cart_amount" class="pb-2 font-semibold text-gray-800">Min Cart Amount <span class="ml-1 text-red-400">*</span></label>
                    <InputText type="text"
                               id="minimum_cart_amount"
                               v-model="form.minimum_cart_amount"
                               placeholder="Enter Value" aria-describedby="minimum_cart_amount-help"
                               :class="[errors.minimum_cart_amount ? 'p-invalid' : '']"

                    />
                    <small id="minimum_cart_amount-help" v-if="errors.minimum_cart_amount" class="p-invalid">{{ errors.minimum_cart_amount }}</small>
                </div>
            </div>

            <div class="w-full flex gap-4">
                <!-- Valid From -->
                <div class="w-full flex flex-col mb-6">
                    <label for="valid_from" class="pb-2 font-semibold text-gray-800">Valid From<span class="ml-1 text-red-400">*</span></label>
                    <DatePicker class="w-full" :mode="'dateTime'" v-model="form.valid_from" :is24hr="false" :masks="masks" :update-on-input="true"
                                :model-config="modelConfig" :minute-increment="5">
                        <template v-slot="{ inputValue, inputEvents }">
                            <InputText type="text" id="valid_from" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.valid_from ? 'p-invalid' : '']"/>
                        </template>
                    </DatePicker>
                    <small id="valid_from-help" v-if="errors.valid_from" class="p-invalid">{{ errors.valid_from }}</small>
                </div>

                <!--Valid Until -->
                <div class="w-full flex flex-col mb-6">
                    <label for="valid_until" class="pb-2 font-semibold text-gray-800">Valid Until<span class="ml-1 text-red-400">*</span></label>
                    <DatePicker class="w-full" :mode="'dateTime'" v-model="form.valid_until" :is24hr="false" :masks="masks" :update-on-input="true"
                                :model-config="modelConfig" :minute-increment="5">
                        <template v-slot="{ inputValue, inputEvents }">
                            <InputText type="text" id="valid_until" class="w-full" v-on="inputEvents" :value="inputValue" :class="[errors.valid_until ? 'p-invalid' : '']"/>
                        </template>
                    </DatePicker>
                    <small id="valid_until-help" v-if="errors.valid_until" class="p-invalid">{{ errors.valid_until }}</small>
                </div>
            </div>

            <div class="w-full flex gap-4">
                <!-- Is Private -->
                <div class="w-full">
                    <div class="flex justify-between items-center mb-8">
                        <div class="w-9/12">
                            <label for="is_private" class="font-semibold text-gray-800 pb-1" v-html="form.is_private ? 'Private' : 'Public'"></label>
                            <p class="text-gray-500">Private (Applicable to selected users). Public (Applicable to all)</p>
                        </div>
                        <div class="cursor-pointer rounded-full relative shadow-sm">
                            <InputSwitch id="is_private" v-model="form.is_private" />
                        </div>
                    </div>
                </div>

                <!-- User Groups -->
                <div v-if="form.is_private" class="w-full flex flex-col mb-6">
                    <label for="user_group_id" class="pb-2 font-semibold text-gray-800">User Group<span class="ml-1 text-red-400">*</span></label>
                    <Dropdown id="user_group_id" v-model="form.user_group_id" :options="groups" optionValue="id" optionLabel="name" placeholder="Select a Group"
                              :filter="true" filterPlaceholder="Find Group" :class="[errors.user_group_id ? 'p-invalid' : '']"/>
                    <small id="user_group_id-help" v-if="errors.user_group_id" class="p-invalid">{{ errors.user_group_id }}</small>
                </div>
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
    import RadioButton from 'primevue/radiobutton';
    import { DatePicker } from 'v-calendar';
    export default defineComponent({
        name: 'DiscountForm',
        components: {
            InputText,
            InputNumber,
            TextArea,
            Button,
            InputSwitch,
            FormInputShimmer,
            FormSwitchShimmer,
            Dropdown,
            RadioButton,
            DatePicker
        },
        props: {
            editFlag: Boolean,
            discountId: Number,
            formErrors: Object,
            title: '',
            products: Array,
            plans: Array,
            groups: Array
        },
        data() {
            return {
                errors: {},
                masks: {
                    inputDateTime: ['DD/MM/YYYY HH:mm A'],
                },
                modelConfig: {
                    type: 'string',
                    mask: 'YYYY-MM-DD HH:mm:ss', // Uses 'iso' if missing
                },
                form: {
                    name: '',
                    description: '',
                    item_type: 'plan',
                    item_id: null,
                    discount_type: 'percentage',
                    discount_value: 10,
                    discount_method: 'auto',
                    coupon_code: '',
                    valid_from: '',
                    valid_until: '',
                    maximum_discount_value: 0,
                    minimum_cart_amount: 0,
                    is_private: false,
                    user_group_id: null,
                    is_active: true,
                },
                items: [
                    {id: 'App\\Models\\Product', name: 'Product'},
                    {id: 'App\\Models\\Plan', name: 'Plan'},
                ],
                types: [
                    {id: 'fixed', name: 'Fixed'},
                    {id: 'percentage', name: 'Percentage'},
                ],
                methods: [
                    {id: 'auto', name: 'auto'},
                    {id: 'coupon', name: 'coupon'},
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
                this.$inertia.post(route('discounts.store'), this.form, {
                    onSuccess: () => {
                        if (Object.keys(this.errors).length === 0) {
                            this.$emit('close', true);
                        }
                    },
                });
            },
            update() {
                this.formValidated = true;
                this.$inertia.patch(route('discounts.update', { id: this.discountId }), this.form, {
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
                    axios.get(route('discounts.edit', { id: this.discountId }))
                        .then(function (response) {
                            let data = response.data;
                            _this.form.name = data.name;
                            _this.form.description = data.description;
                            _this.form.item_type = data.item_type;
                            _this.form.item_id = data.item_id;
                            _this.form.discount_type = data.discount_type;
                            _this.form.discount_value = data.discount_value;
                            _this.form.discount_method = data.discount_method;
                            _this.form.coupon_code = data.coupon_code;
                            _this.form.valid_from = data.valid_from;
                            _this.form.valid_until = data.valid_until;
                            _this.form.maximum_discount_value = data.maximum_discount_value;
                            _this.form.minimum_cart_amount = data.minimum_cart_amount;
                            _this.form.is_private = data.is_private;
                            _this.form.user_group_id = data.user_group_id;
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
