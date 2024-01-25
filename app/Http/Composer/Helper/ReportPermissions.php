<?php


namespace App\Http\Composer\Helper;


use App\Helpers\Core\Traits\InstanceCreator;

class ReportPermissions
{
    use InstanceCreator;

    public function permissions(): array
    {
        return [
            [
                'name' => __t('product_sales_report'),
                'url' => url('account/product-sales'),
                'permission' => authorize_any([
                    'view_product_sales_report',
                ]),
            ],
            [
                'name' => __t('custmer_group_2010_2019_report'),
                'url' => url('account/custmer-group-2010-2019'),
                'permission' => authorize_any([
                    'view_customer_group_2010_2019_report',
                ]),
            ],
            [
                'name' => __t('product_sales_and_units_report'),
                'url' => url('account/product-sales-and-units'),
                'permission' => authorize_any([
                    'view_product_sales_and_units_report',
                ]),
            ],
            [
                'name' => __t('year_on_year_july_report'),
                'url' => url('account/year-on-year-july'),
                'permission' => authorize_any([
                    'view_year_on_year_july_report',
                ]),
            ],
            [
                'name' => __t('visitors_and_sales_report'),
                'url' => url('account/visitors-and-sales'),
                'permission' => authorize_any([
                    'view_visitors_and_sales_report',
                ]),
            ],
            [
                'name' => __t('yearly_sales_2016_2023_report'),
                'url' => url('account/yearly-sales-2016-2023'),
                'permission' => authorize_any([
                    'view_yearly_sales_2016_2023_report',
                ]),
            ],
            [
                'name' => __t('monthly_debtors_report'),
                'url' => url('account/monthly-debtors'),
                'permission' => authorize_any([
                    'view_monthly_debtors_report',
                ]),
            ],
            [
                'name' => __t('sales_2022_2023_report'),
                'url' => url('account/sales-2022-2023'),
                'permission' => authorize_any([
                    'view_sales_2022_2023_report',
                ]),
            ],
            [
                'name' => __t('customer_group_2013_2023_report'),
                'url' => url('account/customer-group-2013-2023'),
                'permission' => authorize_any([
                    'view_customer_group_2013_2023_report',
                ]),
            ],
            [
                'name' => __t('country_wise_turnover_report'),
                'url' => url('account/country-wise-turnover'),
                'permission' => authorize_any([
                    'view_country_wise_turnover_report',
                ]),
            ],
            [
                'name' => __t('healthaid_shopify_report'),
                'url' => url('account/healthaid-shopify'),
                'permission' => authorize_any([
                    'view_healthaid_shopify_report',
                ]),
            ],
            [
                'name' => __t('healthaid_social_media_report'),
                'url' => url('account/healthaid-social-media'),
                'permission' => authorize_any([
                    'view_healthaid_social_media_report',
                ]),
            ],
            [
                'name' => __t('healthaid_sales_insight_report'),
                'url' => url('account/healthaid-sales-insight'),
                'permission' => authorize_any([
                    'view_healthaid_sales_insight_report',
                ]),
            ],
            [
                'name' => __t('healthaid_year_sales_report'),
                'url' => url('account/healthaid-year-sales'),
                'permission' => authorize_any([
                    'view_healthaid_year_sales_report',
                ]),
            ],
            // [
            //     'name' => __t('sales_insights_report'),
            //     'url' => url('account/sales-insights-report'),
            //     'permission' => authorize_any([
            //         'view_sales_insights_report',
            //     ]),
            // ],
            // [
            //     'name' => __t('hilton_report'),
            //     'url' => url('account/hilton-report'),
            //     'permission' => authorize_any([
            //         'view_hilton_report',
            //     ]),
            // ],
            // [
            //     'name' => __t('sales_insights_q_report'),
            //     'url' => url('account/sales-insights-q-report'),
            //     'permission' => authorize_any([
            //         'view_sales_insights_q_report',
            //     ]),
            // ],
            [
                'name' => __t('sales_report'),
                'url' => url('sales/report'),
                'permission' => authorize_any([
                    'view_sales_report',
                ]),
            ],
            [
                'name' => __t('cash_counter'),
                'url' => url('cash-counter/report'),
                'permission' => authorize_any([
                    'view_cash_counter_report',
                ]),
            ],
            [
                'name' => __t('sales_return_report'),
                'url' => url('sales/return/report'),
                'permission' => authorize_any([
                    'view_sales_return_report',
                ]),
            ],
            [
                'name' => __t('top_selling_products'),
                'url' => url('top-selling-products/report'),
                'permission' => authorize_any([
                    'view_top_selling_product_report',
                ]),
            ],
            [
                'name' => __t('lot_report'),
                'url' => route('support.lot.report.view', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_lot_report',
                ]),
            ],
            [
                'name' => __t('stock_report'),
                'url' => route('support.stock.report.view', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_stock_report',
                ]),
            ],
            [
                'name' => __t('warehouse_report'),
                'url' => route('support.branch_warehouse.report.view'),
                'permission' => authorize_any([
                    'view_branch_warehouse_report'
                ]),
            ],
            [
                'name' => __t('profit_loss'),
                'url' => url('profit-loss/report'),
                'permission' => authorize_any([
                    'view_profit_loss_report',
                ]),
            ],
            [
                'name' => __t('user_report'),
                'url' => url('user/report'),
                'permission' => authorize_any([
                    'view_user_report',
                ]),
            ],
            [
                'name' => __t('expense_report'),
                'url' => url('expense/report'),
                'permission' => authorize_any([
                    'view_expense_report',
                ]),
            ],
            [
                'name' => __t('supplier_report'),
                'url' => route('support.supplier.report.view', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_supplier_report'
                ]),
            ],
            [
                'name' => __t('customer_report'),
                'url' => route('support.customer.report.view', optional(tenant())->is_single ? '' : ['tenant_parameter' => tenant()->short_name]),
                'permission' => authorize_any([
                    'view_customer_report',
                ]),
            ],

        ];
    }

    public function canVisit(): bool
    {
        return authorize_any([
            'view_hilton_report', 'view_sales_insights_report', 'view_sales_insights_q_report', 'view_sales_report', 'view_cash_counter_report', 'view_top_selling_product', 'view_lot_report', 'view_stock_report',
            'view_branch_warehouse_report', 'view_user_report', 'view_expense_report', 'view_supplier_report', 'view_customer_report',
            'view_sales_return_report', 'view_product_sales_report', 'view_customer_group_2010_2019_report', 'view_product_sales_and_units_report',
            'view_year_on_year_july_report', 'view_visitors_and_sales_report', 'view_yearly_sales_2016_2023_report', 'view_monthly_debtors_report', 'view_sales_2022_2023_report', 'view_customer_group_2013_2023_report', 'view_healthaid_shopify_report', 'view_country_wise_turnover_report', 'view_healthaid_social_media_report', 'view_healthaid_sales_insight_report', 'view_healthaid_year_sales_report'
        ]);
    }
}
