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
      Payments List
    </h5>
    <div class="col-lg-12" style="margin-top: 20px">
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Title</th>
            <th>Source</th>
            <th>Description</th>
            <th>net_gbp</th>
            <th>gross_gbp</th>
            <th>payment_status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="payment in payments" :key="payment.id">
            <td>{{ payment.date }}</td>
            <td>{{ payment.title }}</td>
            <td>{{ payment.source }}</td>
            <td>{{ payment.description }}</td>
            <td>{{ payment.net_gbp }}</td>
            <td>{{ payment.gross_gbp }}</td>
            <td>{{ payment.payment_status }}</td>
            <td>
              <default-action
                :key="`default`"
                :actions="actions"
                :row-data="payment"
                :unique-key="payment.id"
                @action="triggerAction"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-3">

      <div class="d-flex justify-content-center">
        <app-contact-person-payments-modal
          v-if="isModalActive"
          v-model="isModalActive"
          :user-id="contactPersonId"
          :selectedUrl="url"
          :user-type="userType"
          @close="isModalActive = false"
          @reload="fetchPayments"
        />
        <app-confirmation-modal
          v-if="confirmationModalActive"
          :firstButtonName="$t('yes')"
          icon="trash-2"
          modal-class="warning"
          modal-id="app-confirmation-modal"
          @cancelled="cancelled"
          @confirmed="deleteNote"
          @reload="fetchPayments"
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
} from '../../../../../../common/Helper/AxiosHelper'
import { PAYMENT } from '../../../../../Config/ApiUrl-CPB'
// import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import PaymentEditModal from './PaymentCreateEditModal'
import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import HelperMixin from '../../../../../../common/Mixin/Global/HelperMixin'

export default {
  components: {
    DefaultAction,
    PaymentEditModal,
  },
  props: {
    // userId: {
    //   type: Number,
    //   required: true,
    // },
    contactPersonId: {
      type: Number,
      required: true,
    },
    props: {},
  },
  mixins: [HelperMixin],
  data() {
    return {
      payments: [],
      deleteUrl: PAYMENT,
      isModalActive: false,
      payments: '',
      url: '',
      noteData: '',
      userType: 'payment',
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
    this.fetchPayments()
  },
  created() {
    axiosGet(`app/payment/${this.contactPersonId}`)
      .then((response) => {
        this.payments = response
      })
      .catch((error) => {
        this.loading = true
        this.$toastr.e(error)
      })
  },
  methods: {
    fetchPayments() {
      axiosGet(`app/payment/${this.contactPersonId}`)
        .then(({ data }) => {
          this.payments = data
        })
        .catch((error) => {
          console.error('Error fetching payments:', error)
        })
    },
    editNoteModal() {
      this.isModalActive = true
      this.url = ''
    },
    triggerAction(row, action) {
      if (action.type === 'edit') {
        this.url = `${PAYMENT}/${row.id}`
        this.isModalActive = true
        this.status = row.status
      } else if (action.type === 'delete') {
        this.confirmationModalActive = true
        this.deleteUrl = `${PAYMENT}/${row.id}`
      } else {
        this.getAction(row, action, active)
      }
    },
    deleteNote() {
      axiosDelete(this.deleteUrl).then(({ data }) => {
        this.cancelled()
        this.toastAndReload(data.message, 'all-branches-table')
        location.reload()
        this.fetchPayments()
      })
      location.reload()
    },
  },
}
</script>

<style scoped>
/* Add your component-specific styles here */
</style>
