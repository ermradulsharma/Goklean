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
                <label class="pb-2 font-semibold text-gray-800">Schedule Details</label>
                <table class="w-full table-auto">
                    <tbody>
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Schedule ID</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ schedule.code }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Customer</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ schedule.customer }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Customer Car</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ schedule.customer_car }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Booking Code</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ schedule.booking_code }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Schedule Date</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ schedule.date }}</td>
                    </tr>
					
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Status</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ schedule.status }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm font-semibold">Reason (If any)</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ schedule.reason }}</td>
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
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Status</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Reason (If any)</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="item in items" :key="item.code">
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.name }}</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.status }}</td>
                        <td class="border border-gray-500 px-4 py-2 text-gray-600 text-sm">{{ item.reason }}</td>
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
        name: 'ScheduleDetails',
        components: {
            FormInputShimmer,
            FormSwitchShimmer
        },
        props: {
            scheduleId: Number,
            title: ''
        },
        data() {
            return {
                schedule: Object,
                items: Array,
                loading: false,
            }
        },
        methods: {
            fetch() {
                let _this = this;
                _this.loading = true;
                axios.get(route('schedules.show', { id: this.scheduleId }))
                    .then(function (response) {
                        let data = response.data;
                        _this.schedule = data.schedule;
                        _this.items = data.items;
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
