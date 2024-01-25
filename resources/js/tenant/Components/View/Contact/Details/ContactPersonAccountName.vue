<template>
    <div>
        <p class="text-primary cursor-pointer pb-0 mb-0" @click="handleCustomerNameClick"> {{ rowData.name }} </p>
    </div>
</template>

<script>
import {CONTACTPERSON,CONTACTPERSON_DETAILS ,CUSTOMER_ID_CUSTOMER_GROUP} from "../../../../Config/ApiUrl-CPB";
import {urlGenerator} from "../../../../Helper/Helper";

export default {
    name: "ContactPersonAction",
    props: {
        value: {},
        rowData: {},
    },
    data() {
        return {
            CUSTOMER_ID_CUSTOMER_GROUP
        }
    },
    methods: {
        handleCustomerNameClick() {
            return window.location.replace(urlGenerator('account/details'+ '/' + this.rowData.id));
        }
    }
}
</script>