<template>
  <form
    ref="form"
    :data-url="selectedUrl ? selectedUrl : CONTACTPERSON"
    :class="{ 'loading-opacity': loading }"
    @submit.prevent="submitData"
  >
    <app-overlay-loader v-if="loading" />
    <div class="row mt-3">
      <div class="col-md-6">
        <label>{{ $t('country') }}</label>
        <app-input
          type="select"
          :list="countryList"
          :key="countryList.length"
          list-value-field="name"
          v-model="personlist.country_id"
          :error-message="$errorMessage(errors, 'country')"
        />
      </div>
      <div class="col-md-6">
        <app-form-group
          :label="$t('state')"
          :placeholder="$t('e_state')"
          v-model="personlist.state"
          :required="true"
          :error-message="$errorMessage(errors, 'state')"
        />
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-md-6">
        <app-form-group
          :label="$t('city')"
          :placeholder="$t('e_city')"
          v-model="personlist.city"
          :required="true"
          :error-message="$errorMessage(errors, 'city')"
        />
      </div>
      <div class="col-md-6">
        <app-form-group
          :label="$t('county')"
          :placeholder="$t('e_county')"
          v-model="personlist.area"
          :required="true"
          :error-message="$errorMessage(errors, 'county')"
        />
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-md-6">
        <app-form-group
          :label="$t('zip_code')"
          :placeholder="$t('e_zip_code')"
          v-model="personlist.zip_code"
          :required="true"
          :error-message="$errorMessage(errors, 'zip_code')"
        />
      </div>
      <div class="col-md-6">
        <app-form-group
          :label="$t('address')"
          :placeholder="$t('e_address_details')"
          v-model="personlist.details"
          :required="true"
          :error-message="$errorMessage(errors, 'address')"
        />
      </div>
    </div>

    <div class="form-group mt-5 mb-0">
      <app-submit-button @click="submitData" :title="$t('save')" />
    </div>
  </form>
</template>

<script>
import moment from 'moment'
// import FormHelperMixins from "../../../../../Mixin/Global/FormHelperMixins";
import FormHelperMixins from '../../../../../../../js/common/Mixin/Global/FormHelperMixins'
// import { CONTACTPERSON } from '../../../../../Config/ApiUrl-CPB'
import { CONTACTPERSON } from '../../../../../Config/ApiUrl-CPB'
import { COUNTRIES } from '../../../../../Config/ApiUrl-CP'
import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import {
  axiosGet,
  axiosPatch,
  urlGenerator,
} from '../../../../../../common/Helper/AxiosHelper'

export default {
  name: 'AdditionalInfo',
  mixins: [FormHelperMixins],

  props: {
    props: {
      customerId: {},
    },
  },
  components: {
    DefaultAction,
  },
  data() {
    return {
      loading: false,
      personlist: {},
      CONTACTPERSON,
      COUNTRIES,
      countryOptions: {
        url: urlGenerator('app/selectable-countries'),
        query_name: 'search_by_name',
        per_page: 10,
        loader: 'app-pre-loader', // by default 'app-overlay-loader'
        modifire: ({ id, name: value }) => ({ id, value }),
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
    submitData() {
      this.loading = true
      this.message = ''
      this.errors = {}

      axiosPatch(`${CONTACTPERSON}/${this.props}`, this.personlist)
        .then(() => {
          setTimeout(() => {
            window.location.reload()
          }, 2500)
        })
        .catch((err) => this.$toastr.e('', err.data.message))
    },

    afterSuccess(response) {
      this.$toastr.s('', response.data.message)
      setTimeout('', location.reload())
      this.axiosGet('app/selectable-countries')
        .then((res) => (this.countryList = res.data))
        .finally(() => {
          this.preloader = false
        })
    },

    cancelUser() {
      location.reload()
    },
  },

  watch: {
    getPerson: {
      handler: function (user) {
        if (user) this.preloader = false
        this.personlist = {
          ...user,
          profile: {
            ...user.profile,
          },
        }
      },
      deep: true,
    },
  },
  mounted() {
    this.$store.dispatch('getPerson', this.props)
    this.getCountries()
    this.preloader = true
  },
  computed: {
    getPerson() {
      return this.$store.getters.getPerson
    },
  },
}
</script>
