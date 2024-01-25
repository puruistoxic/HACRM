<?php

namespace App\Http\Controllers\Tenant\Contacts;

use Illuminate\Http\Request;
use App\Filters\Tenant\ContactPersonFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\ContactPersonRequest;
use App\Models\Tenant\Customer\ContactPerson;
use App\Models\Tenant\Customer\StatusLogs;
use App\Services\Tenant\Contact\CustomerContactPersonService;
use App\Services\Tenant\Contact\SupplierContactPersonService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;
use App\Models\Tenant\Customer\Customer;
use Illuminate\Http\JsonResponse;

class ContactPersonController extends Controller
{
    public SupplierContactPersonService $supplierContactPersonService;

    public function __construct(CustomerContactPersonService $service, ContactPersonFilter $filter, SupplierContactPersonService $supplierContactPersonService)
    {
        $this->service = $service;
        $this->filter = $filter;
        $this->supplierContactPersonService = $supplierContactPersonService;
    }

    public function index($type): LengthAwarePaginator
    {
        //
        return $this->service
            ->select('id', 'customer_group_id', 'customer_id', 'supplier_id', 'name', 'phonenumber', 'email', 'designation', 'city', 'country_id', 'zip_code', 'area', 'details', 'state',  'tenant_id')
            ->filters($this->filter)
            ->where($type . "_id", '!=', null)
            ->with($type)
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    // 'country_id',
    public function store(ContactPersonRequest $request)
    {

        $this->service
            ->save(
                $request->only(
                    'customer_group_id',
                    'customer_id',
                    'supplier_id',
                    'name',
                    'phonenumber',
                    'email',
                    'designation',
                    'city',
                    'country_id',
                    'zip_code',
                    'area',
                    'details',
                    'state'
                )
            );

        return created_responses('contact_details');
    }
    //'country_id',

    public function show($type, ContactPerson $contactperson): ContactPerson
    {
        return $contactperson;
    }


    public function update($type, ContactPersonRequest $request, ContactPerson $contactperson)
    {

        // $this->service
        //     ->setModel($contactperson)
        //     ->save($request->only(
        //         'customer_group_id',
        //         'customer_id',
        //         'supplier_id',
        //         'name',
        //         'phonenumber',
        //         'email',
        //         'designation',
        //         'city',
        //         'country_id',
        //         'zip_code',
        //         'area',
        //         'details',
        //         'state'
        //     ));

        $data = $request->only(
            'customer_group_id',
            'customer_id',
            'supplier_id',
            'name',
            'phonenumber',
            'email',
            'designation',
            'city',
            'country_id',
            'zip_code',
            'area',
            'details',
            'state',
            'status'
        );

        $existingStatusLogs = StatusLogs::where('contact_person_id', $contactperson->id)->get();

        // Mark existing records as deleted
        foreach ($existingStatusLogs as $existingStatusLog) {
            $existingStatusLog->update(['is_deleted' => true]);
        }

        $this->savePersonStatus($contactperson, $data['status']);

        $this->service
            ->setModel($contactperson)
            ->save($data);

        return updated_responses('contactperson', ['contactperson' => $contactperson]);
    }

    protected function savePersonStatus(ContactPerson $contactperson, $personStatus)
    {
        // Save to the 'status_logs' table
        StatusLogs::create([
            'contact_person_id' => $contactperson->id,
            'status' => $personStatus,
            'is_deleted' => '0'
        ]);
    }

    //'country_id',

    public function destroy($type, ContactPerson $contactperson): array
    {
        $contactperson->delete();

        return deleted_responses('contactperson');
    }

    public function customerById($customer)
    {
        return $this->service
            ->findCustomer($customer);
    }

    public function contactPersonArea(): object
    {
        return $this->service->whereNotNull('area')->get(['area']);
    }

    public function contactPersonCity(): object
    {
        return $this->service->whereNotNull('city')->get(['city']);
    }

    public function supplierContactPersons($supplier)
    {
        return $this->supplierContactPersonService->findSupplier($supplier);
    }

    public function getCustomerData()
    {

        // $perPage = 50;
        // $contactPersons = ContactPerson::with(['customer.customerGroup'])
        //     ->filters($this->filter)
        //     ->paginate($perPage);

        // $mappedData = $contactPersons->map(function ($item) {
        //     return [
        //         'id' => $item->id,
        //         'customer_group_id' => optional(optional($item->customer)->customerGroup)->id,
        //         'customer_group_name' => optional(optional($item->customer)->customerGroup)->name,
        //         'customer_id' => optional($item->customer)->id,
        //         'created_by' => optional($item->customer)->created_by,
        //         'name' => $item->name,
        //         'email' => $item->email,
        //         'phonenumber' => $item->phonenumber,
        //         'designation' => $item->designation,
        //         'customer_name' => optional($item->customer)->name,
        //         'contacts' => [],  // You might need to populate this field based on your data structure
        //         'customer_group' => [
        //             'id' => optional(optional($item->customer)->customerGroup)->id,
        //             'name' => optional(optional($item->customer)->customerGroup)->name,
        //             'tenant_id' => optional(optional($item->customer)->customerGroup)->tenant_id,
        //             'created_at' => optional(optional($item->customer)->customerGroup)->created_at,
        //             'updated_at' => optional(optional($item->customer)->customerGroup)->updated_at,
        //             'is_default' => optional(optional($item->customer)->customerGroup)->is_default,
        //         ],
        //         'addresses' => [],  // You might need to populate this field based on your data structure
        //         // Add more fields as needed
        //     ];
        // });
        // return response()->json([
        //     'data' => $mappedData,
        //     'total' => $contactPersons->total(),
        //     'per_page' => $contactPersons->perPage(),
        //     'current_page' => $contactPersons->currentPage(),
        //     'last_page' => $contactPersons->lastPage(),
        //     'from' => $contactPersons->firstItem(),
        //     'to' => $contactPersons->lastItem(),

        // ]);

        $perPage = 50;

        $contactPersons = ContactPerson::with([
            'customer.customerGroup',
            'statusLogs' => function ($query) {
                $query->where('is_deleted', 0);
            }
        ])->filters($this->filter)->paginate($perPage);

        $mappedData = $contactPersons->map(function ($item) {
            return [
                'id' => $item->id,
                'customer_group_id' => optional(optional($item->customer)->customerGroup)->id,
                'customer_group_name' => optional(optional($item->customer)->customerGroup)->name,
                'customer_id' => optional($item->customer)->id,
                'created_by' => optional($item->customer)->created_by,
                'name' => $item->name,
                'email' => $item->email,
                'phonenumber' => $item->phonenumber,
                'designation' => $item->designation,
                'customer_name' => optional($item->customer)->name,
                'contacts' => [],  // You might need to populate this field based on your data structure
                'customer_group' => [
                    'id' => optional(optional($item->customer)->customerGroup)->id,
                    'name' => optional(optional($item->customer)->customerGroup)->name,
                    'tenant_id' => optional(optional($item->customer)->customerGroup)->tenant_id,
                    'created_at' => optional(optional($item->customer)->customerGroup)->created_at,
                    'updated_at' => optional(optional($item->customer)->customerGroup)->updated_at,
                    'is_default' => optional(optional($item->customer)->customerGroup)->is_default,
                ],
                'addresses' => [],  // You might need to populate this field based on your data structure
                'status_logs' => $item->statusLogs->where('is_deleted', 0),
                // Add more fields as needed
            ];
        });

        return response()->json([
            'data' => $mappedData,
            'total' => $contactPersons->total(),
            'per_page' => $contactPersons->perPage(),
            'current_page' => $contactPersons->currentPage(),
            'last_page' => $contactPersons->lastPage(),
            'from' => $contactPersons->firstItem(),
            'to' => $contactPersons->lastItem(),
        ]);


    }



    public function getCustomerDataId(Request $request, $id)
    {
        // $contactPerson = ContactPerson::with(['customer.customerGroup'])->find($id);

        // if (!$contactPerson) {
        //     return response()->json([
        //         'error' => 'Contact person not found.',
        //     ], 404);
        // }

        // $mappedData = [
        //     'id' => $contactPerson->id,
        //     'name' => $contactPerson->name,
        //     'email' => $contactPerson->email,
        //     'phonenumber' => $contactPerson->phonenumber,
        //     'designation' => $contactPerson->designation,
        //     'customer_id' => optional($contactPerson->customer)->id,
        //     'customer_name' => optional($contactPerson->customer)->name,
        //     'customer_group_name' => optional(optional($contactPerson->customer)->customerGroup)->name,
        //     // Add more fields as needed
        // ];

        // return response()->json([
        //     'data' => $mappedData,
        // ]);

        $contactPerson = ContactPerson::with([
            'customer.customerGroup',
            'statusLogs' => function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('is_deleted')
                    ->orWhere('is_deleted', 0);
                });
            }
        ])->find($id);

        if (!$contactPerson) {
            return response()->json([
                'error' => 'Contact person not found.',
            ], 404);
        }

        $mappedData = [
            'id' => $contactPerson->id,
            'name' => $contactPerson->name,
            'email' => $contactPerson->email,
            'phonenumber' => $contactPerson->phonenumber,
            'designation' => $contactPerson->designation,
            'customer_id' => optional($contactPerson->customer)->id,
            'customer_name' => optional($contactPerson->customer)->name,
            'customer_group_name' => optional(optional($contactPerson->customer)->customerGroup)->name,
            'status' => null, // Default value, set to the actual value if applicable
            // Add more fields as needed
        ];

        // Check if there are status logs with is_deleted null or 0
        $latestStatusLog = $contactPerson->statusLogs->where('is_deleted', null)->merge(
            $contactPerson->statusLogs->where('is_deleted', 0)
        )->sortByDesc('created_at')->first();

        if ($latestStatusLog) {
            $mappedData['status'] = $latestStatusLog->status;
        }

        return response()->json([
            'data' => $mappedData,
        ]);
    }
}
