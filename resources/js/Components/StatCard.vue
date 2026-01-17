<template>
    <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-xl border border-gray-100">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-lg bg-gray-50 mr-4">
                    <slot name="icon"></slot>
                </div>
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                            {{ title }}
                        </dt>
                        <dd>
                            <div class="text-2xl font-bold text-gray-900 mt-1">
                                {{ subtitle }}
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {Link} from "@inertiajs/inertia-vue3";

    export default {
        name: 'StatCard',
        components: {
            Link
        },
        props: {
            title: String,
            subtitle: [String, Number]
        }
    }
</script>
