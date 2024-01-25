<template>
  <modal
    id="contact-person-modal"
    v-model="showModal"
    size="large"
    :title="generateModalTitle('contact_details')"
    @submit="submitData"
    :loading="loading"
    :preloader="preloader"
  >
    <form
      v-if="!loading"
      ref="form"
      :data-url="selectedUrl ? selectedUrl : CONTACTPERSON"
      @submit.prevent="submitData"
    >
      <div class="row">
        <div class="col-md-6">
          <label>{{ $t('account_name') }}</label>
          <app-input
            class="margin-right-8"
            type="search-and-select"
            :key="customerGroupInputKey"
            :placeholder="
              $t('search_and_select', {
                name: $t('account_name'),
              })
            "
            :options="customerGroupOptions"
            :inputclearable="false"
            v-model="formData.customer_id"
            @input="updateCustomerGroup"
            :error-message="$errorMessage(errors, 'customer_id')"
          />
        </div>
        <div class="col-md-6">
          <app-form-group
            :label="$t('name')"
            :placeholder="$t('entername')"
            v-model="formData.name"
            :required="true"
            :error-message="$errorMessage(errors, 'name')"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <app-form-group
            :label="$t('phonenumber')"
            :placeholder="$t('enterphonenumber')"
            v-model="formData.phonenumber"
            :error-message="$errorMessage(errors, 'phone_number')"
          />
        </div>
        <div class="col-md-6">
          <app-form-group
            :label="$t('email')"
            :placeholder="$t('enteremail')"
            v-model="formData.email"
            :error-message="$errorMessage(errors, 'email')"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <app-form-group
            :label="$t('designation')"
            :placeholder="$t('designation')"
            v-model="formData.designation"
            :error-message="$errorMessage(errors, 'designation')"
          />
        </div>
      </div>
    </form>
  </modal>
</template>

<script>
import FormHelperMixins from '../../../../common/Mixin/Global/FormHelperMixins'
import ModalMixin from '../../../../common/Mixin/Global/ModalMixin'
import DefaultAction from '../../../../core/components/datatable/DefaultAction'
import { CONTACTPERSON } from '../../../Config/ApiUrl-CPB'
import { COUNTRIES, SELECTABLE_CUSTOMERS } from '../../../Config/ApiUrl-CP'
import CoreLibrary from '../../../../core/helpers/CoreLibrary'
import { mapGetters } from 'vuex'
import { axiosGet, urlGenerator } from '../../../../common/Helper/AxiosHelper'

export default {
  name: 'ContactPersonModal',
  extends: CoreLibrary,
  mixins: [FormHelperMixins, ModalMixin],
  components: {
    DefaultAction,
  },
  props: {
    userId: {},
    userType: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      customerData: '',
      CONTACTPERSON,
      COUNTRIES,
      isModalActive: false,
      customerGroupInputKey: 0,
      customerGroupOptions: {
        url: urlGenerator('app/selectable-customers'),
        query_name: 'search_by_name',
        per_page: 150,
        loader: 'app-pre-loader', // by default 'app-overlay-loader'
        modifire: ({ id, first_name: value }) => ({ id, value }),
        // prefetch: false,
      },
      countryOptions: {
        url: urlGenerator('app/selectable-countries'),
        query_name: 'search_by_name',
        per_page: 10,
        loader: 'app-pre-loader', // by default 'app-overlay-loader'
        modifire: ({ id, name: value }) => ({ id, value }),
      },
      formData: {
        customer_group_id: '',
        customer_id: '',
        name: '',
        phonenumber: '',
        email: '',
        desgination: '',
      },
      countryList: [],
    }
  },
  // created() {
  //   if (!this.selectedUrl) this.setDefaultGroupId()
  // },
  methods: {
    getCountries() {
      axiosGet('app/selectable-countries').then(
        (data) =>
          (this.countryList = [
            { name: this.$t('select_country') },
            ...data.data,
          ])
      )
    },
    updateCustomerGroup(selectedCustomerId) {
      // Fetch the selected customer data
      console.log(selectedCustomerId)

      // Fetch the selectable customers
      axiosGet('app/selectable-customers')
        .then((response) => {
          const selectableCustomers = response.data.data

          // Find the selected customer in the array
          const selectedCustomer = selectableCustomers.find(
            (customer) => customer.id === selectedCustomerId
          )

          if (selectedCustomer) {
            // Get the customer_group_id associated with the selectedCustomerId
            const customerGroupId = selectedCustomer.customer_group_id

            // Update formData with the customer_group_id
            this.formData.customer_group_id = customerGroupId
          } else {
            console.error(
              `Customer with ID ${selectedCustomerId} not found in selectable customers.`
            )
          }
        })
        .catch((error) => {
          console.error('Error fetching selectable customers:', error)
        })
    },
    afterSuccess({ data }) {
      this.formData = {
        sales_person: [],
      }
      $('#contact-person-modal').modal('hide')
      this.$emit('input', false)
      this.$emit('reload')
      this.toastAndReload(data.message)
      location.reload()
    },
    afterSuccessFromGetEditData(response) {
      this.formData = response.data
      this.axiosGet('app/selectable-countries')
        .then((res) => (this.countryList = res.data))
        .finally(() => {
          this.preloader = false
        })
    },
    // setDefaultGroupId() {
    //   this.axiosGet('app/selectable-customers').then((response) => {
    //     let id
    //     response.data.data.filter((item) => {
    //       id = item.id
    //     })
    //     this.formData.customer_id = id
    //     this.customerGroupInputKey++
    //     console.log(response)
    //   })
    // },
  },
  mounted() {
    this.getCountries()
    // axiosGet(`app/get-account-name-id/${this.userId}`)
    //   .then((response) => {
    //     const data = response.data.data
    //     // Assuming the response contains a property called 'customer_group_id'
    //     if (data.customer_group_id) {
    //       this.formData.customer_group_id = data.customer_group_id
    //     } else {
    //       console.error('customer_group_id not found in the response')
    //     }
    //   })
    //   .catch((error) => {
    //     console.error('Error fetching data:', error)
    //   })
  },
}
</script>
