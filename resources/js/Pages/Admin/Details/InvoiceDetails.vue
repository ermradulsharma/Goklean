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
        <div v-else class="my-6 w-11/12 mx-auto xl:w-full xl:mx-0">
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
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Invoice Amount</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.total_price }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Payment Mode</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.payment_mode }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Status</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.status }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Payment Date</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ invoice.payment_date }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
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
        </div>
    </div>
</template>
<script>
    import { defineComponent } from 'vue';
    import FormInputShimmer from "@/Components/Shimmers/FormInputShimmer";
    import FormSwitchShimmer from "@/Components/Shimmers/FormSwitchShimmer";
    export default defineComponent({
        name: 'InvoiceDetails',
        components: {
            FormInputShimmer,
            FormSwitchShimmer
        },
        props: {
            invoiceId: Number,
            title: ''
        },
        data() {
            return {
                invoice: Object,
                loading: false,
            }
        },
        methods: {
            fetch() {
                let _this = this;
                _this.loading = true;
                axios.get(route('invoices.show', { id: this.invoiceId }))
                    .then(function (response) {
                        let data = response.data;
                        _this.invoice = data.invoice;
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
