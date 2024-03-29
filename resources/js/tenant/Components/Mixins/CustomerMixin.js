import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import { CUSTOMERS, SELECTABLE_CUSTOMER_GROUP } from "../../Config/ApiUrl-CPB";
import { ADDRESS_AREA, ADDRESS_CITY } from "../../Config/ApiUrl-CP";
import { axiosGet } from "../../../common/Helper/AxiosHelper";
import { ucWords } from "../../../common/Helper/Support/TextHelper";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: CUSTOMERS,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                customerGroup: null,
                columns: [
                    {
                        title: this.$t('image'),
                        type: 'component',
                        key: 'profile_picture',
                        componentName: 'app-customer-image'
                    },
                    {
                        title: this.$t('name'),
                        type: 'component',
                        key: 'full_name',
                        componentName: 'app-customer-action'

                    },
                    {
                        title: this.$t('account_status'),
                        type: 'custom-html',
                        key: 'status_logs',
                        modifier: (value) => {
                            const statusLogs = value;
                            if (Array.isArray(statusLogs) && statusLogs.length > 0) {
                                const latestStatus = statusLogs[0];

                                let colorClass = '';
                                let textColor = '';

                                switch (latestStatus.status) {
                                    case 'LOST':
                                        colorClass = '#ff0000'; // Red color for LOST
                                        textColor = '#ffffff';
                                        break;
                                    case 'SUSP':
                                        colorClass = '#BFBFBF'; // Blue color for ACA
                                        textColor = '#000';
                                        break;
                                    case 'COOL':
                                        colorClass = '#00B0F0'; // Orange color for WARM
                                        textColor = '#000';
                                        break;
                                    case 'WARM':
                                        colorClass = '#FFE598'; // Orange color for WARM
                                        textColor = '#000';
                                        break;
                                    case 'HOT':
                                        colorClass = '#FFC000'; // Orange color for WARM
                                        textColor = '#000';
                                        break;
                                    case 'ACC':
                                        colorClass = '#C5E0B3'; // Orange color for WARM
                                        textColor = '#000';
                                        break;
                                    default:
                                        colorClass = '#ffffff'; // Default color for other statuses
                                }

                                return `<small class="text-capitalize px-3 py-1" style="border-radius: 8rem; width: 5rem;background: ${colorClass};color: ${textColor}">${latestStatus.status}</small>`;
                            }

                            return '-';
                        },
                    },
                    {
                        title: this.$t('customer_group'),
                        type: 'custom-html',
                        key: 'customer_group',
                        modifier: (value) => value.name ? `<small class="text-capitalize bg-success px-3 py-1 text-white" style="border-radius: 8rem; width: 5rem;">${value.name}</small>` : '-',
                    },
                    {
                        title: this.$t('email_s'),
                        type: 'component',
                        key: 'emails',
                        componentName: 'app-customer-contact'
                    },
                    {
                        title: this.$t('phone_number_s'),
                        type: 'component',
                        key: 'phone_numbers',
                        componentName: 'app-customer-contact'
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('quick_edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-tenant-group-modal',
                        modalId: 'app-modal',
                        modifier: () => this.$can('update_customers'),
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: CUSTOMERS,
                        name: 'delete',
                        modifier: (row) => this.$can('delete_customers') && +row.id !== 1

                    }
                ],
                filters: [
                    {
                        title: ucWords(this.$t('customer_group')),
                        type: "checkbox",
                        key: "customer_groups",
                        option: [],

                    },
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                search: true,
            },
        }
    },
    created() {
        this.filterInitiate();
    },
    methods: {

        filterInitiate() {
            this.customerGroups();
            this.addressArea();
            this.addressCity();
        },

        customerGroups() {
            axiosGet(SELECTABLE_CUSTOMER_GROUP).then((response) => {

                let name = this.options.filters.find(element => element.key === 'customer_groups');
                // this.$toastr.e(name);
                if (name) name.option = response.data.map(name => {
                    return {
                        id: name.id,
                        value: name.name
                    }
                });
                // console.log(name);
            }).catch((error) => {
                this.$toastr.e(error);
            });
        },

        addressArea() {
            axiosGet(ADDRESS_AREA).then((response) => {

                let name = this.options.filters.find(element => element.key === 'address_area');
                if (name) name.option = response.data.map(name => {
                    return {
                        id: name.area,
                        name: name.area
                    }
                });
            }).catch((error) => {
                this.$toastr.e(error);
            });
        },

        addressCity() {
            axiosGet(ADDRESS_CITY).then((response) => {

                let name = this.options.filters.find(element => element.key === 'address_city');
                if (name) name.option = response.data.map(name => {
                    return {
                        id: name.city,
                        name: name.city
                    }
                });
            }).catch((error) => {
                this.$toastr.e(error);
            });
        }
    }
}
