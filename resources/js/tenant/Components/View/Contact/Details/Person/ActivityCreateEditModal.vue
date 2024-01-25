<template>
  <modal
    id="activity-modal"
    v-model="showModal"
    size="large"
    :title="generateModalTitle('activity_details')"
    @submit="submitData"
    :loading="loading"
    :preloader="preloader"
  >
    <form
      v-if="!loading"
      ref="form"
      :data-url="selectedUrl ? selectedUrl : ACTIVITY"
      @submit.prevent="submitData"
    >
      <div class="row">
        <div class="col-md-12">
          <label
            class="mb-lg-0 col-lg-3"
            style="
              padding-left: 0px !important;
              margin-bottom: 7.48px !important;
            "
          >
            {{ $t('activity') }}
          </label>
          <CustomRadioInput
            v-model="formData.activityId"
            :options="customActivityTypeList"
            :required="true"
            :activityId="activityId"
            @input="updateActivityId"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <app-form-group
            :label="$t('title')"
            :placeholder="$placeholder('title')"
            v-model="formData.title"
            :required="true"
            :error-message="$errorMessage(errors, 'title')"
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
              :placeholder="$placeholder('description')"
            />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label
              class="mb-lg-0 col-lg-6"
              style="
                padding-left: 0px !important;
                margin-bottom: 7.48px !important;
              "
            >
              {{ $t('start_time') }}
            </label>
            <input
              type="time"
              v-model="formData.start_time"
              class="form-control"
            />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label
              class="mb-lg-0 col-lg-6"
              style="
                padding-left: 0px !important;
                margin-bottom: 7.48px !important;
              "
            >
              {{ $t('end_time') }}
            </label>
            <input
              type="time"
              v-model="formData.end_time"
              class="form-control"
            />
          </div>
        </div>
      </div>

      <!-- <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="chipsDropdown">Participants:</label>
            <div class="chips-dropdown">
              <div
                v-for="option in dropdownOptions"
                :key="option.id"
                class="chip"
                @click="toggleOption(option.id)"
                :class="{ selected: isSelected(option.id) }"
              >
                {{ option.name }}
              </div>
            </div>
          </div>
        </div>
      </div> -->

      <label
        class="mb-lg-0 col-lg-12"
        style="
          padding-left: 0px !important;
          margin-bottom: 5.48px !important;
          color: lightgray !important;
        "
      >
        Previously Added Participants:
        <span style="color: #906725 !important">
          {{ formData.participantsString }}
        </span>
      </label>

      <form-group-selectable
        type="multi-create"
        :label="$t('participants')"
        list-value-field="name"
        v-model="formData.participants"
        :error-message="$errorMessage(errors, 'participants')"
        :fetch-url="`app/contact-person/${this.customerId}`"
        :store-data="attributeVariationStore"
        @list="attributeVariations = $event"
        @input="chipChange"
      />
    </form>
    <label
      class="mb-lg-0 col-lg-12"
      style="
        padding-left: 0px !important;
        margin-bottom: 5.48px !important;
        color: lightgray !important;
      "
    >
      Previously Added Collaborators:
      <span style="color: #906725 !important">
        {{ formData.collaboratorsString }}
        <!-- {{ collaboratorsArray.join(', ') }} -->
      </span>
    </label>
    <app-form-group-selectable
      type="multi-create"
      :label="$t('collaborators')"
      list-value-field="first_name"
      v-model="formData.collaborators"
      :error-message="$errorMessage(errors, 'collaborators')"
      :fetch-url="`app/get-user-data`"
      :store-data="attributeVariationStore2"
      @list="attributeVariations = $event"
      @input="chipChange2"
    />

    <div class="row">
      <div class="col-md-12">
        <label
          class="mb-lg-0 col-lg-3"
          style="padding-left: 0px !important; margin-bottom: 7.48px !important"
        >
          {{ $t('activity_status') }}
        </label>

        <!-- Improved styling for radio buttons -->
        <div class="radio-buttons">
          <div class="radio-button">
            <input
              type="radio"
              id="completed"
              v-model="formData.activity_status"
              value="Completed"
              :disabled="!isAuthenticated"
            />
            <label for="completed">{{ $t('completed') }}</label>
          </div>
          <div class="radio-button">
            <input
              type="radio"
              id="notCompleted"
              v-model="formData.activity_status"
              value="Not Completed"
              :disabled="!isAuthenticated"
            />
            <label for="Not Completed">{{ $t('not_completed') }}</label>
          </div>
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
import CustomRadioInput from './CustomRadioInput'
import FormHelperMixins from '../../../../../../common/Mixin/Global/FormHelperMixins'
import ModalMixin from '../../../../../../common/Mixin/Global/ModalMixin'
import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import { ACTIVITY } from '../../../../../Config/ApiUrl-CPB'
import { COUNTRIES } from '../../../../../Config/ApiUrl-CP'
import CoreLibrary from '../../../../../../core/helpers/CoreLibrary'
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
    CustomRadioInput,
  },
  props: {
    userId: {},
    userType: {
      type: String,
      required: true,
    },
    customerId: {
      type: String, // Change the type as needed
      required: true,
    },
  },
  data() {
    return {
      personlist: {},
      namelist: {},
      customerData: '',
      isAuthenticated: false,
      COUNTRIES,
      ACTIVITY,
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
        user_id: '',
        participants: [], // Array to store participant names
        participantsString: '', // String to store comma-separated participant names
        collaborators: [],
        collaboratorsString: '',
        user_name: '',
        activity_status: 'completed',
        variations: [],
        attribute_variation: [],
        chips: {},
      },
      countryList: [],
      selectedOptions: [], // Add selectedOption field
      dropdownOptions: [], // Add dropdownOptions field
      customActivityTypeList: [
        { value: 'call', label: 'Call' },
        { value: 'meeting', label: 'Meeting' },
        { value: 'email', label: 'Email' },
        { value: 'task', label: 'Task' },
        { value: 'others', label: 'Others' },
      ],
      customer_id: '',
    }
  },
  computed: {
    collaboratorsArray() {
      if (this.formData.collaboratorsString) {
        return this.formData.collaboratorsString
          .split(',')
          .map((name) => name.trim())
          .filter((name) => name !== '') // Exclude empty strings
      }
      return []
    },
  },
  mounted() {
    this.getCountries()
    this.fetchCollaborators()
    // Fetch dropdown options when the component is mounted
    axiosGet(`app/get-customer-name-id/${this.props}`)
      .then((response) => {
        const customerData = response.data.data
        this.customer_id = customerData.customer_id
        this.fetchDropdownOptions()
      })
      .catch((error) => {
        // Handle error if needed
        console.error('Error fetching customer data:', error)
      })
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
      $('#activity-modal').modal('hide')
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

    toggleOption(optionId) {
      // Toggle the selected state of the option
      if (this.isSelected(optionId)) {
        this.selectedOptions = this.selectedOptions.filter(
          (id) => id !== optionId
        )
      } else {
        this.selectedOptions.push(optionId)
      }
    },

    isSelected(optionId) {
      // Check if the option is selected
      return this.selectedOptions.includes(optionId)
    },

    // getParticipantsArray() {
    //   if (this.formData.participantsString) {
    //     const participantArray = this.formData.participantsString
    //       .split(',')
    //       .map((name) => name.trim())
    //   }
    //   console.log('participants Array: ' + participantArray)
    // },

    chipChange(variation) {
      const names = this.attributeVariations
        .filter(({ id }) => variation.includes(id))
        .map((e) => e.name)
      this.formData.attribute_variation = names

      this.formData.participantsString = names.join(', ')
      // Log the string of participant names
    },
    attributeVariationStore(variationName) {
      let tempId = this.attributeVariations.length
        ? this.attributeVariations[this.attributeVariations.length - 1].id + 1
        : Date.now()

      this.attributeVariations.push({
        id: tempId,
        name: variationName,
      })

      this.formData.variations.push(tempId)

      this.formData.attribute_variation.push(variationName)
    },

    chipChange2(variation) {
      const names = this.attributeVariations
        .filter(({ id }) => variation.includes(id))
        .map((e) => e.first_name)
      // this.formData.attribute_variation = names
      // this.formData.collaboratorsString = names.join(', ')
      this.formData.attribute_variation = names
      this.formData.collaboratorsString = names.join(', ')
    },
    attributeVariationStore2(variationName) {
      let tempId = this.attributeVariations.length
        ? this.attributeVariations[this.attributeVariations.length - 1].id + 1
        : Date.now()

      this.attributeVariations.push({
        id: tempId,
        name: variationName,
      })

      this.formData.variations.push(tempId)

      this.formData.attribute_variation.push(variationName)
    },

    fetchCollaborators() {
      axiosGet(`admin/auth/users`)
        .then((response) => {
          const apiCollaborators = response.data.data.map(
            (item) => item.first_name
          )
          console.log(apiCollaborators)
          const matchingCollaborators = this.collaboratorsArray.filter(
            (collaborator) => apiCollaborators.includes(collaborator)
          )

          if (matchingCollaborators.length > 0) {
            this.checkAuthentication(matchingCollaborators)
          } else {
            this.isAuthenticated = true
          }
        })
        .catch((error) => {
          console.error('Error fetching participants:', error)
        })
    },
    checkAuthentication(matchingCollaborators) {
      // axiosGet(`admin/authenticate-user`)
      //   .then((response) => {
      //     this.isAuthenticated = response.data.first_name
      //     console.log('Is authenticated:', this.isAuthenticated)

      //     if (this.isAuthenticated) {
      //       console.log('Yes, you are authenticated.')
      //     } else {
      //       console.log('No, you are not authenticated.')
      //     }
      //   })
      //   .catch((error) => {
      //     console.error('Error checking authentication:', error)
      //   })
      console.log('in function')
      axiosGet(`admin/authenticate-user`)
        .then((response) => {
          console.log(response.data.first_name)
          const isAuthenticatedUser = matchingCollaborators.some(
            (collaborator) => collaborator === response.data.first_name
          )

          console.log('Is authenticated:', isAuthenticatedUser)

          if (isAuthenticatedUser) {
            this.isAuthenticated = true
          } else {
            this.isAuthenticated = false
          }
        })
        .catch((error) => {
          console.error('Error checking authentication:', error)
        })
    },
  },
}
</script>
<style scoped>
.radio-buttons {
  display: flex;
  gap: 16px;
}

.radio-button {
  display: flex;
  align-items: center;
}

.radio-button input {
  margin-right: 8px;
}
</style>
