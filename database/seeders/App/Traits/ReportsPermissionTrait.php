<?php

namespace Database\Seeders\App\Traits;

trait ReportsPermissionTrait
{
    public function reports($type, $group = null): array
    {
        return [
            [
                'name' => 'view_product_sales_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_customer_group_2010_2019_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_product_sales_and_units_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_year_on_year_july_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_visitors_and_sales_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_yearly_sales_2016_2023_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_monthly_debtors_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_sales_2022_2023_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_customer_group_2013_2023_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_country_wise_turnover_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_healthaid_shopify_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_healthaid_social_media_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_healthaid_sales_insight_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_healthaid_year_sales_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            // [
            //     'name' => 'view_sales_insights_report',
            //     'type_id' => $type,
            //     'group_name' => $group ?? 'report'
            // ],
            // [
            //     'name' => 'view_hilton_report',
            //     'type_id' => $type,
            //     'group_name' => $group ?? 'report'
            // ],
            // [
            //     'name' => 'view_sales_report',
            //     'type_id' => $type,
            //     'group_name' => $group ?? 'report'
            // ],
            [
                'name' => 'view_cash_counter_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_sales_return_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_top_selling_product_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_lot_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_stock_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_branch_warehouse_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_profit_loss_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_user_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_expense_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_supplier_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'view_customer_report',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'export_sales',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'export_cash_counter',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'export_sales_return',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'export_users',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'sales_report_users',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'purchase_report_users',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],
            [
                'name' => 'sales_return_report_users',
                'type_id' => $type,
                'group_name' => $group ?? 'report'
            ],

        ];
    }
}
