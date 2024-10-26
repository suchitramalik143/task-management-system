<template>
    <div class="quick-filter-wrapper">
        <FilterBadge v-for="filter in filters"
                     :baseUrl="baseUrl"
                     :key="filter.name" :request-data="requestData" :filter="filter"></FilterBadge>

    </div>

</template>


<script setup>
import FilterBadge from "./Fragments/FilterBadge.vue";
import {defineProps} from 'vue';

const props = defineProps({
    baseUrl: {
        type: String,
        required: true,
    },
    filters: {
        type: Array,
        default: () => []
    },
    requestData: {
        type: Object,
        default: () => ({})
    }
});

</script>

<style scoped lang="scss">
.quick-filter-wrapper {
    display: flex;
    gap: 5px;
}


</style>
