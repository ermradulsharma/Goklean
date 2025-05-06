<template>
    <Link :class="{'text-blue-400': active}" :href="url" class="flex items-center px-4 py-3 transition cursor-pointer group hover:bg-gray-800 hover:text-gray-200">
        <slot name="icon"></slot>
        <span>{{ title }}</span>
    </Link>
</template>
<script>
    import {Link} from "@inertiajs/inertia-vue3";

    export default {
        name: 'SidebarLink',
        components: {
            Link
        },
        props: {
            title: String,
            url: String
        },
        computed: {
            active() {
                return window.location.href === this.url;
            }
        }
    }
</script>
