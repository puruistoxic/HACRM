import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import { EXPENSES } from "../../Config/ApiUrl-CP";
import { NOTE } from "../../Config/ApiUrl-CPB";
import { numberWithCurrencySymbol } from "../../Helper/Helper";
import { formatDateToLocal } from "../../../common/Helper/Support/DateTimeHelper";
import { textTruncate } from "../../../common/Helper/Support/TextHelper";
import { expense_area } from "../../../store/Tenant/Mixins/ExpenseAreaMixin";
import { mapGetters } from "vuex";

export default {
    mixins: [DatatableHelperMixin],
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
            options: {
                name: this.$t('note'),
                url: `/app/note/${this.props}`,
                // showCount: true,
                // showClearFilter: true,
                // showHeader: true,
                columns: [
                    {
                        title: this.$t('title'),
                        type: 'text',
                        key: 'title',
                    },
                    {
                        title: this.$t('description'),
                        type: 'custom-html',
                        key: 'description',
                        modifier: value => value ? `<p class="mb-0">${textTruncate(value, 30)}</p>` : '-'
                    },
                    {
                        title: this.$t('attachment'),
                        type: 'component',
                        key: 'attachments',
                        componentName: 'app-attachment'
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                actionType: "default",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-tenant-group-modal',
                        modalId: 'app-modal',
                        modifier: () => this.$can('update_note'),
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: NOTE,
                        name: 'delete',
                        modifier: row => this.$can('delete_note') && !parseInt(row.is_default)
                    }
                ],
                // filters: [
                //     {
                //         title: this.$t('expense_date'),
                //         type: "range-picker",
                //         key: "expense_date",
                //         option: ["today", "thisMonth", "last7Days", "thisYear"]
                //     },
                //     {
                //         title: this.$t('area_of_expense'),
                //         type: "multi-select-filter",
                //         key: "expense_area",
                //         option: [],
                //     },
                // ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                // search: true,
            },
        }
    },

}
