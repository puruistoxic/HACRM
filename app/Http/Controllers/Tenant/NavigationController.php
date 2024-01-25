<?php

namespace App\Http\Controllers\Tenant;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Models\Tenant\Branch\Branch;
use App\Models\Tenant\InvoiceTemplate\InvoiceTemplate;
use Illuminate\Contracts\Foundation\Application;

class NavigationController extends Controller
{
    public function dashboard()
    {
        if (authorize_any(['dashboard_tenant'])) {
            return view('tenant.dashboard');
        }
        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function settings(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Application
    {
        $permission = [
            'general' => authorize_any(['view_settings', 'update_settings']),
            'notification_template' => authorize_any(['view_notification_templates', 'create_notification_templates']),
            'notification' => authorize_any(['view_notification_settings', 'update_notification_settings']),
            'update' => authorize_any(['check_for_updates', 'update_app'])
        ];

        $authorized = array_reduce(array_values($permission), function ($sum, $carry) {
            return $sum + $carry;
        });

        if ($authorized)
            return view('tenant.settings.index', ['permissions' => $permission]);

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function posBookingSetting()
    {
        $permission = [
            'general' => authorize_any(['view_settings', 'update_settings']),
            'notification_template' => authorize_any(['view_notification_templates', 'create_notification_templates']),
            'notification' => authorize_any(['view_notification_settings', 'update_notification_settings']),
            'update' => authorize_any(['check_for_updates', 'update_app'])
        ];

        $authorized = array_reduce(array_values($permission), function ($sum, $carry) {
            return $sum + $carry;
        });

        if ($authorized)
            return view('tenant.settings.pos-booking', ['permissions' => $permission]);

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function posSetting()
    {
        $permission = [
            'general' => authorize_any(['view_settings', 'update_settings']),
            'notification_template' => authorize_any(['view_notification_templates', 'create_notification_templates']),
            'notification' => authorize_any(['view_notification_settings', 'update_notification_settings']),
            'update' => authorize_any(['check_for_updates', 'update_app'])
        ];

        $authorized = array_reduce(array_values($permission), function ($sum, $carry) {
            return $sum + $carry;
        });

        if ($authorized)
            return view('tenant.settings.pos-booking', ['permissions' => $permission]);

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function notifications()
    {
        return view('tenant.notification.notifications');
    }

    public function profile()
    {
        return view('tenant.user.profile');
    }

    public function users()
    {
        if (authorize_any(['view_users', 'view_roles'])) {
            return view('tenant.user.user-roles');
        }
        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function customers()
    {
        if (authorize_any(['view_customers'])) {
            return view('tenant.contact.customer.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function persons()
    {
        if (authorize_any(['view_persons'])) {
            return view('tenant.contact.person.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function lot_report()
    {
        if (authorize_any(['view_lot_report'])) {
            return view('tenant.report.lot.lot_report');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function stock_report()
    {
        if (authorize_any(['view_stock_report'])) {
            return view('tenant.report.stock.stock_report');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function supplier_report()
    {
        if (authorize_any(['view_supplier_report'])) { //not defined yet
            return view('tenant.report.supplier.supplier_report');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function customer_report()
    {
        if (authorize_any(['view_customer_report'])) { //not define yet
            return view('tenant.report.customer.customer_report');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function hilton_report()
    {
        if (authorize_any(['view_hilton_report'])) { //not define yet
            return view('tenant.report.hilton_report.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }


    public function sales_insights_report()
    {
        if (authorize_any(['view_sales_insights_report'])) { //not define yet
            return view('tenant.report.sales_insights_report.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }
    public function sales_insights_q_report()
    {
        if (authorize_any(['view_sales_insights_q_report'])) { //not define yet
            return view('tenant.report.sales_insights_q_report.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }



    // healthaid reports


    //1
    public function product_sales_report()
    {
        if (authorize_any(['view_product_sales_report'])) { //not define yet
            return view('tenant.report.product_sales.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //2
    public function customer_group_2010_2019_report()
    {
        if (authorize_any(['view_customer_group_2010_2019_report'])) { //not define yet
            return view('tenant.report.customer_group_one.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //3
    public function product_sales_and_units_report()
    {
        if (authorize_any(['view_product_sales_and_units_report'])) { //not define yet
            return view('tenant.report.product_sales_and_units.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //4
    public function year_on_year_july_report()
    {
        if (authorize_any(['view_year_on_year_july_report'])) { //not define yet
            return view('tenant.report.year_on_year_july.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //5
    public function visitors_and_sales_report()
    {
        if (authorize_any(['view_visitors_and_sales_report'])) { //not define yet
            return view('tenant.report.visitors_and_sales.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //6
    public function yearly_sales_2016_2023_report()
    {
        if (authorize_any(['view_yearly_sales_2016_2023_report'])) { //not define yet
            return view('tenant.report.yearly_sales_2016_2023.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //7
    public function monthly_debtors_report()
    {
        if (authorize_any(['view_monthly_debtors_report'])) { //not define yet
            return view('tenant.report.monthly_debtors.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //8
    public function sales_2022_2023_report()
    {
        if (authorize_any(['view_sales_2022_2023_report'])) { //not define yet
            return view('tenant.report.sales_2022_2023.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //9
    public function customer_group_2013_2023_report()
    {
        if (authorize_any(['view_customer_group_2013_2023_report'])) { //not define yet
            return view('tenant.report.customer_group_2013_2023.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //10
    public function country_wise_turnover_report()
    {
        if (authorize_any(['view_country_wise_turnover_report'])) { //not define yet
            return view('tenant.report.country_wise_turnover.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //11
    public function healthaid_year_sales_report()
    {
        if (authorize_any(['view_healthaid_year_sales_report'])) { //not define yet
            return view('tenant.report.healthaid_year_sales.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //12
    public function healthaid_shopify_report()
    {
        if (authorize_any(['view_healthaid_shopify_report'])) { //not define yet
            return view('tenant.report.healthaid_shopify.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //13
    public function healthaid_social_media_report()
    {
        if (authorize_any(['view_healthaid_social_media_report'])) { //not define yet
            return view('tenant.report.healthaid_social_media.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //14
    public function healthaid_sales_insight_report()
    {
        if (authorize_any(['view_healthaid_sales_insight_report'])) { //not define yet
            return view('tenant.report.healthaid_sales_insight.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }



    public function customerDetails($customer_id)
    {
        if (authorize_any(['details_customer'])) {
            return view('tenant.contact.customer.customer_details', ['customer_id' => $customer_id]);
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function contactPersonDetails($customer_id)
    {
        if (authorize_any(['contactperson_view'])) { //not define yet
            return view('tenant.contact.person.contactperson_details', ['customer_id' => $customer_id]);
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }


    public function customersGroup()
    {
        if (authorize_any(['view_customer_groups'])) {
            return view('tenant.contact.group.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function customersImport()
    {
        if (authorize_any(['view_all_customers'])) {
            return view('tenant.contact.customer.import');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function supplier()
    {
        if (authorize_any(['view_suppliers'])) {
            return view('tenant.contact.supplier.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function supplierDetails($supplierId)
    {
        if (authorize_any(['view_suppliers', 'create_suppliers', 'update_suppliers', 'delete_suppliers'])) {
            return view('tenant.contact.supplier.supplier_details', compact('supplierId'));
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function supplierImport()
    {
        if (authorize_any(['view_supplier', 'create_supplier', 'update_supplier', 'delete_supplier'])) {
            return view('tenant.contact.supplier.import-supplier');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function branch()
    {
        if (authorize_any(['view_all_branches'])) {
            return view('tenant.branch.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function branchAndWarehouse()
    {
        if (authorize_any(['view_all_branches_and_warehouses'])) {
            return view('tenant.branch.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function invoiceTemplate()
    {
        if (authorize_any(['view_all_cash_register'])) {
            return view('tenant.invoice_template.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function coupon()
    {
        if (authorize_any(['view_all_coupons'])) {
            return view('tenant.coupon.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }


    public function taxManagements()
    {
        if (authorize_any(['view_tax_management'])) {
            return view('tenant.settings.tax_management.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }


    public function selectableBranch()
    {
        return Branch::all(['id', 'name']);
    }

    public function selectableInvoiceTemplate()
    {
        return InvoiceTemplate::all(['id', 'name']);
    }

    public function selectableSalesPerson()
    {
        return User::all(['id', 'first_name', 'last_name']);
    }

    public function category()
    {
        if (authorize_any(['view_categories'])) {
            return view('tenant.product.category.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function categoryImport()
    {
        if (authorize_any(['view_category'])) {
            return view('tenant.product.category.import');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function unit()
    {
        if (authorize_any(['view_unit'])) {
            return view('tenant.product.unit.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function attribute()
    {
        if (authorize_any(['view_attributes'])) {
            return view('tenant.product.attribute.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function attributeImport()
    {
        if (authorize_any(['view_attributes'])) {
            return view('tenant.product.attribute.import');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function group()
    {
        if (authorize_any(['view_groups'])) {
            return view('tenant.product.group.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function productGroupImport()
    {
        if (authorize_any(['view_group'])) {
            return view('tenant.product.group.import');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function productImport()
    {
        if (authorize_any(['import_products'])) {
            return view('tenant.product.import');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function brand()
    {
        if (authorize_any(['view_brands'])) {
            return view('tenant.product.brand.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function importBrand()
    {
        if (authorize_any(['view_brand'])) {
            return view('tenant.product.brand.import');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function product()
    {
        if (authorize_any(['view_products'])) {
            return view('tenant.product.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function addProduct()
    {
        if (authorize_any(['create_products'])) {
            return view('tenant.product.create');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function editProduct($product_id)
    {
        if (authorize_any(['update_products'])) {
            return view('tenant.product.edit', ['product_id' => $product_id]);
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function productDetails($product)
    {
        if (authorize_any(['products_manage'])) {
            return view('tenant.product.details.product_details', ['product_id' => $product]);
        }
        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function salesView()
    {
        if (authorize_any(['view_sales'])) {
            return view('tenant.pos.sales_view.index');
        }
        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function returns()
    {
        if (authorize_any(['view_order_return'])) {
            return view('tenant.pos.returns.index');
        }
        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function invoice()
    {
        if (authorize_any(['list_invoice'])) {
            return view('tenant.pos.invoice.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function cashCounter()
    {
        if (authorize_any(['view_cash_counter'])) {
            return view('tenant.pos.cash_counter.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function expense()
    {
        if (authorize_any(['view_expenses'])) {
            return view('tenant.expense.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function areaOfExpense()
    {
        if (authorize_any(['view_expense_areas'])) {
            return view('tenant.expense.area_of_expense.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function discounts()
    {
        if (authorize_any(['view_discount'])) {
            return view('tenant.settings.discount.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function counters()
    {
        if (authorize_any(['view_counter'])) {
            return view('tenant.settings.counter.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function reports()
    {
        if (authorize_any(['view_reports'])) {
            return view('tenant.reports.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function subCategory()
    {
        if (authorize_any(['view_sub_category'])) {
            return view('tenant.product.sub_category.index');
        }
        throw new GeneralException(trans('default.action_not_allowed'));
    }

    //inventory
    public function stockView()
    {
        if (authorize_any(['view_stock', 'import_stock'])) {
            return view('tenant.inventory.stock');
        }
        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function lotView()
    {
        if (authorize_any(['lot_manage', 'view_lot', 'create_lot'])) {
            return view('tenant.inventory.manage_lot');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function stockOverView($variant_id)
    {
        return view('tenant.inventory.stock-overview', ['variant_id' => $variant_id]);
    }

    public function adjustmentView()
    {
        if (authorize_any(['view_stock_adjustments'])) {
            return view('tenant.inventory.stock_adjustment');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function importStockView()
    {
        if (authorize_any(['import_stock'])) {
            return view('tenant.inventory.import_stock');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function printBarcodeView()
    {
        if (authorize_any(['generate_barcode_inventory'])) {
            return view('tenant.inventory.print_barcode');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function printQRcodeView()
    {
        if (authorize_any(['generate_qrcode'])) {
            return view('tenant.inventory.print_qrcode.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function internalTransferView()
    {
        if (authorize_any(['view_internal_transfers', 'create_internal_transfers', 'update_internal_transfers', 'delete_internal_transfers'])) {
            return view('tenant.inventory.internal_transfer');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function addLot()
    {
        if (authorize_any(['create_lot'])) {
            return view('tenant.inventory.lot.create');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function editLot($lot_id)
    {
        if (authorize_any(['update_lot'])) {
            return view('tenant.inventory.lot.edit', ['lot_id' => $lot_id]);
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }


    public function salesReportView()
    {
        if (authorize_any(['view_sales_report'])) {
            return view('tenant.report.sales.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function cashCounterReportView()
    {
        if (authorize_any(['view_cash_counter_report'])) {
            return view('tenant.report.cash_counter.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function salesReturnReportView()
    {
        if (authorize_any(['view_sales_return_report'])) {
            return view('tenant.report.return.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function topSellingProducts()
    {
        if (authorize_any(['view_top_selling_product_report'])) {
            return view('tenant.report.topSelling.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function expenseReport()
    {
        if (authorize_any(['view_expense_report'])) {
            return view('tenant.report.expense.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function userReport()
    {
        if (authorize_any(['view_user_report'])) {
            return view('tenant.report.user.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function userReportDetails(User $user)
    {
        if (authorize_any(['view_user_report'])) {
            return view('tenant.report.user.user_details_report', compact('user'));
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function branchAndWarehouseReport()
    {
        if (authorize_any(['view_branch_warehouse_report'])) {
            return view('tenant.report.branch_warehouse.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }

    public function profitLossReport()
    {
        if (authorize_any(['view_profit_loss_report'])) {
            return view('tenant.report.profit_loss.index');
        }

        throw new GeneralException(trans('default.action_not_allowed'));
    }
}
