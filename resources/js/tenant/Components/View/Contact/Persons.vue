<template>
  <div class="content-wrapper">
    <app-page-top-section :title="$t('persons')" icon="briefcase">
      <app-default-button
        @click="openModal()"
        v-if="this.$can('create_persons')"
        :title="$fieldTitle('add', 'person', true)"
      />
    </app-page-top-section>

    <app-table id="person-table" :options="options" @action="triggerActions" />

    <app-person-modal
      v-if="isModalActive"
      v-model="isModalActive"
      :selected-url="selectedUrl"
      @afterSubmitSuccess="filterInitiate"
      @close="isModalActive = false"
    />

    <app-confirmation-modal
      v-if="confirmationModalActive"
      icon="trash-2"
      modal-id="app-confirmation-modal"
      @confirmed="confirmed('person-table')"
      @cancelled="cancelled"
    />
  </div>
</template>

<script>
import HelperMixin from '../../../../common/Mixin/Global/HelperMixin'
import PersonMixin from '../../Mixins/PersonMixin'
import CoreLibrary from '../../../../core/helpers/CoreLibrary'
import {
  CONTACTPERSON,
  CONTACTPERSON_DETAILS,
} from '../../../Config/ApiUrl-CPB'
import { urlGenerator } from '../../../../common/Helper/AxiosHelper'

export default {
  name: 'Persons',
  mixins: [HelperMixin, PersonMixin],
  data() {
    return {
      isModalActive: false,
      selectedUrl: '',
      totalCustomer: '',
      urlGenerator,
      CONTACTPERSON_DETAILS,
    }
  },
  extends: CoreLibrary,
  methods: {
    triggerActions(row, action, active) {
      if (action.title === this.$t('edit')) {
        window.location.href = urlGenerator(`${CONTACTPERSON_DETAILS}${row.id}`)
        // this.selectedUrl = `${CONTACTPERSON}/${row.id}`
        // this.isModalActive = true
      } else {
        this.getAction(row, action, active)
      }
    },

    openModal() {
      this.isModalActive = true
      this.selectedUrl = ''
    },

    exportPersons() {
      window.location = CONTACTPERSON_EXPORT
    },
  },
}
</script>
