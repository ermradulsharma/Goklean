<template>
    <div>
        <Head :title="title" />
        <div class="ml-0 transition md:ml-60">
            <jet-banner />
		</div>
        <div class="bg-gray-50 min-h-screen">
            <nav
			class="fixed top-0 left-0 z-20 h-full pb-10 overflow-hidden transition origin-left transform bg-gray-900 w-60 md:translate-x-0"
			:class="{ '-translate-x-full' : !sideBar, 'translate-x-0' : sideBar }"
			@click="sideBar = false">
                <Link class="flex items-center px-4 py-5" :href="route('dashboard')">
				<jet-application-mark class="block h-9 w-auto bg-white rounded-full" />
				<div class="h-9 ml-4 pt-1 text-xl font-bold text-white">GoKleen</div>
                </Link>
                <perfect-scrollbar class="h-full overflow-hidden" ref="scroll" :options="scrollbarOptions">
                    <nav class="text-sm font-medium pb-16 text-gray-400" aria-label="Main Navigation">
                        <template v-for="item in items">
                            <sidebar-dropdown v-if="item.active && item.item_type === 'dropdown'" :title="item.label" :items="item.items">
                                <template #icon>
                                    <span v-html="item.icon"></span>
								</template>
							</sidebar-dropdown>
                            <sidebar-link v-if="item.active === true && item.item_type === 'link'" :title="item.label" :url="item.url">
                                <template #icon>
                                    <span v-html="item.icon"></span>
								</template>
							</sidebar-link>
                            <div class="my-4 mx-4 uppercase font-semibold text-gray-500 text-xs" v-if="item.item_type === 'divider'">
                                {{ item.label }}
							</div>
						</template>
					</nav>
				</perfect-scrollbar>
			</nav>
            <div class="ml-0 transition md:ml-60">
                <header class="bg-white shadow flex items-center justify-between w-full md:mx-auto md:sticky md:z-30 md:top-0 px-4 h-14">
                    <button class="block btn btn-light-secondary md:hidden" @click="sideBar = true">
                        <span class="sr-only">Menu</span>
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
						</svg>
					</button>
                    <div class="hidden -ml-3 form-icon md:block w-96">
                        <Link class="text-sm font-semibold px-4 py-5 capitalize" :href="route('dashboard')">
						Control Panel
                        </Link>
					</div>
                    <div class="flex items-center">
                        <a href="#" class="flex text-gray-500">
                            <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
							</svg>
						</a>
                        <!-- Account Dropdown -->
                        <div class="ltr:ml-4 rtl:mr-4 relative">
                            <jet-dropdown align="right" width="48">
                                <template #trigger>
                                    <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                        <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.first_name" />
									</button>
									
                                    <span v-else class="inline-flex rounded-md">
										<button type="button" class="inline-flex items-center px-3 py-2 border border-transparent leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
											{{ $page.props.user.first_name }}
											
											<svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
												<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
											</svg>
										</button>
									</span>
								</template>
								
                                <template #content>
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Manage Account
									</div>
									
                                    <jet-dropdown-link :href="route('profile.show')">
                                        Profile
									</jet-dropdown-link>
									
                                    <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                        Api Tokens
									</jet-dropdown-link>
									
                                    <div class="border-t border-gray-100"></div>
									
                                    <!-- Authentication -->
                                    <form @submit.prevent="logout">
                                        <jet-dropdown-link as="button">
                                            Log Out
										</jet-dropdown-link>
									</form>
								</template>
							</jet-dropdown>
						</div>
					</div>
				</header>
                <section>
                    <div class="container mx-auto pt-4 px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                            <div>
                                <slot name="header"></slot>
							</div>
                            <div class="mb-6 sm:mb-0 sm:mt-0">
                                <slot name="actions"></slot>
							</div>
						</div>
					</div>
				</section>
                <!-- Page Content -->
                <main>
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                        <Message v-if="$page.props.successMessage" severity="success" :closable="false">{{ $page.props.successMessage }}</Message>
                        <Message v-if="$page.props.errorMessage" severity="error" :closable="false">{{ $page.props.errorMessage }}</Message>
					</div>
					
                    <slot></slot>
				</main>
				
                <ConfirmDialog></ConfirmDialog>
                <Toast position="bottom-right" />
			</div>
            <!-- Sidebar Backdrop -->
            <div class="fixed inset-0 z-10 w-screen h-screen bg-black bg-opacity-25 md:hidden" v-show="sideBar" @click="sideBar = false"></div>
		</div>
	</div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
    import JetBanner from '@/Jetstream/Banner.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import JetNavLink from '@/Jetstream/NavLink.vue'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import ConfirmDialog from 'primevue/confirmdialog';
    import Toast from 'primevue/toast';
    import Message from 'primevue/message';
    import SidebarDropdown from "@/Components/SidebarDropdown";
    import SidebarLink from "@/Components/SidebarLink";
	
    export default defineComponent({
        props: {
            title: String,
		},
        components: {
            Head,
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
            Link,
            ConfirmDialog,
            Message,
            Toast,
            SidebarDropdown,
            SidebarLink,
		},
		
        data() {
            return {
                sideBar: false,
                darkMode: false,
                scrollbarOptions: {
                    suppressScrollX: true
				},
                successMessage: String,
                errorMessage: String,
                items: [
				{
					label:'Dashboard',
					item_type: 'link',
					icon:'<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
					url: route('dashboard'),
					active: true,
				},
				{
					label: 'Schedules',
					item_type: 'link',
					icon: '<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
					url: route('schedules.index'),
					active: true
				},
				{
					label: 'Bookings',
					item_type: 'link',
					icon: '<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-red-600" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M17 5c.83 0 1.5-.67 1.5-1.5 0-1-1.5-2.7-1.5-2.7s-1.5 1.7-1.5 2.7c0 .83.67 1.5 1.5 1.5zm-5 0c.83 0 1.5-.67 1.5-1.5 0-1-1.5-2.7-1.5-2.7s-1.5 1.7-1.5 2.7c0 .83.67 1.5 1.5 1.5zM7 5c.83 0 1.5-.67 1.5-1.5C8.5 2.5 7 .8 7 .8S5.5 2.5 5.5 3.5C5.5 4.33 6.17 5 7 5zm11.92 3.01C18.72 7.42 18.16 7 17.5 7h-11c-.66 0-1.21.42-1.42 1.01L3 14v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 9h10.29l1.04 3H5.81l1.04-3zM19 19H5v-4.66l.12-.34h13.77l.11.34V19z"/><circle cx="7.5" cy="16.5" r="1.5"/><circle cx="16.5" cy="16.5" r="1.5"/></svg>',
					url: route('bookings.index'),
					active: true
				},
				{
					label:'Customer Cars',
					item_type: 'link',
					icon:'<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-yellow-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><rect fill="none" height="24" width="24" y="0"/></g><g><g><path d="M18.92,6.01C18.72,5.42,18.16,5,17.5,5h-11C5.84,5,5.29,5.42,5.08,6.01L3,12v8c0,0.55,0.45,1,1,1h1c0.55,0,1-0.45,1-1v-1 h12v1c0,0.55,0.45,1,1,1h1c0.55,0,1-0.45,1-1v-8L18.92,6.01z M6.85,7h10.29l1.04,3H5.81L6.85,7z M19,17H5v-5h14V17z"/><circle cx="7.5" cy="14.5" r="1.5"/><circle cx="16.5" cy="14.5" r="1.5"/></g></g></svg>',
					url: route('customer-cars.index'),
					active: true,
				},
				{
					item_type: 'divider',
					label: 'Payments'
				},
				{
					label:'Invoices',
					item_type: 'link',
					icon:'<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-pink-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19.5 3.5L18 2l-1.5 1.5L15 2l-1.5 1.5L12 2l-1.5 1.5L9 2 7.5 3.5 6 2 4.5 3.5 3 2v20l1.5-1.5L6 22l1.5-1.5L9 22l1.5-1.5L12 22l1.5-1.5L15 22l1.5-1.5L18 22l1.5-1.5L21 22V2l-1.5 1.5zM19 19.09H5V4.91h14v14.18zM6 15h12v2H6zm0-4h12v2H6zm0-4h12v2H6z"/></svg>',
					url: route('invoices.index'),
					active: true,
				},
				{
					label:'Subscriptions',
					item_type: 'link',
					icon: '<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-indigo-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M4 6h16v2H4zm2-4h12v2H6zm14 8H4c-1.1 0-2 .9-2 2v8c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-8c0-1.1-.9-2-2-2zm0 10H4v-8h16v8zm-10-7.27v6.53L16 16z"/></svg>',
					url: route('subscriptions.index'),
					active: true,
				},
				{
					label:'Refunds',
					item_type: 'link',
					icon: '<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>',
					url: route('refunds.index'),
					active: true,
				},
				{
					label:'Referrals',
					item_type: 'link',
					icon: '<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
					url: route('referrals.index'),
					active: true,
				},
				{
					item_type: 'divider',
					label: 'Notification'
				},
				{
					label: 'Send Notification',
					item_type: 'dropdown',
					icon:'<svg class="flex-shrink-0 w-5 h-5 mr-2 text-indigo-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>',
					active: true,
					items:[
					{
						label:'Android',
						url: route('notification.index'),
						active: true,
					},
					{
						label:'iPhone',
						url: route('notification.index'),
						active: true,
					},
					{
						label:'All',
						url: route('notification.index'),
						active: true,
					},
					],
				},
				
				{
					item_type: 'divider',
					label: 'User Management'
				},
				{
					label: 'Users',
					item_type: 'dropdown',
					icon:'<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
					active: true,
					items:[
					{
						label:'Customers',
						url: route('customers.index'),
						active: true,
					},
					{
						label:'Service Units',
						url: route('service-units.index'),
						active: true,
					},
					{
						label:'Administrators',
						url: route('admins.index'),
						active: true,
					},
					],
				},
				{
					label: 'Respond To',
					item_type: 'dropdown',
					icon:'<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
					active: true,
					items:[
					/*{
						label:'Profile Change Requests',
						url: route('profile_change_requests'),
						active: true,
					},*/
					{
						label:'Address Change Requests',
						url: route('address_change_requests'),
						active: true,
					},
					],
				},
				{
					item_type: 'divider',
					label: 'Configuration'
				},
				{
					label: 'Plans & Prings',
					item_type: 'dropdown',
					icon:'<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
					active: true,
					items:[
					{
						label:'Base Prices',
						url: route('base_prices'),
						active: true,
					},
					{
						label:'Plans',
						url: route('plans.index'),
						active: true,
					},
					{
						label:'Products',
						url: route('products.index'),
						active: true,
					},
					{
						label:'Discounts',
						url: route('discounts.index'),
						active: true,
					},
					],
				},
				{
					label: 'Manage Cars',
					item_type: 'dropdown',
					icon:'<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-blue-500" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><rect fill="none" height="24" width="24" y="0"/></g><g><g><path d="M18.92,6.01C18.72,5.42,18.16,5,17.5,5h-11C5.84,5,5.29,5.42,5.08,6.01L3,12v8c0,0.55,0.45,1,1,1h1c0.55,0,1-0.45,1-1v-1 h12v1c0,0.55,0.45,1,1,1h1c0.55,0,1-0.45,1-1v-8L18.92,6.01z M6.85,7h10.29l1.04,3H5.81L6.85,7z M19,17H5v-5h14V17z"/><circle cx="7.5" cy="14.5" r="1.5"/><circle cx="16.5" cy="14.5" r="1.5"/></g></g></svg>',
					active: true,
					items:[
					{
						label:'Car Models',
						url: route('cars.index'),
						active: true,
					},
					{
						label:'Car Brands',
						url: route('brands.index'),
						active: true,
					},
					{
						label:'Car Colors',
						url: route('colors.index'),
						active: true,
					},
					],
				},
				{
					label: 'Settings',
					item_type: 'dropdown',
					icon:'<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
					active: true,
					items:[
					{
						label:'General Settings',
						url: route('general_settings'),
						active: true,
					},
					{
						label:'Notification Settings',
						url: route('notification_settings'),
						active: true,
					},
					{
						label:'Payment Settings',
						url: route('payment_settings'),
						active: true,
					},
					/*{
						label:'App Banners',
						url: route('banners.index'),
						active: true,
					},*/
					],
				},
				{
					label: 'File Manager',
					item_type: 'link',
					url: route('file-manager'),
					icon: '<svg class="flex-shrink-0 w-5 h-5 mr-2 transition text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>',
					active: true
				},
                ]
			}
		},
		
        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
					}, {
                    preserveState: false
				})
			},
			
            logout() {
                this.$inertia.post(route('logout'));
			},
		}
	})
</script>
