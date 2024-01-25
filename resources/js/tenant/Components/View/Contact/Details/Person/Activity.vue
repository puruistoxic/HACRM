<template>
  <div>
    <form
      ref="form"
      :data-url="selectedUrl ? selectedUrl : ACTIVITY"
      :class="{ 'loading-opacity': loading }"
      @submit.prevent="submitData"
    >
      <app-overlay-loader v-if="loading" />
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
             :placeholder="$t('enter_title')"
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
              :placeholder="$t('enter_description')"
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

      <form-group-selectable
        type="multi-create"
        :label="$t('participants')"
        list-value-field="name"
        v-model="formData.participants"
        :error-message="$errorMessage(errors, 'participants')"
        :fetch-url="`app/contact-person/${this.customer_id}`"
        :store-data="attributeVariationStore"
        @list="attributeVariations = $event"
        @input="chipChange"
      />

      <app-form-group-selectable
        type="multi-create"
        :label="$t('collaborators')"
        list-value-field="first_name"
        v-model="formData.collaborators"
        :error-message="$errorMessage(errors, 'collaborators')"
        :fetch-url="`app/get-user-data`"
        :store-data="attributeVariationStore2"
        @list="attributeVariationss = $event"
        @input="chipChange2"
      />

      <div class="row">
        <div class="col-md-12">
          <label
            class="mb-lg-0 col-lg-3"
            style="
              padding-left: 0px !important;
              margin-bottom: 7.48px !important;
            "
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
              />
              <label for="completed">{{ $t('completed') }}</label>
            </div>
            <div class="radio-button">
              <input
                type="radio"
                id="notCompleted"
                v-model="formData.activity_status"
                value="Not Completed"
              />
              <label for="notCompleted">{{ $t('not_completed') }}</label>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group mt-5 mb-5">
        <app-submit-button @click="submitData" :title="$t('save')" />
      </div>
    </form>
    <activity-list
      :contactPersonId="formData.contact_person_id"
      :customer-id="customer_id"
    />
  </div>
</template>

<script>
import CustomRadioInput from './CustomRadioInput'
import ActivityList from './ActivityList'
import { mapGetters } from 'vuex'
import FormHelperMixins from '../../../../../../../js/common/Mixin/Global/FormHelperMixins'
import {
  ACTIVITY,
  CUSTOMER_CONTACTPERSON,
} from '../../../../../Config/ApiUrl-CPB'
import { APP_USERS } from '../../../../../../common/Config/apiUrl'
import { SELECTABLE_VARIATIONS } from '../../../../../Config/ApiUrl-CP'
import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import {
  axiosGet,
  axiosPatch,
  urlGenerator,
} from '../../../../../../common/Helper/AxiosHelper'

export default {
  name: 'Activites',
  mixins: [FormHelperMixins],
  props: {
    props: {
      customerId: {},
    },
  },
  components: {
    DefaultAction,
    ActivityList,
    CustomRadioInput,
  },
  data() {
    return {
      loading: false,
      personlist: {},
      namelist: {},
      attributeVariations: [],
      attributeVariationss: [],
      activityId: '',
      ACTIVITY,
      CUSTOMER_CONTACTPERSON,
      formData: {
        // contact_person_id: this.props,
        // user_id: '',
        // activityId: this.activityId,
        // participants: '',
        // collaborators: '',
        // user_name: '',
        // activity_status: 'completed',
        // variations: [],
        // attribute_variation: [],
        // chips: {},
        // id: this.userId,
        user_id: '',
        contact_person_id: this.props,
        activityId: this.activityId,
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
  mounted() {
    axiosGet(`app/get-customer-name-id/${this.props}`)
      .then((response) => {
        const customerData = response.data.data
        this.customer_id = customerData.customer_id
        // console.log(this.customer_id)
        this.fetchDropdownOptions()
      })
      .catch((error) => {
        // Handle error if needed
        console.error('Error fetching customer data:', error)
      })
  },
  methods: {
    async submitData() {
      try {
        this.loading = true
        this.message = ''
        this.errors = {}

        // Fetch user_id using async/await
        const { data } = await axiosGet(`admin/authenticate-user`)

        const fullName = `${data.first_name} ${data.last_name}`

        this.formData.user_id = data.id
        this.formData.user_name = fullName
        // Continue with the rest of the code
        // const selectedOptionNames = this.selectedOptions.map((id) => {
        //   const option = this.dropdownOptions.find((opt) => opt.id === id)
        //   return option ? option.name : null
        // })

        // const selectedOptionNamesString = selectedOptionNames.join(', ')

        // this.formData.participants = selectedOptionNamesString

        // Save the form data
        this.save(this.formData)
      } catch (error) {
        this.$toastr.e(error)
      }
    },
    afterSuccess(response) {
      this.$toastr.s('', response.data.message)
      setTimeout('', location.reload())
    },

    cancelUser() {
      location.reload()
    },

    updateActivityId(selectedOption, activityId) {
      // this.formData.activityId = activityId
    },

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
      const nam = this.attributeVariationss
        .filter(({ id }) => variation.includes(id))
        .map((e) => e.first_name)

      this.formData.attribute_variation = nam
      this.formData.collaboratorsString = nam.join(', ')
    },
    attributeVariationStore2(variationName) {
      let tempId = this.attributeVariationss.length
        ? this.attributeVariationss[this.attributeVariationss.length - 1].id + 1
        : Date.now()

      this.attributeVariationss.push({
        id: tempId,
        name: variationName,
      })

      this.formData.variations.push(tempId)

      this.formData.attribute_variation.push(variationName)
    },

    // getUserInformationId(payload) {
    //   axiosGet(`admin/authenticate-user`)
    //     .then(({ data }) => {
    //       // Assuming `data` is an object with an `id` property
    //       this.userId = data.id
    //       console.log('Fetched ', this.userId)
    //       // Update formData with the user ID
    //       this.formData.user_id = userId
    //       console.log('Fetched user ID:', this.formData.user_id)

    //       this.save(payload)

    //       // Now, you can proceed to save the form
    //       // this.save(this.formData)
    //     })
    //     .catch((error) => this.$toastr.e(error))
    // },

    fetchDropdownOptions() {
      axiosGet(`app/contact-person/${this.customer_id}`)
        .then((response) => {
          this.dropdownOptions = response.data
        })
        .catch((error) => {
          console.error('Error fetching dropdown options:', error)
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
  },
}
</script>

<style scoped>
.chips-dropdown {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.chip {
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  cursor: pointer;
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
