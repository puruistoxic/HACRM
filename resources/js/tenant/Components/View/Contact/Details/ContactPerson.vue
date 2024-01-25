<template>
  <div id="contact">
    <div class="row">
      <div class="col-lg-9">
        <div v-for="contactperson in contactpersons" class="row">
          <div class="col-md-3">
            <div class="media mb-4 mb-xl-0">
              <div class="align-self-center">
                <app-icon name="user" />
              </div>
              <div class="media-body align-self-center ml-3">
                <p class="mb-0">{{ contactperson.name }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <p>
              {{ fullContactPerson(contactperson) }}
            </p>
          </div>
          <div class="col-md-3">
            <div class="d-flex justify-content-center">
              <default-action
                :key="`default`"
                :actions="actions"
                :row-data="contactperson"
                :unique-key="contactperson.id"
                @action="triggerAction"
              />
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-9">
        <div class="row mt-3">
          <div class="col-md-3">
            <div class="media mb-4 mb-xl-0">
              <div class="align-self-center">
                <app-icon name="user" />
              </div>
              <div class="media-body align-self-center ml-3">
                <p class="mb-0">{{ $t('contact_details') }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <p>{{ $t('not_added_yet') }}</p>
          </div>
          <div class="col-md-3">
            <div class="d-flex justify-content-center">
              <button class="btn btn-primary" @click="addContactPersonModal">
                {{ this.$t('add') }}
              </button>
              <app-contact-person-modal
                v-if="isModalActive"
                v-model="isModalActive"
                :user-id="props"
                :selectedUrl="url"
                :user-type="userType"
                @close="isModalActive = false"
                @reload="getContactPerson"
              />
              <app-confirmation-modal
                v-if="confirmationModalActive"
                :firstButtonName="$t('yes')"
                icon="trash-2"
                modal-class="warning"
                modal-id="app-confirmation-modal"
                @cancelled="cancelled"
                @confirmed="deleteContactPerson"
                @reload="getContactPerson"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import HelperMixin from '../../../../../common/Mixin/Global/HelperMixin'
import DefaultAction from '../../../../../core/components/datatable/DefaultAction'
import ContactPersonModal from './ContactPersonCreateEditModal'
import {
  CONTACTPERSON,
  CUSTOMER_CONTACTPERSON,
  CONTACTPERSON_DETAILS,
} from '../../../../Config/ApiUrl-CPB'
import { axiosDelete, axiosGet, urlGenerator } from '../../../../../common/Helper/AxiosHelper'

export default {
  name: 'Contact',
  components: {
    DefaultAction,
    ContactPersonModal,
  },
  props: {
    props: {},
  },
  mixins: [HelperMixin],
  CONTACTPERSON_DETAILS,
  data() {
    return {
      isModalActive: false,
      contactpersons: '',
      url: '',
      customerData: '',
      userType: 'customer',
      deleteUrl: CONTACTPERSON,
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
    axiosGet(CUSTOMER_CONTACTPERSON + '/' + this.props)
      .then((response) => {
        this.contactpersons = response.data
      })
      .catch((error) => {
        this.$toastr.e(error)
      })
  },

  methods: {
    getContactPerson() {
      axiosGet(CUSTOMER_CONTACTPERSON + '/' + this.props)
        .then((response) => {
          this.contactpersons = response.data
        })
        .catch((error) => {
          this.$toastr.e(error)
        })
    },
    fullContactPerson(contactperson) {
      return `${'Ph No.:'}
      ${
        contactperson.phonenumber
          ? contactperson.phonenumber + ' - Email: '
          : ''
      }
            ${contactperson.email ? contactperson.email + ' - Des.: ' : ''}
            ${contactperson.designation ? contactperson.designation + ' ' : ''}
            `
    },
    addContactPersonModal() {
      this.isModalActive = true
      this.url = ''
    },

    cancelled() {
      this.confirmationModalActive = false
      this.deleteUrl = ''
    },
    triggerAction(row, action) {
      if (action.type === 'edit') {
        // this.url = `${CONTACTPERSON}/${row.id}`
        // this.$toastr.e(row.id)
        // this.$toastr.e(`${CONTACTPERSON_DETAILS}${row.id}`)
        window.location.href = urlGenerator(`${CONTACTPERSON_DETAILS}${row.id}`)
        // this.$router.push(`${CONTACTPERSON_DETAILS}${row.id}`)
        // this.$router.go({
        //   name: 'CONTACTPERSON_DETAILS',
        //   params: { id: row.id },
        // })
        // this.isModalActive = true
        // this.status = row.status
      } else if (action.type === 'delete') {
        this.confirmationModalActive = true
        this.deleteUrl = `${CONTACTPERSON}/${row.id}`
      } else {
        this.getAction(row, action, active)
      }
    },
    deleteContactPerson() {
      axiosDelete(this.deleteUrl).then(({ data }) => {
        this.cancelled()
        this.toastAndReload(data.message, 'all-branches-table')
        this.getContactPerson()
      })
    },
  },
}
</script>
