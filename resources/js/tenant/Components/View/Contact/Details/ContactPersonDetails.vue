<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-8">
        <app-breadcrumb
          :page-title="$t('person_details')"
          :button="{
            label: $t('back_to_persons_list'),
            url: getAppUrl(PERSON_LIST),
          }"
        />
      </div>
      <div class="col-sm-4">
        <div class="text-sm-right"></div>
      </div>
    </div>
    <div class="user-profile mb-primary">
      <div class="card card-with-shadow py-5 border-0">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">
            <div
              class="media border-right px-5 pr-xl-5 pl-xl-0 user-header-media"
            >
              <div class="profile-pic-wrapper">
                <div class="custom-image-upload-wrapper circle mx-xl-auto">
                  <div class="image-area d-flex">
                    <img
                      id="imageResult"
                      :src="profile_picture_link"
                      alt=""
                      class="img-fluid mx-auto my-auto"
                    />
                  </div>
                  <!-- <div class="input-area">
                    <label id="upload-label" for="upload">{{
                      $t('change')
                    }}</label>
                    <input
                      id="upload"
                      type="file"
                      onchange="readURL(this)"
                      class="form-control d-none"
                    />
                  </div> -->
                </div>
              </div>
              <div class="media-body user-info-header">
                <h4>
                  {{ personlist.name }}
                </h4>
                <p class="text-muted mt-2 mb-0">{{ personlist.email }}</p>
                <p class="mt-4 mb-0" v-if="namelist.status">
                  <span class="text-muted"> {{ $t('status') }}: </span>
                  <span :style="{ color: getStatusColor(namelist.status) }"
                    ><b> {{ namelist.status }} </b></span
                  >
                </p>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-7">
            <div
              class="user-details px-5 px-sm-5 px-md-5 px-lg-0 px-xl-0 mt-5 mt-sm-5 mt-md-0 mt-lg-0 mt-xl-0"
            >
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                  <div class="border-right custom">
                    <div class="media mb-4 mb-xl-5">
                      <div class="align-self-center mr-3">
                        <app-icon name="phone" />
                      </div>
                      <div class="media-body">
                        <p class="text-muted mb-0">{{ $t('contact') }}:</p>
                        <p class="mb-0" v-if="personlist.phonenumber">
                          {{ personlist.phonenumber }}
                        </p>
                        <p class="mb-0" v-else>{{ $t('not_added_yet') }}</p>
                      </div>
                    </div>
                    <div class="media mb-4 mb-xl-0">
                      <div class="align-self-center mr-3">
                        <app-icon name="user" />
                      </div>
                      <div class="media-body">
                        <p class="text-muted mb-0">Designation:</p>
                        <p class="mb-0" v-if="personlist.designation">
                          {{ personlist.designation }}
                        </p>
                        <p class="mb-0" v-else>{{ $t('not_added_yet') }}</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                  <div class="media mb-4 mb-xl-5">
                    <div class="align-self-center mr-3">
                      <app-icon name="user" />
                    </div>
                    <div class="media-body">
                      <p class="text-muted mb-0">
                        {{ $t('account_group_name') }}:
                      </p>
                      <p class="mb-0" v-if="namelist.customer_group_name">
                        {{ namelist.customer_group_name }}
                      </p>
                      <p class="mb-0" v-else>{{ $t('not_added_yet') }}</p>
                    </div>
                  </div>
                  <div class="media mb-4 mb-xl-5">
                    <div class="align-self-center mr-3">
                      <app-icon name="user" />
                    </div>
                    <div class="media-body">
                      <p class="text-muted mb-0">{{ $t('account_name') }}:</p>
                      <p
                        class="mb-0"
                        v-if="namelist.customer_name"
                        @click="handleCustomerNameClick"
                        style="color: blue; cursor: pointer"
                      >
                        {{ namelist.customer_name }}
                      </p>
                      <p class="mb-0" v-else>{{ $t('not_added_yet') }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <app-tab :tabs="tabs" :icon="tabIcon" />
  </div>
</template>
<script>
import { FormMixin } from '../../../../../core/mixins/form/FormMixin'
import { mapState } from 'vuex'
import CoreLibrary from '../../../../../core/helpers/CoreLibrary'
import {
  axiosPost,
  urlGenerator,
  axiosGet,
} from '../../../../../common/Helper/AxiosHelper'
import { ucWords } from '../../../../../common/Helper/Support/TextHelper'
import {
  PERSON_LIST,
  CONTACTPERSON_DETAILS,
  CUSTOMER_DETAILS,
} from '../../../../Config/ApiUrl-CPB'
// import { formatDateToLocal } from '../../Helper/Support/DateTimeHelper'

export default {
  name: 'Profile',
  mixins: [FormMixin],
  props: {
    customerId: {},
  },
  data() {
    return {
      personlist: {},
      namelist: {},
      PERSON_LIST,
      CUSTOMER_DETAILS,
      userImage: '',
      profile_picture: '',
      tabIcon: 'user',
      tabs: [
        {
          name: this.$t('personal_info'),
          title: this.$t('personal_info'),
          component: 'app-contact-person-detail-info',
          permission: '',
          props: this.customerId,
        },
        {
          name: this.$t('additional_info'),
          title: this.$t('additional_info'),
          component: 'app-contact-person-additional-info',
          permission: '',
          props: this.customerId,
        },
        {
          name: this.$t('activities'),
          title: this.$t('activities'),
          component: 'app-contact-person-activities',
          permission: '',
          props: this.customerId,
        },
        {
          name: this.$t('notes'),
          title: this.$t('notes'),
          component: 'app-contact-person-notes',
          permission: '',
          props: this.customerId,
        },
        {
          name: this.$t('payments'),
          title: this.$t('payments'),
          component: 'app-contact-person-payment',
          permission: '',
          props: this.customerId,
        },
      ],
      // formatDateToLocal,
    }
  },

  computed: {
    getPerson() {
      return this.$store.getters.getPerson
    },
    profile_picture_link() {
      return urlGenerator('/images/avatar.png')
    },
  },
  mounted() {
    // let instance = this
    // instance.$store.dispatch('getPerson', 3)
    this.$store.dispatch('getPerson', this.customerId)

    axiosGet(`app/get-customer-name-id/${this.customerId}`)
      .then((response) => {
        const customerData = response.data.data
        this.namelist.customer_name = customerData.customer_name
        this.namelist.customer_group_name = customerData.customer_group_name
        this.namelist.customer_group_id = customerData.customer_id
        this.namelist.status = customerData.status
      })
      .catch((error) => {
        // Handle error if needed
        console.error('Error fetching customer data:', error)
      })
  },

  watch: {
    getPerson: {
      handler: function (user) {
        this.personlist = user
      },
      deep: true,
    },
  },
  methods: {
    readURL() {
      this.files = this.$refs.changeProfileImage.files
      if (this.files && this.files[0]) {
        let reader = new FileReader()
        reader.onload = function (e) {
          $('#imageResult').attr('src', e.target.result)
        }
        let image = reader.readAsDataURL(this.files[0])
        let formData = new FormData()
        formData.append('profile_picture', this.files[0])
        formData.append('user_id', this.customerId)
        axiosPost(CUSTOMER_PROFILE_PICTURE_UPLOAD + this.customerId, formData)
          .then((response) => {
            location.reload()
          })
          .catch((error) => {
            this.$toastr.e(
              error.response.data ? error.response.data.profile_picture[0] : ''
            )
          })
      }
    },
    handleCustomerNameClick() {
      return window.location.replace(
        urlGenerator(CUSTOMER_DETAILS + '/' + this.namelist.customer_group_id)
      )
    },
    getStatusColor(status) {
      switch (status) {
        case 'SUSP':
          return '#BFBFBF'
        case 'COOL':
          return '#00B0F0'
        case 'WARM':
          return '#FFE598'
        case 'HOT':
          return '#FFC000'
        case 'ACC':
          return '#C5E0B3'
        case 'LOST':
          return '#FF0000'
        default:
          return 'black' // Default color
      }
    },
  },
}
</script>
