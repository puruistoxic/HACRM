<template>
  <modal
    id="payment-modal"
    v-model="showModal"
    size="large"
    :title="generateModalTitle('payment_details')"
    @submit="submitData"
    :loading="loading"
    :preloader="preloader"
  >
    <form
      v-if="!loading"
      ref="form"
      :data-url="selectedUrl ? selectedUrl : PAYMENT"
      @submit.prevent="submitData"
    >
      <div class="row">
        <div class="col-md-12">
          <app-form-group
            :label="$t('title')"
            :placeholder="$t('e_title')"
            v-model="formData.title"
            :required="true"
            :error-message="$errorMessage(errors, 'title')"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <label>{{ $t('date') }}</label>
          <app-date-picker
            type="range-picker"
            :placeholder="$t('e_date')"
            v-model="formData.date"
            :error-message="$errorMessage(errors, 'date')"
          />
        </div>
        <div class="col-md-6">
          <label>{{ $t('Source') }}</label>
          <app-dropdown-options
            type="dropdown"
            :options="dropdownOptions"
            v-model="formData.source"
            :placeholder="$t('Select Options')"
            :error-message="$errorMessage(errors, 'selectedOption')"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label
              class="mb-lg-0 col-lg-3"
              style="
                padding-left: 0px !important;
                margin-bottom: 7.48px !important;
              "
            >
              {{ $t('description') }}
            </label>
            <app-input
              type="textarea"
              :label="$t('description')"
              :text-area-rows="8"
              v-model="formData.description"
              :error-message="$errorMessage(errors, 'description')"
              :placeholder="$t('e_description')"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <app-form-group
            :label="$t('Net (GBP)')"
            :placeholder="$t('e_net_gbp')"
            v-model="formData.net_gbp"
            :error-message="$errorMessage(errors, 'Net (GBP)')"
          />
        </div>
        <div class="col-md-4">
          <app-form-group
            :label="$t('Gross (GBP)')"
            :placeholder="$t('e_gross_gbp')"
            v-model="formData.gross_gbp"
            :error-message="$errorMessage(errors, 'Gross (GBP)')"
          />
        </div>
        <div class="col-md-4">
          <label>{{ $t('payment_status') }}</label>
          <app-dropdown-options
            type="dropdown"
            :options="dropdownOptions2"
            v-model="formData.payment_status"
            :placeholder="$t('Select Option')"
            :error-message="$errorMessage(errors, 'selectedOption')"
          />
        </div>
      </div>
    </form>
  </modal>
</template>

<script>
import FormHelperMixins from '../../../../../../common/Mixin/Global/FormHelperMixins'
import ModalMixin from '../../../../../../common/Mixin/Global/ModalMixin'
import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import { PAYMENT } from '../../../../../Config/ApiUrl-CPB'
import { COUNTRIES } from '../../../../../Config/ApiUrl-CP'
import CoreLibrary from '../../../../../../core/helpers/CoreLibrary'
import DatePicker from './DatePicker'
import DropDown from './DropDown'
import {
  axiosGet,
  urlGenerator,
} from '../../../../../../common/Helper/AxiosHelper'

export default {
  name: 'NoteModal',
  extends: CoreLibrary,
  mixins: [FormHelperMixins, ModalMixin],
  components: {
    DefaultAction,
    DatePicker,
    DropDown,
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
      COUNTRIES,
      PAYMENT,
      isModalActive: false,
      countryOptions: {
        url: urlGenerator('app/selectable-countries'),
        query_name: 'search_by_name',
        per_page: 10,
        loader: 'app-pre-loader', // by default 'app-overlay-loader'
        modifire: ({ id, name: value }) => ({ id, value }),
      },
      formData: {
        id: this.userId,
        title: '',
        description: '',
        source: 'null',
        date: '',
        net_gbp: '',
        gross_gbp: '',
        payment_status: 'null',
      },
      countryList: [],
      dropdownOptions: [
        { label: 'Receivable Credit Note', value: 'Receivable Credit Note' },
        { label: 'Receivable Invoice', value: 'Receivable Invoice' },
        // Add more options as needed
      ],
      dropdownOptions2: [
        { label: 'Completed', value: 'Completed' },
        { label: 'Pending', value: 'Pending' },
        // Add more options as needed
      ],
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
      $('#payment-modal').modal('hide')
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
  },
  mounted() {
    this.getCountries()
  },
}
</script>
