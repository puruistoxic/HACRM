<template>
  <div>
    <p
      class="text-primary cursor-pointer pb-0 mb-0"
      @click="handleCustomerNameClick"
    >
      {{ rowData.customer_name }}
    </p>
  </div>
</template>

<script>
import {
  CONTACTPERSON,
  CONTACTPERSON_DETAILS,
  CUSTOMER_DETAILS,
} from '../../../../Config/ApiUrl-CPB'
import { urlGenerator } from '../../../../Helper/Helper'

export default {
  name: 'ContactPersonAction',
  props: {
    value: {},
    rowData: {},
  },
  data() {
    return {
      CONTACTPERSON,
      CUSTOMER_DETAILS,
    }
  },
  methods: {
    handleCustomerNameClick() {
      return window.location.replace(
        urlGenerator(CUSTOMER_DETAILS + '/' + this.rowData.customer_id)
      )
    },
  },
}
</script>
