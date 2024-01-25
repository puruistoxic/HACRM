<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Filters\Tenant\CustomerFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\CustomerRequest;
use App\Models\Tenant\Contact\ContactPerson;
use App\Models\Tenant\Customer\StatusLogs;
use Illuminate\Http\Request;
use App\Models\Tenant\Customer\Customer;
use App\Services\Tenant\Contact\CustomerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Tenant\Customer\CustomerGroup;

class CustomerController extends Controller
{
    public function __construct(CustomerService $service, CustomerFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    private function relationship(): array
    {
        return [
            'customerGroup:id,name',
            'addresses',
            'emails',
            'phoneNumbers',
            'profilePicture',
            'createdBy:id,first_name,last_name',
        ];
    }

    public function index(): LengthAwarePaginator
    {
        $customers = $this->service
            ->filters($this->filter)
            ->with($this->relationship())
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );

        $customers->load('statusLogs');

        return $customers;

    }

    public function store(CustomerRequest $request): array
    {

        DB::transaction(function () use ($request) {
            $this->service
                ->setAttrs($request->only('first_name', 'last_name', 'tin', 'customer_group_id', 'email'))
                ->store();

            $this->service
                ->setAttrs($request->only('email_details', 'phone_number_details'))
                ->storeContacts();

            if ($request->get('address_information_details')) {
                $this->service
                    ->setAttrs($request->only('address_information_details'))
                    ->storeAddress();
            }
        });

        return created_responses('customer');
    }

    public function show(Customer $customer): object
    {
        $contacts = $customer->contacts->groupBy('name');

        return $customer->setRelation('contacts', $contacts)->load(['customerGroup', 'addresses', 'statusLogs']);
    }


    public function update(CustomerRequest $request, Customer $customer): array
    {
        DB::transaction(function () use ($customer, $request) {

            $newCustomerGroupId = $request->input('customer_group_id');

            $customer->update(
                $request->only('first_name', 'last_name', 'tin', 'customer_group_id', 'created_by', 'tenant_id', 'email', 'status')
            );

            $this->service
                ->setModel($customer)
                ->setAttrs($request->only('email_details', 'phone_number_details'))
                ->updateContacts();

            if ($request->get('address_information_details')) {
                $this->service
                    ->setModel($customer)
                    ->setAttrs($request->only('address_information_details'))
                    ->updateAddress();
            }

            // Update the contactperson table if customer_group_id is changed


            $customer->contactPerson->each(function ($contactPerson) use ($newCustomerGroupId) {
                $contactPerson->update(['customer_group_id' => $newCustomerGroupId]);
            });


            // Get person status from the request
            $personStatus = $request->input('status');

            // Save person status
            $this->savePersonStatus($customer, $personStatus);
        });

        return updated_responses('customer');
    }

    protected function savePersonStatus(Customer $customer, $personStatus)
    {

        $existingStatusLogs = StatusLogs::where('customer_id', $customer->id)->get();

        // if ($existingStatusLog) {
        //     // Mark existing status log as deleted
        //     $existingStatusLog->update(['is_deleted' => true]);
        // }

        foreach ($existingStatusLogs as $existingStatusLog) {
            $existingStatusLog->update(['is_deleted' => true]);
        }

        // Create a new status log
        StatusLogs::create([
            'customer_id' => $customer->id,
            'status' => $personStatus,
            'is_deleted' => false
        ]);
    }

    public function destroy(Customer $customer): array
    {
        $this->service
            ->setModel($customer)
            ->deleteCustomer();

        return deleted_responses('customer');
    }

    // public function getCustomerActivity($customerId)
    // {
    //     $customer = Customer::with(['contactPerson.contactPersonActivities'])->find($customerId);

    //     if (!$customer) {
    //         return response()->json(['error' => 'Customer not found'], 404);
    //     }

    //     $mappedData = [
    //         'id' => $customer->id,
    //         'customer_group_id' => optional(optional($customer->customer)->customerGroup)->id,
    //         'customer_id' => optional($customer->customer)->id,
    //         'created_by' => optional($customer->customer)->created_by,
    //         'name' => $customer->name,
    //         'email' => $customer->email,
    //         'phone_number' => $customer->phone_number,
    //         'customer_name' => optional($customer->customer)->name,
    //         'contacts' => $customer->contactPersons->map(function ($contactPerson) {
    //             return [
    //                 'id' => $contactPerson->id,
    //                 'activity_details' => $contactPerson->contactPersonActivities->map(function ($activity) {
    //                     return [
    //                         'id' => $activity->id,
    //                         'user_id' => $activity->user_id,
    //                         'contact_person_id' => $activity->contact_person_id,
    //                         'activityId' => $activity->activityId,
    //                         'title' => $activity->title,
    //                         'description' => $activity->description,
    //                         'start_time' => $activity->start_time,
    //                         'end_time' => $activity->end_time,
    //                         'participants' => $activity->participants,
    //                         'created_at' => $activity->created_at,
    //                         'updated_at' => $activity->updated_at,
    //                     ];
    //                 }),
    //             ];
    //         }),
    //     ];

    //     return response()->json([
    //         'data' => $mappedData,
    //     ]);
    // }

    // public function getCustomerActivity($customerId)
    // {
    //     $customer = Customer::with(['contactPersons.contactPersonActivities'])->find($customerId);

    //     if (!$customer) {
    //         return response()->json(['error' => 'Customer not found'], 404);
    //     }

    //     $customerActivities = $customer->contactPersons->flatMap(function ($contactPerson) {
    //         return $contactPerson->contactPersonActivities->map(function ($activity) {
    //             return [
    //                 'id' => $activity->id,
    //                 'user_id' => $activity->user_id,
    //                 'contact_person_id' => $activity->contact_person_id,
    //                 'contact_person_name' => $activity->name,
    //                 'activityId' => $activity->activityId,
    //                 'title' => $activity->title,
    //                 'description' => $activity->description,
    //                 'start_time' => $activity->start_time,
    //                 'end_time' => $activity->end_time,
    //                 'participants' => $activity->participants,
    //                 'created_at' => $activity->created_at,
    //                 'updated_at' => $activity->updated_at,
    //             ];
    //         });
    //     });

    //     $mappedData = [
    //         'id' => $customer->id,
    //         'customer_group_id' => optional(optional($customer->customer)->customerGroup)->id,
    //         'customer_id' => optional($customer->customer)->id,
    //         'created_by' => optional($customer->customer)->created_by,
    //         'name' => $customer->name,
    //         'email' => $customer->email,
    //         'phone_number' => $customer->phone_number,
    //         'customer_name' => optional($customer->customer)->name,
    //         'all_activities' => $customerActivities->all(),
    //     ];

    //     return response()->json([
    //         'data' => $mappedData,
    //     ]);
    // }

    public function getAccountDataId(Request $request, $id)
    {
        $customer = Customer::with(['customerGroup'])->find($id);

        if (!$customer) {
            return response()->json([
                'error' => 'Customer not found.',
            ], 404);
        }

        $mappedData = [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phonenumber' => $customer->phonenumber,
            'designation' => $customer->designation, // Assuming this field exists in your Customer model
            'customer_id' => optional($customer->customer)->id,
            'customer_name' => optional($customer->customer)->name,
            'customer_group_name' => optional($customer->customerGroup)->name,
            'customer_group_id' => optional($customer->customerGroup)->id,
            // Add more fields as needed
        ];

        return response()->json([
            'data' => $mappedData,
        ]);
    }


    public function getCustomerActivity($customerId)
    {
        $customer = Customer::with(['contactPersons.contactPersonActivities'])->find($customerId);

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $customerActivities = $customer->contactPersons->flatMap(function ($contactPerson) {
            if ($contactPerson->contactPersonActivities->isNotEmpty()) {
                return $contactPerson->contactPersonActivities->map(function ($activity) use ($contactPerson) {
                    return [
                        'id' => $activity->id,
                        'user_id' => $activity->user_id,
                        'user_name' => $activity->user_name,
                        'contact_person_id' => $activity->contact_person_id,
                        'contact_person_name' => $contactPerson->name, // Add this line to get contact_person_name
                        'activityId' => $activity->activityId,
                        'title' => $activity->title,
                        'description' => $activity->description,
                        'start_time' => $activity->start_time,
                        'end_time' => $activity->end_time,
                        'participantsString' => $activity->participantsString,
                        'activity_status' => $activity->activity_status,
                        'created_at' => $activity->created_at,
                        'updated_at' => $activity->updated_at,
                    ];
                });
            }
            return [];
        });

        $mappedData = [
            'id' => $customer->id,
            'customer_group_id' => optional(optional($customer->customer)->customerGroup)->id,
            'customer_id' => optional($customer->customer)->id,
            'created_by' => optional($customer->customer)->created_by,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone_number' => $customer->phone_number,
            'customer_name' => optional($customer->customer)->name,
            'all_activities' => $customerActivities->all(),
        ];

        return response()->json([
            'data' => $mappedData,
        ]);
    }


    // public function getCustomerActivity($customerId)
    // {
    //     $customer = Customer::with(['contactPersons.contactPersonActivities'])->find($customerId);

    //     if (!$customer) {
    //         return response()->json(['error' => 'Customer not found'], 404);
    //     }

    //     $customerActivities = $customer->contactPersons->flatMap(function ($contactPerson) {
    //         if ($contactPerson->contactPersonActivities->isNotEmpty()) {
    //             return $contactPerson->contactPersonActivities->map(function ($activity) use ($contactPerson) {
    //                 // Fetch user information
    //                 $user = auth('admin')->user(); // Modify this according to your authentication logic

    //                 return [
    //                     'id' => $activity->id,
    //                     'user_id' => $user->id,
    //                     'user_name' => $user->first_name . ' ' . $user->last_name,
    //                     'contact_person_id' => $activity->contact_person_id,
    //                     'contact_person_name' => $contactPerson->name,
    //                     'activityId' => $activity->activityId,
    //                     'title' => $activity->title,
    //                     'description' => $activity->description,
    //                     'start_time' => $activity->start_time,
    //                     'end_time' => $activity->end_time,
    //                     'participants' => $activity->participants,
    //                     'created_at' => $activity->created_at,
    //                     'updated_at' => $activity->updated_at,
    //                 ];
    //             });
    //         }
    //         return [];
    //     });

    //     $mappedData = [
    //         'id' => $customer->id,
    //         'customer_group_id' => optional(optional($customer->customer)->customerGroup)->id,
    //         'customer_id' => optional($customer->customer)->id,
    //         'created_by' => optional($customer->customer)->created_by,
    //         'name' => $customer->name,
    //         'email' => $customer->email,
    //         'phone_number' => $customer->phone_number,
    //         'customer_name' => optional($customer->customer)->name,
    //         'all_activities' => $customerActivities->all(),
    //     ];

    //     return response()->json([
    //         'data' => $mappedData,
    //     ]);
    // }



}
