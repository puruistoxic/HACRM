<template>
  <div id="activity">
    <div class="row">
      <div class="col-lg-12">
        <span v-for="activity in activities" :key="activity.id">
        <table class="table">
          <thead>
            <tr>
              <th>Contact Person Name</th>
              <th>Contact Owner Name</th>
              <th>Activity ID</th>
              <th>Title</th>
              <th>Description</th>
              <th>Date</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>Activity Status</th>
              <!-- Add more columns as needed -->
            </tr>
          </thead>
          
          <tbody>
              <tr
                v-for="all_activities in activity.all_activities"
                :key="all_activities.id"
              >
                <td>{{ all_activities.contact_person_name }}</td>
                <td>{{ all_activities.user_name }}</td>
                <td>{{ all_activities.activityId }}</td>
                <td>{{ all_activities.title }}</td>
                <td>{{ all_activities.description }}</td>
                <td>{{ formatDate(all_activities.created_at) }}</td>
                <td>{{ all_activities.start_time }}</td>
                <td>{{ all_activities.end_time }}</td>
                <td>{{ all_activities.activity_status }}</td>
                <!-- Add more columns as needed -->
              </tr>
            </tbody>
          
        </table>
        </span>
      </div>
    </div>
  </div>
</template>

<script>
import HelperMixin from '../../../../../common/Mixin/Global/HelperMixin'
import DefaultAction from '../../../../../core/components/datatable/DefaultAction'
import AddressModal from './AddressCreateEditModal'
import { ADDRESS, CUSTOMER_ADDRESS } from '../../../../Config/ApiUrl-CPB'
import { axiosDelete, axiosGet } from '../../../../../common/Helper/AxiosHelper'

export default {
  name: 'Address',
  components: {
    DefaultAction,
    AddressModal,
  },
  props: {
    props: {},
  },
  mixins: [HelperMixin],
  data() {
    return {
      isModalActive: false,
      activities: '',
      url: '',
      customerData: '',
      userType: 'customer',
      deleteUrl: ADDRESS,
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

  created() {
    axiosGet(`app/all-activity/${this.props}`)
      .then((response) => {
        this.activities = response.data
      })
      .catch((error) => {
        this.$toastr.e(error)
      })
  },

  methods: {
    getAddress() {
      axiosGet(`app/all-activity/${this.props}`)
        .then((response) => {
          this.activities = response.data
        })
        .catch((error) => {
          this.$toastr.e(error)
        })
    },
    formatDate(dateTimeString) {
      const date = new Date(dateTimeString);
      // Format the date as desired, for example, using toLocaleDateString
      return date.toLocaleDateString();
    },
    fullAddress(activity) {
      return `${activity.details ? activity.details + ', ' : ''}
            ${activity.area ? activity.area + ', ' : ''}
            ${activity.city ? activity.city + ', ' : ''}
            ${activity.country ? activity.country + ' -' : ''}
            ${activity.zip_code ? activity.zip_code : ''}`
    },
    addAddressModal() {
      this.isModalActive = true
      this.url = ''
    },

    cancelled() {
      this.confirmationModalActive = false
      this.deleteUrl = ''
    },
    triggerAction(row, action) {
      if (action.type === 'edit') {
        this.url = `${ADDRESS}/${row.id}`
        this.isModalActive = true
        this.status = row.status
      } else if (action.type === 'delete') {
        this.confirmationModalActive = true
        this.deleteUrl = `${ADDRESS}/${row.id}`
      } else {
        this.getAction(row, action, active)
      }
    },
    deleteAddress() {
      axiosDelete(this.deleteUrl).then(({ data }) => {
        this.cancelled()
        this.toastAndReload(data.message, 'all-branches-table')
        this.getAddress()
      })
    },
  },
}
</script>
