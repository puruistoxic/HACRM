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
      Notes List
    </h5>
    <div class="col-lg-12" style="margin-top: 20px">
      <!-- <div v-for="note in notes" :key="note.id" class="row">
        <div class="col-md-3">
          <div class="media mb-4 mb-xl-0">
            <div class="media-body align-self-center ml-3">
              <p class="mb-0">
                <span style="font-size: 12px; margin-right: 10px">Title:</span
                >{{ note.title }}
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="media mb-4 mb-xl-0">
            <div class="media-body align-self-center ml-3">
              <p class="mb-0">
                <span style="font-size: 12px; margin-right: 10px"
                  >Description:</span
                >
                {{ note.description }}
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="d-flex justify-content-center">
            <default-action
              :key="`default`"
              :actions="actions"
              :row-data="note"
              :unique-key="note.id"
              @action="triggerAction"
            />
          </div>
        </div>
      </div> -->
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="note in notes" :key="note.id">
            <td>{{ note.title }}</td>
            <td>{{ note.description }}</td>
            <td>
              <default-action
                :key="`default`"
                :actions="actions"
                :row-data="note"
                :unique-key="note.id"
                @action="triggerAction"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="col-md-3">
      <div class="d-flex justify-content-center">
        <app-contact-person-notes-modal
          v-if="isModalActive"
          v-model="isModalActive"
          :user-id="contactPersonId"
          :selectedUrl="url"
          :user-type="userType"
          @close="isModalActive = false"
          @reload="fetchNotes"
        />
        <app-confirmation-modal
          v-if="confirmationModalActive"
          :firstButtonName="$t('yes')"
          icon="trash-2"
          modal-class="warning"
          modal-id="app-confirmation-modal"
          @cancelled="cancelled"
          @confirmed="deleteNote"
          @reload="fetchNotes"
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
import { NOTE } from '../../../../../Config/ApiUrl-CPB'
// import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import NoteEditModal from './NoteCreateEditModal'
import DefaultAction from '../../../../../../core/components/datatable/DefaultAction'
import HelperMixin from '../../../../../../common/Mixin/Global/HelperMixin'

export default {
  components: {
    DefaultAction,
    NoteEditModal,
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
      notes: [],
      deleteUrl: NOTE,
      isModalActive: false,
      notes: '',
      url: '',
      noteData: '',
      userType: 'note',
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
    this.fetchNotes()
  },
  created() {
    axiosGet(`app/note/${this.contactPersonId}`)
      .then((response) => {
        this.notes = response
      })
      .catch((error) => {
        this.$toastr.e(error)
      })
  },
  methods: {
    fetchNotes() {
      axiosGet(`app/note/${this.contactPersonId}`)
        .then(({ data }) => {
          this.notes = data
        })
        .catch((error) => console.error('Error fetching notes:', error))
    },
    editNoteModal() {
      this.isModalActive = true
      this.url = ''
    },
    triggerAction(row, action) {
      if (action.type === 'edit') {
        this.url = `${NOTE}/${row.id}`
        this.isModalActive = true
        this.status = row.status
      } else if (action.type === 'delete') {
        this.confirmationModalActive = true
        this.deleteUrl = `${NOTE}/${row.id}`
      } else {
        this.getAction(row, action, active)
      }
    },
    deleteNote() {
      axiosDelete(this.deleteUrl).then(({ data }) => {
        this.cancelled()
        // location.reload()
        this.toastAndReload(data.message, 'all-branches-table')
        // this.fetchNotes()
      })
    },
  },
}
</script>

<style scoped>
/* Add your component-specific styles here */
</style>
