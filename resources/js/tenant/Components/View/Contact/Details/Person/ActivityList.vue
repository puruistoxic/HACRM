<template>
  <div>
    <hr />
    <h5
      style="
        margin-top: 20px;
        margin-bottom: 20px;
        font-size: 18.7px;
        text-align: center;
      "
    >
      Activities List
    </h5>
    <div class="col-lg-12" style="margin-top: 20px">
      <table class="table">
        <thead>
          <tr>
            <th>Activity Owner</th>
            <th>Activity Type</th>
            <th>Title</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Activity Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="activity in activities" :key="activity.id">
            <td>{{ activity.user_name }}</td>
            <td>{{ activity.activityId }}</td>
            <td>{{ activity.title }}</td>
            <td>{{ activity.start_time }}</td>
            <td>{{ activity.end_time }}</td>
            <td>{{ activity.activity_status }}</td>
            <td>
              <default-action
                :key="`default`"
                :actions="actions"
                :row-data="activity"
                :unique-key="activity.id"
                @action="triggerAction"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-3">
      <div class="d-flex justify-content-center">
        <app-contact-person-activities-modal
          v-if="isModalActive"
          v-model="isModalActive"
          :user-id="contactPersonId"
          :selectedUrl="url"
          :customer-id="customerId"
          :user-type="userType"
          @close="isModalActive = false"
          @reload="fetchActivities"
        />
        <app-confirmation-modal
          v-if="confirmationModalActive"
          :firstButtonName="$t('yes')"
          icon="trash-2"
          modal-class="warning"
          modal-id="app-confirmation-modal"
          @cancelled="cancelled"
          @confirmed="deleteActivity"
          @reload="fetchActivities"
        />
      </div>
    </div>
  </div>
</template>

<script>
// import { axiosGet } from '../../../../../../common/Helper/AxiosHelper'
import {
  axiosDelete,
  urlGenerator,
  axiosGet,
  axiosPatch,
} from '../../../../../../common/Helper/AxiosHelper'
import { ACTIVITY } from '../../../../../Config/ApiUrl-CPB'
// import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import ActivityEditModal from './ActivityCreateEditModal'
import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import HelperMixin from '../../../../../../common/Mixin/Global/HelperMixin'

export default {
  components: {
    DefaultAction,
    ActivityEditModal,
  },
  props: {
    contactPersonId: {
      type: Number,
      required: true,
    },
    customerId: {
      type: String, // Adjust the type accordingly
      required: true,
    },
    props: {},
  },
  mixins: [HelperMixin],
  data() {
    return {
      activities: [],
      completed: 'completed',
      deleteUrl: ACTIVITY,
      isModalActive: false,
      activities: '',
      url: '',
      activityData: '',
      userType: 'activity',
      confirmationModalActive: false,
      actions: [
        {
          title: this.$t('edit'),
          icon: 'edit',
          type: 'edit',
        },
        {
          title: this.$t('delete'),
          icon: 'trash-2',
          type: 'delete',
        },
      ],
    }
  },
  mounted() {
    this.fetchActivities()
  },
  created() {
    axiosGet(`app/activity/${this.contactPersonId}`)
      .then((data) => {
        this.activities = data
      })
      .catch((error) => {
        this.$toastr.e(error)
      })
  },
  methods: {
    fetchActivities() {
      axiosGet(`app/activity/${this.contactPersonId}`)
        .then(({ data }) => {
          this.activities = data
        })
        .catch((error) => console.error('Error fetching activities:', error))
    },
    editActivityModal() {
      this.isModalActive = true
      this.url = ''
    },
    triggerAction(row, action) {
      if (action.type === 'edit') {
        this.url = `${ACTIVITY}/${row.id}`
        this.isModalActive = true
        this.status = row.status
      } else if (action.type === 'delete') {
        this.confirmationModalActive = true
        this.deleteUrl = `${ACTIVITY}/${row.id}`
      } else {
        this.getAction(row, action, active)
      }
    },
    afterSuccess(response) {
      this.loading = false
      this.$toastr.s('', response.data.message)
      location.reload()
    },
    deleteActivity() {
      axiosDelete(this.deleteUrl).then(({ data }) => {
        this.cancelled()
        this.toastAndReload(data.message, 'all-branches-table')
        this.fetchActivities()
      })
      location.reload()
    },
    completeActivity(row) {
      // Customize this method to send a PATCH request to update the data
      const patchUrl = `app/contactperson/activity/${row.id}`
      const newData = { completed: true } // Modify this data as needed
      axiosPatch(patchUrl, newData)
        .then((response) => {
          console.log(response.data)
          this.$toastr.s('', response.data.message)
          this.fetchActivities()
        })
        .catch((error) => {
          this.$toastr.e(error)
        })
    },
  },
}
</script>

<style scoped>
/* Add your component-specific styles here */
</style>
