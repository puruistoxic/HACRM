<template>
  <div>
    <form
      ref="form"
      :data-url="selectedUrl ? selectedUrl : PAYMENT"
      @submit.prevent="submitData"
      :class="{ 'loading-opacity': loading }"
    >
      <app-overlay-loader v-if="loading" />
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
            :placeholder="$t('Select Option')"
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
              :label="$t('e_description')"
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

      <div class="form-group mt-5 mb-5">
        <app-submit-button @click="submitData" :title="$t('save')" />
      </div>
    </form>
    <payment-list :contactPersonId="formData.contact_person_id" />
  </div>
</template>

<script>
import PaymentList from './PaymentList'
import CustomRadioInput from './CustomRadioInput'
import DatePicker from './DatePicker'
import DropDown from './DropDown'
import { mapGetters } from 'vuex'
import FormHelperMixins from '../../../../../../../js/common/Mixin/Global/FormHelperMixins'
import { PAYMENT } from '../../../../../Config/ApiUrl-CPB'
import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import {
  axiosGet,
  axiosPatch,
  urlGenerator,
} from '../../../../../../common/Helper/AxiosHelper'

export default {
  name: 'Payments',
  mixins: [FormHelperMixins],
  props: {
    props: {
      customerId: {},
    },
  },
  components: {
    DefaultAction,
    PaymentList,
    DatePicker,
    DropDown,
    CustomRadioInput,
  },
  data() {
    return {
      loading: false,
      personlist: {},
      PAYMENT,
      formData: {
        contact_person_id: this.props,
        user_id: null,
        title: '',
        description: '',
        source: 'null',
        date: '',
        net_gbp: '',
        gross_gbp: '',
        payment_status: 'null',
      },
      textEditorReboot: false,
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
    submitData() {
      this.loading = true
      this.message = ''
      this.errors = {}
      this.getUserInformationId()
    },

    afterSuccess(response) {
      this.loading = false
      this.$toastr.s('', response.data.message)
      location.reload()
    },

    cancelUser() {
      location.reload()
    },

    getUserInformationId() {
      axiosGet(`admin/authenticate-user`)
        .then(({ data }) => {
          // Assuming `data` is an object with an `id` property
          const userId = data.id

          // Update formData with the user ID
          this.formData.user_id = userId

          // Now, you can proceed to save the form
          this.save(this.formData)
        })
        .catch((error) => this.$toastr.e(error))
    },
  },
}
</script>
<style scoped>
.app-input {
  margin-bottom: 10px;
}

.app-input-label {
  font-size: 14px;
  margin-bottom: 4px;
  display: block;
}

.app-input-dropdown {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.app-input-error {
  color: #ff0000;
  font-size: 12px;
  margin-top: 4px;
  display: block;
}

.selected {
  background-color: #007bff;
  color: #fff;
  border-color: #007bff;
}

.radio-buttons {
  display: flex;
  gap: 16px;
  margin-top: 8px; /* Adjust margin as needed */
}

.radio-button {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.radio-button input {
  margin-right: 8px;
  cursor: pointer;
}

/* Style for checked radio button */
.radio-button input:checked + label {
  font-weight: bold;
  color: #007bff; /* Change color as needed */
}
</style>
