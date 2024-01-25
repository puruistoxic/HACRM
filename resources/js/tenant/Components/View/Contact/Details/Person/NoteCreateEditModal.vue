<template>
  <modal
    id="note-create-edit-modal"
    v-model="showModal"
    :loading="loading"
    :preloader="preloader"
    :title="generateModalTitle('note')"
    @submit="submitDataTest"
  >
    <form
      ref="form"
      :data-url="selectedUrl ? selectedUrl : NOTE"
      enctype="multipart/form-data"
    >
      <app-form-group
        v-model="formData.title"
        :label="$t('title')"
        :placeholder="$t('e_title')"
        :required="true"
        :error-message="$errorMessage(errors, 'title')"
      />

      <app-form-group
        type="textarea"
        v-model="formData.description"
        :text-area-rows="8"
        :label="$t('description')"
        :placeholder="$t('e_description')"
      />

      <div class="form-row">
        <div class="margin-top-10 text-warning d-flex align-item-center">
          <app-icon name="alert-circle" class="mr-2" />
          <p style="margin-top: 2px">
            {{
              $t('clicking_remove_file_will_remove_the_attachment_permanently')
            }}
          </p>
        </div>
        <div class="form-group col-12">
          <label>{{ $t('attachments') }}</label>
          <app-input
            v-model="formData.attachments"
            @file-removed="handleDropzoneFileRemove"
            type="dropzone"
            v-if="showDropzone"
          />
        </div>

        <small :class="{ 'text-danger': attachmentErrorLength }">
          {{ $t('allowed_file_types_xls_csv_excluded') }}
        </small>
      </div>
    </form>
  </modal>
</template>
<script>
import FormHelperMixins from '../../../../../../common/Mixin/Global/FormHelperMixins'
import ModalMixin from '../../../../../../common/Mixin/Global/ModalMixin'
import {
  axiosGet,
  urlGenerator,
  axiosDelete
} from '../../../../../../common/Helper/AxiosHelper'
import {
  EXPENSES,
  SELECTABLE_BRANCHES_OR_WAREHOUSES,
  SELECTABLE_EXPENSE_AREA,
  EXPENSE_ATTACHMENT_DELETE,
} from '../../../../../Config/ApiUrl-CP'
import { NOTE } from '../../../../../Config/ApiUrl-CPB'
import {
  formDataAssigner,
  getCurrencySymbol,
} from '../../../../../Helper/Helper'
import { mapGetters } from 'vuex'
import { formatDateForServer } from '../../../../../../common/Helper/Support/DateTimeHelper'

export default {
  name: 'NoteCreateEditModal',
  mixins: [FormHelperMixins, ModalMixin],
  mounted() {
    this.formData.branch_or_warehouse_id = this.getBranchOrWarehouseId
    this.formData.expense_date = new Date()
    this.branchOrWarehouseInputKey++
  },
  props: {
    props: {
      customerId: {},
    },
    customerIdCopy: {
      type: [String, Number], // Adjust the type based on your actual data type
      default: null,
    },
  },
  data() {
    return {
      showNote: true,
      expenseAttachments: [],
      branchOrWarehouseInputKey: 0,
      showDropzone: true,
      NOTE,
      formData: {
        title: '',
        description: '',
        attachments: [],
        contact_person_id: '',
        user_id: '',
      },
      branchesOrWarehousesOptions: {
        url: urlGenerator(SELECTABLE_BRANCHES_OR_WAREHOUSES),
        query_name: 'search_by_name',
        per_page: 10,
        loader: 'app-pre-loader', // by default 'app-overlay-loader'
        prefetch: false,
        modifire: ({ id, name: value }) => ({ id, value }),
      },
      EXPENSES,
      SELECTABLE_EXPENSE_AREA,
      getCurrencySymbol,
    }
  },
  mounted() {
    console.log(this.formData.title)
    console.log(this.formData.description)
    this.getUserInformationId()
    this.$on('set-customer-id', (customerId) => {
      // Update customerId when the event is received
      this.formData.contact_person_id = customerId
    })
    console.log(this.formData.contact_person_id)

  },
  methods: {
    handleDropzoneFileRemove(file) {
      const fileToRemove = this.expenseAttachments.find(
        (attachment) => attachment.path === file.dataURL
      )
      if (!fileToRemove) return
      const { id: fileToRemoveId } = fileToRemove
      axiosDelete(`app/note-attachment/delete/` + fileToRemoveId)
        .then((res) => this.$toastr.s('', res.data.message))
        .catch((err) => this.$toastr.e('', err.message))
    },
    submitDataTest() {
      this.loading = true
      this.message = ''
      this.errors = {}
      this.showDropzone = false
      // removing the dropzone from the template immediately on save button click (the contents get removed if this isn't done)

      try {
        this.formData.attachments = this.formData.attachments.filter(
          (attachment) => 'upload' in attachment
        )
      } catch (e) {
        this.$toastr.e(e)
        this.formData.attachments = null;
      }

      this.formData.contact_person_id = this.customerIdCopy;

      if (this.formData.branch_or_warehouse_id === null)
        this.formData.branch_or_warehouse_id = ''

      let formData = formDataAssigner(new FormData(), this.formData)

      if (this.selectedUrl) formData.append('_method', 'patch')
  
      if (this.formData.attachments.length)
        this.formData.attachments.forEach((attachment) => {
          attachment instanceof File
            ? formData.append(`attachments[]`, attachment)
            : formData.append(`dont_delete[]`, attachment.dataURL)
        })

      return this.submitFromFixin(
        'post',
        this.selectedUrl ? this.selectedUrl : NOTE,
        formData
      )
    },
    afterSuccess({ data }) {
      $('#note-create-edit-modal').modal('hide')
      this.$emit('input', false)
      this.toastAndReload(data.message, 'note-table')
    },
    afterSuccessFromGetEditData({ data }) {
      this.preloader = false
      // this.formData.expense_date = data.expense_date
      //   ? new Date(data.expense_date)
      //   : ''
      this.formData = data
      this.expenseAttachments = [...this.formData.attachments]

      //show attachment in dropzone
      this.formData.attachments = _.map(this.formData.attachments, 'path')
    },
    getUserInformationId() {
      axiosGet(`admin/authenticate-user`)
        .then(({ data }) => {
          // Assuming `data` is an object with an `id` property
          const userId = data.id

          // Update formData with the user ID
          this.formData.user_id = userId

          // Now, you can proceed to save the form
        })
        .catch((error) => this.$toastr.e(error))
    },
  },
  computed: {
    // ...mapGetters(['getBranchOrWarehouseId']),
    attachmentErrorLength() {
      return (
        Object.keys(this.errors).filter((item) => {
          return item.includes('attachments')
        }).length > 0
      )
    },
  },
}
</script>
