<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Filters\Tenant\CustomerFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\CustomerRequest;
use App\Models\Tenant\Contact\ContactPerson;

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
        return $this->service
            ->filters($this->filter)
            ->with($this->relationship())
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
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

        return $customer->setRelation('contacts', $contacts)->load(['customerGroup', 'addresses']);
    }


    public function update(CustomerRequest $request, Customer $customer): array
    {
        DB::transaction(function () use ($customer, $request) {

            $customer->update(
                $request->only('first_name', 'last_name', 'tin', 'customer_group_id', 'created_by', 'tenant_id', 'email')
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
        });

        return updated_responses('customer');
    }

    public function destroy(Customer $customer): array
    {
        $this->service
            ->setModel($customer)
            ->deleteCustomer();

        return deleted_responses('customer');
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



}
