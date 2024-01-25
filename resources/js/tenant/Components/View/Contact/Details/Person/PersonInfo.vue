<template>
  <form
    ref="form"
    :data-url="selectedUrl ? selectedUrl : CONTACTPERSON"
    :class="{ 'loading-opacity': loading }"
    @submit.prevent="submitData"
  >
    <app-overlay-loader v-if="loading" />

    <app-form-group
      page="page"
      :label="$t('name')"
      type="text"
      id="input-text-first-name"
      :placeholder="$t('e_name', '')"
      v-model="personlist.name"
      :error-message="$errorMessage(errors, 'name')"
    />

    <app-form-group
      page="page"
      :label="$t('phonenumber')"
      type="text"
      id="input-text-last-name"
      :placeholder="$t('e_phone_number', '')"
      v-model="personlist.phonenumber"
      :error-message="$errorMessage(errors, 'phonenumber')"
    />

    <app-form-group
      page="page"
      :label="$t('email')"
      type="text"
      id="input-text-last-name"
      :placeholder="$t('e_email', '')"
      v-model="personlist.email"
      :error-message="$errorMessage(errors, 'email')"
    />

    <app-form-group
      page="page"
      :label="$t('designation')"
      type="text"
      id="input-text-last-name"
      :placeholder="$t('e_designation', '')"
      v-model="personlist.designation"
      :error-message="$errorMessage(errors, 'designations')"
    />

    <div class="col-md-12 d-flex" style="padding-left: 0px">
      <div
        class="col-md-3"
        style="
          padding-left: 0px !important;
          margin-top: auto !important;
          margin-bottom: auto !important;
        "
      >
        <label>{{ $t('person_status') }}</label>
      </div>
      <div class="col-md-9" style="padding-left: 10px">
        <app-dropdown-options
          type="dropdown"
          :options="dropdownOptions"
          v-model="formData.status"
          :color="color"
          :placeholder="$t('Select Option')"
          :error-message="$errorMessage(errors, 'selectedOption')"
        />
      </div>
    </div>

    <div class="form-group mt-5 mb-0">
      <app-submit-button @click="submitData" :title="$t('save')" />
    </div>
  </form>
</template>

<script>
import FormHelperMixins from '../../../../../../../js/common/Mixin/Global/FormHelperMixins'
import { CONTACTPERSON } from '../../../../../Config/ApiUrl-CPB'
import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import {
  axiosGet,
  axiosPatch,
  urlGenerator,
} from '../../../../../../common/Helper/AxiosHelper'
import DropDown from './DropDown'

export default {
  name: 'ProfilePersonalInfo',
  mixins: [FormHelperMixins],

  props: {
    props: {
      customerId: {},
    },
  },
  components: {
    DefaultAction,
    DropDown,
  },
  data() {
    return {
      loading: false,
      personlist: {},
      CONTACTPERSON,
      formData: {
        status: 'null',
      },
      dropdownOptions: [
        { label: 'SUSP', value: 'SUSP', color: '#BFBFBF' },
        { label: 'COOL', value: 'COOL', color: '#00B0F0' },
        { label: 'WARM', value: 'WARM', color: '#FFE598' },
        { label: 'HOT', value: 'HOT', color: '#FFC000' },
        { label: 'ACC', value: 'ACC', color: '#C5E0B3' },
        { label: 'LOST', value: 'LOST', color: '#FF0000' },
        // Add more options as needed
      ],
    }
  },
  methods: {
    submitData() {
      //   let profile = this.personlist
      //   this.loading = true
      // profile.gender = this.personlist.gender;
      //   profile.phonenumber = this.personlist.phonenumber
      // profile.address = this.personlist.address;
      // profile.date_of_birth = this.personlist.date_of_birth ? moment(this.personlist.date_of_birth).format('YYYY-MM-DD') : '';
      //   this.save(profile)

      this.loading = true
      this.message = ''
      this.errors = {}

      const payload = {
        ...this.personlist,
        status: this.formData.status,
      }

      axiosPatch(`${CONTACTPERSON}/${this.props}`, payload)
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
    },

    cancelUser() {
      location.reload()
    },

    getcustomerData() {
      axiosGet(`app/get-customer-name-id/${this.props}`)
        .then((response) => {
          const customerData = response.data.data
          this.formData.status = customerData.status
        })
        .catch((error) => {
          // Handle error if needed
          console.error('Error fetching customer data:', error)
        })
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
    this.$store
      .dispatch('getPerson', this.props)
      .then(() => {
        this.preloader = false // Assuming 'preloader' is a boolean flag for loading state
        this.getcustomerData()
      })
      .catch((error) => {
        console.error('Error fetching person data:', error)
      })
  },
  computed: {
    getPerson() {
      return this.$store.getters.getPerson
    },
  },
}
</script>
