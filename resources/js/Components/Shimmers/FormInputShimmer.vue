<template>
    <div class="mb-6">
        <Skeleton width="10rem" class="mb-2"></Skeleton>
        <Skeleton height="2rem"></Skeleton>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    import Skeleton from "primevue/skeleton";

    export default defineComponent({
        name: "FormInputShimmer",
        components: {
            Skeleton
        }
    })
</script>
