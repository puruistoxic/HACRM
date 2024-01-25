<template>
    <div style="width: 15.625rem">

        <div v-if="value.length">
            <div>
                <p class="mb-2"
                   v-if=" index < contactPersonShow"
                   v-for="(item, index) in value">
                    <span>{{ fullContactPerson(item) }}</span>
                </p>
            </div>

            <div
                v-if="contactPersonShow < value.length || value.length > contactPersonShow">
                <a :href="contactPersonUrl"
                   class="ml-2">
                    + {{ value.length - 1 }} {{ $t('more') }}
                </a>
            </div>
        </div>
        <div v-else>-</div>

    </div>
</template>

<script>

import {CUSTOMER_DETAILS} from '../../../../Config/ApiUrl-CPB';

export default {
    name: "ContactPersonInfo",
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            contactPersonShow: 1,
            CUSTOMER_DETAILS,
            contactPersonUrl: CUSTOMER_DETAILS + this.rowData.id + '?tab=ContactPersons'
        }
    },
    methods: {

        fullContactPerson(item) {
            return `${item.details ? item.details + ', ' : ''}
            ${item.area ? item.area + ', ' : ''}
            ${item.city ? item.city + ', ' : ''}
            ${item.country ? item.country.name + ' -' : ''}
            ${item.zip_code ? item.zip_code : ''}`;
        }
    }
}
</script>
