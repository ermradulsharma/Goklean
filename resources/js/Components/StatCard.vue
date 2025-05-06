<template>
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            {{ title }}
                        </dt>
                        <dd>
                            <div class="text-lg font-medium text-gray-900">
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
            subtitle: String
        }
    }
</script>
