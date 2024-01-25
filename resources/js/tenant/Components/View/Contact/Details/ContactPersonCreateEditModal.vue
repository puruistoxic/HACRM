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
        <div class="col-md-12">
          <app-form-group
            :label="$t('person_name')"
            :placeholder="$t('e_person_name')"
            v-model="formData.name"
            :required="true"
            :error-message="$errorMessage(errors, 'person_name')"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <app-form-group
            :label="$t('phone_number')"
            :placeholder="$t('e_phone_number')"
            v-model="formData.phonenumber"
            :error-message="$errorMessage(errors, 'phone_number')"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <app-form-group
            :label="$t('email')"
            :placeholder="$t('e_email')"
            v-model="formData.email"
            :error-message="$errorMessage(errors, 'email')"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <app-form-group
            :label="$t('designation')"
            :placeholder="$t('e_designation')"
            v-model="formData.designation"
            :error-message="$errorMessage(errors, 'designation')"
          />
        </div>
      </div>
    </form>
  </modal>
</template>

<script>
import FormHelperMixins from '../../../../../common/Mixin/Global/FormHelperMixins'
import ModalMixin from '../../../../../common/Mixin/Global/ModalMixin'
import DefaultAction from '../../../../../core/components/datatable/DefaultAction'
import { CONTACTPERSON } from '../../../../Config/ApiUrl-CPB'
import { COUNTRIES } from '../../../../Config/ApiUrl-CP'
import CoreLibrary from '../../../../../core/helpers/CoreLibrary'
import {
  axiosGet,
  urlGenerator,
} from '../../../../../common/Helper/AxiosHelper'

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
      customer_group_id: '',
      countryOptions: {
        url: urlGenerator('app/selectable-countries'),
        query_name: 'search_by_name',
        per_page: 10,
        loader: 'app-pre-loader', // by default 'app-overlay-loader'
        modifire: ({ id, name: value }) => ({ id, value }),
      },
      formData: {
        customer_group_id: '',
        customer_id: this.userType === 'customer' ? this.userId : '',
        country_id: '',
        supplier_id: this.userType === 'supplier' ? this.userId : '',
      },
      countryList: [],
    }
  },
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
    afterSuccess({ data }) {
      this.formData = {
        sales_person: [],
      }
      $('#contact-person-modal').modal('hide')
      this.$emit('input', false)
      this.$emit('reload')
      this.toastAndReload(data.message)
    },
    afterSuccessFromGetEditData(response) {
      this.formData = response.data
      this.axiosGet('app/selectable-countries')
        .then((res) => (this.countryList = res.data))
        .finally(() => {
          this.preloader = false
        })
    },
  },
  mounted() {
    this.getCountries()
    axiosGet(`app/get-account-name-id/${this.userId}`)
      .then((response) => {
        const data = response.data.data
        // Assuming the response contains a property called 'customer_group_id'
        if (data.customer_group_id) {
          this.formData.customer_group_id = data.customer_group_id
        } else {
          console.error('customer_group_id not found in the response')
        }
      })
      .catch((error) => {
        console.error('Error fetching data:', error)
      })
  },
}
</script>
