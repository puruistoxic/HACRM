<template>
  <div class="content-wrapper">
    <div class="">
      <app-page-top-section :title="powerBiReportName" icon="briefcase">
      </app-page-top-section>
    </div>
    <iframe
      v-if="powerBiReportLink"
      :src="powerBiReportLink"
      frameborder="0"
      width="100%"
      height="100%"
    ></iframe>
  </div>
</template>

<script>
import {
  axiosPatch,
  urlGenerator,
  axiosGet,
} from '../../../../common/Helper/AxiosHelper'

export default {
  name: 'ProductSales',

  data() {
    return {
      powerBiReportLink: null,
      powerBiReportName: '',
    }
  },
  mounted() {
    // Fetch the Power BI report link when the component is mounted
    this.fetchPowerBiReportLink()
  },

  methods: {
    async fetchPowerBiReportLink() {
      try {
        // Replace 'your_report_name' with the actual report name you want to fetch
        const reportName = 'product-sales-2018-2023-report'
        const response = await axiosGet(`/account/report/${reportName}`)
        // Assuming the response structure has a 'report_link' property
        this.powerBiReportLink = response.data.data.report_link
        this.powerBiReportName = response.data.data.report_name_format
      } catch (error) {
        console.error('Error fetching Power BI report link:', error)
        // Handle error as needed
      }
    },
  },
}
</script>
