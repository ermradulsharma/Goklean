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
		<form v-else class="my-6 mx-auto xl:w-full xl:mx-0" @submit.prevent="submitForm">
			<!-- Area -->
			<div class="w-full flex flex-col mb-6">
				<label for="area" class="pb-2 font-semibold text-gray-800">Area <span
				class="ml-1 text-red-400">*</span></label>
				<input type="text" v-model="form.area" id="area" ref="area" placeholder="Search Area"
				class="p-inputtext p-component" aria-describedby="area-help"
				:class="[errors.area ? 'p-invalid' : '']" />
				<small id="area-help" v-if="errors.area" class="p-invalid">{{ errors.area }}</small>
			</div>
			
			<!-- Address -->
			<div class="w-full flex flex-col mb-6">
				<label for="address" class="pb-2 font-semibold text-gray-800">Address <span
				class="ml-1 text-red-400">*</span></label>
				<Textarea id="address" v-model="form.address" placeholder="Enter Address" aria-describedby="address-help"
				:class="[errors.address ? 'p-invalid' : '']" />
				<small id="address-help" v-if="errors.address" class="p-invalid">{{ errors.address }}</small>
			</div>
			
			<!-- Address Type -->
			<div class="w-full flex flex-col mb-6">
				<label for="address_type" class="pb-2 font-semibold text-gray-800">Address Type<span
				class="ml-1 text-red-400">*</span></label>
				<Dropdown id="address_type" v-model="form.address_type" :options="addressTypes" optionValue="id"
				optionLabel="name" placeholder="Select a Type" :filter="true" filterPlaceholder="Find Type"
				:class="[errors.address_type ? 'p-invalid' : '']" />
				<small id="address_type-help" v-if="errors.address_type" class="p-invalid">{{ errors.address_type }}</small>
			</div>
			
			<!-- House No -->
			<div class="w-full flex flex-col mb-6">
				<label for="house_no" class="pb-2 font-semibold text-gray-800">House/Flat/Villa Number <span
				class="ml-1 text-red-400">*</span></label>
				<InputText type="text" id="house_no" v-model="form.house_no" placeholder="Enter No"
				aria-describedby="house_no-help" :class="[errors.house_no ? 'p-invalid' : '']" />
				<small id="house_no-help" v-if="errors.house_no" class="p-invalid">{{ errors.house_no }}</small>
			</div>
			
			<!-- House Name -->
			<div class="w-full flex flex-col mb-6">
				<label for="house_name" class="pb-2 font-semibold text-gray-800">House/Flat/Villa Name <span
				class="ml-1 text-red-400">*</span></label>
				<InputText type="text" id="house_name" v-model="form.house_name" placeholder="Enter Name"
				aria-describedby="house_name-help" :class="[errors.house_name ? 'p-invalid' : '']" />
				<small id="house_name-help" v-if="errors.house_name" class="p-invalid">{{ errors.house_name }}</small>
			</div>
			
			<!-- City -->
			<div class="w-full flex flex-col mb-6">
				<label for="city_id" class="pb-2 font-semibold text-gray-800">City<span
				class="ml-1 text-red-400">*</span></label>
				<Dropdown id="city_id" v-model="form.city_id" :options="cities" optionValue="id" optionLabel="name"
				placeholder="Select a City" :filter="true" filterPlaceholder="Find City"
				:class="[errors.city_id ? 'p-invalid' : '']" />
				<small id="city_id-help" v-if="errors.city_id" class="p-invalid">{{ errors.city_id }}</small>
			</div>
			<!--checkbox-->
			
			<table class="w-full table-auto">
                        <thead class="bg-gray-100 border-gray-200">
                        <tr>
                            <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Select</td>
                            <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Day</td>
                            <td class="border border-gray-500 px-4 py-2 text-gray-600 font-medium text-sm">Time</td>
                        </tr>
                        </thead>
                        <tbody>
						
                        <tr v-for="day in form.day_time_list" :key="day.name">
                         
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
			<!-- Approved Switch -->
			<div class="w-full">
				<div class="flex justify-between items-center mb-8">
					<div class="w-9/12">
						<label for="approved" class="font-semibold text-gray-800 pb-1"
						v-html="form.approved ? 'Approved' : 'Not Approved'"></label>
						<p class="text-gray-500">Approved (Bookings allowed). Not Approved (Bookings not allowed).</p>
					</div>
					<div class="cursor-pointer rounded-full relative shadow-sm">
						<InputSwitch id="approved" v-model="form.approved" />
					</div>
				</div>
			</div>
		
		<!-- Submit Button -->
		<div class="w-full flex">
			<Button type="submit" :label="'Update Address'" />
		</div>
		
	</form>
</div>
</template>
<script>
	import { defineComponent } from 'vue'
	import InputText from 'primevue/inputtext';
	import Textarea from 'primevue/textarea';
	import Button from 'primevue/button';
	import InputSwitch from 'primevue/inputswitch';
	import FormInputShimmer from "@/Components/Shimmers/FormInputShimmer";
	import FormSwitchShimmer from "@/Components/Shimmers/FormSwitchShimmer";
	import Dropdown from 'primevue/dropdown';
	import Checkbox from "primevue/checkbox";
	import {DatePicker} from "v-calendar";
	
	
	export default defineComponent({
		name: 'CustomerAddressForm',
		components: {
			InputText,
			Button,
			InputSwitch,
			FormInputShimmer,
			FormSwitchShimmer,
			Dropdown,
			Textarea,
			DatePicker,
			Checkbox
		},
		props: {
			editFlag: {
                type: Boolean,
                default: false
            },
			customerId: Number,
			cities: Array,
			formErrors: Object,
			title: '',
		
		},
		data() {
			return {
				
				errors: {},
				form: {
					address: '',
					area: '',
					latitude: '',
					longitude: '',
					address_type: 'flat',
					house_no: '',
					house_name: '',
					city_id: 1,
					approved: false,
					day_time_list: this.day_time_list,
					
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
					inputDateTime: ['HH:mm A'],
				},
				modelConfig: {
					type: 'string',
					mask: 'HH:mm:ss', // Uses 'iso' if missing
				},
				timeModelConfig: {
					type: 'string',
					mask: 'HH:mm:ss', // Uses 'iso' if missing
				},
				
				popover: {
					visibility: 'click'
				},
				formValidated: false,
				loading: false,
				addressTypes: [
				{ id: 'flat', name: 'Flat' },
				{ id: 'house', name: 'House' },
				{ id: 'villa', name: 'Villa' },
				{ id: 'office', name: 'Office' }
				],
				
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
				this.$inertia.post(route('update_customer_address', { id: this.customerId }), this.form, {
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
				axios.get(route('get_customer_address', { id: this.customerId }))
				.then(function (response) {
					let data = response.data;
					_this.form.address = data.address;
					_this.form.area = data.area;
					_this.form.latitude = data.latitude;
					_this.form.longitude = data.longitude;
					_this.form.address_type = data.address_type;
					_this.form.house_no = data.house_no;
					_this.form.house_name = data.house_name;
					_this.form.city_id = data.city_id;
					_this.form.approved = data.approved;
					_this.form.day_time_list = data.day_time_list;
					_this.form.editFlag = data.editFlag;
					_this.form.day_list = data.day_list;

				})
				.catch(function (error) {
					_this.loading = false;
				})
				.then(function () {
					_this.loading = false;
					_this.$nextTick(function () {
						_this.initMap();
					});
				});
			},
			initMap() {
				let _this = this;
				const autocomplete = new google.maps.places.Autocomplete(this.$refs["area"]);
				
				autocomplete.addListener('place_changed', function () {
					let place = autocomplete.getPlace();
					if (!place.geometry) {
						// User entered the name of a Place that was not suggested and
						// pressed the Enter key, or the Place Details request failed.
						window.alert("No details available for input: '" + place.name + "'");
						return;
					}
					
					_this.form.area = place.formatted_address;
					_this.form.latitude = place.geometry.location.lat();
					_this.form.longitude = place.geometry.location.lng();
				});
			}
		},
		mounted() {
			this.fetch();
		}
		
	})
	
	
	
</script>
