<template>
    <Link :href="'/'">
        <img class="w-16 h-16" :src="'/images/logo.png'" />
    </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
    })
</script>
