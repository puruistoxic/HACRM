<template>
    <div>
        <p class="text-primary cursor-pointer pb-0 mb-0" @click="handleCustomerNameClick"> {{ rowData.name }} </p>
    </div>
</template>

<script>
import {CONTACTPERSON,CONTACTPERSON_DETAILS} from "../../../../Config/ApiUrl-CPB";
import {urlGenerator} from "../../../../Helper/Helper";

export default {
    name: "ContactPersonAction",
    props: {
        value: {},
        rowData: {},
    },
    data() {
        return {
            CONTACTPERSON
        }
    },
    methods: {
        handleCustomerNameClick() {
            return window.location.replace(urlGenerator(CONTACTPERSON_DETAILS+ '/' + this.rowData.id));
        }
    }
}
</script>