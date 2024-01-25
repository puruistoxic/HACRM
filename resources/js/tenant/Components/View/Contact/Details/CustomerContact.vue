<template>
    <div>
        <div v-if="value.length">
            <div v-for="(item, index) in value" class="mb-2">
            <span class="badge badge-light">
                {{ item.value }}
            </span>
            </div>
        </div>
        <div v-else> -</div>
    </div>
</template>

<script>
export default {
    name: "CustomerContact",
    props: ['rowData', 'tableId', 'value'],
    methods: {
        setBadgeColor(type) {
            if (type === 'home') return 'badge-warning';
            else if (type === 'office') return 'badge-info';
            else if (type === 'work') return 'badge-purple';
        }
    }
}
</script>