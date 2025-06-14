<template>
    <app-layout title="Base Prices">
        <template #header>
            <h4 class="page-heading">Base Prices</h4>
        </template>
        <template #actions>
            <button @click="updatePrices" class="gk-btn gk-btn-success">Update Prices</button>
        </template>

        <div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="w-full shadow-md rounded-md flex gap-4 sm:justify-center items-center">
                <table class="w-full table-auto rounded-md">
                    <thead>
                        <tr>
                            <th class="w-24 bg-white border border-gray-200 px-4 py-2">Wash Qty</th>
                            <th class="w-1/4 bg-white border border-gray-200 px-4 py-2" v-for="type in carTypes" :key="type.id">{{ type.name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="wash in washes" :key="'wash_row_' + wash.id">
                            <th class="bg-white text-center border border-gray-200 px-4 py-2">
                                <div class="flex justify-center items-center w-8 h-8 rounded-md bg-gray-200">{{ wash.wash_qty }}</div>
                            </th>
                            <td class="text-center border border-gray-200 px-4 py-2" v-for="type in prices" :key="'cell_' + type.id + '_wash_' + wash.id">
                                <InputNumber v-model="type.prices[wash.wash_qty]" mode="currency" currency="INR" currency-display="symbol" :minFractionDigits="0" :maxFractionDigits="0" locale="en-IN"></InputNumber>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Button from 'primevue/button';
    import InputNumber from 'primevue/inputnumber';

    export default defineComponent({
        components: {
            AppLayout,
            Button,
            InputNumber
        },
        props: {
            washes: Array,
            carTypes: Array,
            errors: Object,
        },
        data() {
            return {
                prices: this.carTypes,
                loading: false,
            }
        },
        methods: {
            updatePrices() {
                this.$confirm.require({
                    header: 'Confirm Update',
                    message: 'Do you want to update the prices?',
                    icon: 'pi pi-info-circle',
                    acceptClass: 'p-button-danger',
                    rejectLabel: 'Cancel',
                    acceptLabel: 'Yes, Update',
                    accept: () => {
                        this.$inertia.post(route('update_base_prices'), {
                            'prices': this.prices
                        }, {
                            onSuccess: () => {
                                this.$toast.add({
                                    severity: 'info',
                                    summary: 'Confirmed',
                                    detail: 'Prices Updated',
                                    life: 3000
                                });
                            },
                        });
                    },
                    reject: () => {

                    }
                });

            },
        }
    })
</script>
