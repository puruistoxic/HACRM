<?php

namespace App\Http\Controllers\Tenant\Contacts;

use Illuminate\Http\Request;
use App\Filters\Tenant\PaymentFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\PaymentRequest;
use App\Models\Tenant\Customer\Payment;
use App\Services\Tenant\Contact\ContactPersonPaymentService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{

    public function __construct(ContactPersonPaymentService $service, PaymentFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index($type): LengthAwarePaginator
    {
        //
        return $this->service
            ->select(
                'id',
                'user_id',
                'contact_person_id',
                'title',
                'description',
                'source',
                'date',
                'net_gbp',
                'gross_gbp',
                'payment_status'
            )
            ->filters($this->filter)
            ->where($type . "_id", '!=', null)
            ->with($type)
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(PaymentRequest $request)
    {
        $this->service
            ->save(
                $request->only(
                    'id',
                    'user_id',
                    'contact_person_id',
                    'title',
                    'description',
                    'source',
                    'date',
                    'net_gbp',
                    'gross_gbp',
                    'payment_status'
                )
            );
        return created_responses('payment');
    }

    public function show($type, Payment $payment): Payment
    {
        return $payment;
    }


    public function update($type, PaymentRequest $request, Payment $payment)
    {
        $this->service
            ->setModel($payment)
            ->save($request->only(
                'id',
                'user_id',
                'contact_person_id',
                'title',
                'description',
                'source',
                'date',
                'net_gbp',
                'gross_gbp',
                'payment_status'
            ));
        return updated_responses('payment', ['payment' => $payment]);
    }

    public function destroy($type, Payment $payment): array
    {
        $payment->delete();

        return deleted_responses('payment');
    }

    public function paymentById($payment)
    {
        return $this->service
            ->findPayment($payment);
    }

    public function paymentId($payment)
    {
        return $this->service
            ->findPaymentId($payment);
    }
}
