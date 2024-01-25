<template>
  <div>
    <app-page-top-section :title="$t('notes')" icon="briefcase">
      <app-default-button
        @click="openModal()"
        v-if="this.$can('create_note')"
        :title="$fieldTitle('add', 'note', true)"
      />
    </app-page-top-section>

    <app-table
      :id="table_id"
      v-if="options.url"
      :options="options"
      @action="triggerActions"
      :customerIdCopy="customerIdCopy"
    />

    <div class="col-md-3">
      <div class="d-flex justify-content-center">
        <app-contact-person-notes-modal
          v-if="isModalActive"
          v-model="isModalActive"
          :selectedUrl="selectedUrl"
          :customerIdCopy="customerIdCopy"
          @close="isModalActive = false"
        />
        <app-confirmation-modal
          v-if="confirmationModalActive"
          icon="trash-2"
          modal-id="app-confirmation-modal"
          @confirmed="confirmed('note-table')"
          @cancelled="cancelled"
        />
      </div>
    </div>
  </div>
</template>

<script>
import HelperMixin from '../../../../../../common/Mixin/Global/HelperMixin'
import { NOTE } from '../../../../../Config/ApiUrl-CPB'
import NoteMixin from '../../../../Mixins/NoteMixin'

export default {
  name: 'Notes',
  mixins: [HelperMixin, NoteMixin],
  props: {
    props: {
      customerId: {},
    },
    customerId: {},
  },
  data() {
    return {
      table_id: 'note-table',
      isModalActive: false,
      selectedUrl: '',
      customerId: this.props,
      customProps: null,
    }
  },
  created() {
    // Assign the value of this.props to customProps
    this.customerIdCopy = this.props
  },
  methods: {
    triggerActions(row, action, active) {
      if (action.title === this.$t('edit')) {
        this.selectedUrl = `${NOTE}/${row.id}`
        console.log(this.customerIdCopy)
        this.isModalActive = true
      } else {
        this.getAction(row, action, active)
      }
    },
    openModal() {
      this.isModalActive = true
      this.selectedUrl = ''

      this.$refs.noteCreateEditModal.$emit(
        'set-customer-id',
        this.props.customerId
      )
    },
  },
}
</script>
<style scoped>
.text-muted {
  display: none !important;
}
#dropdownMenuButton {
  display: none !important;
}

.form-group-with-search,
.single-search-wrapper {
  display: none !important;
}
</style>
