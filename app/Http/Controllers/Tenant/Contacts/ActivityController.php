<?php

namespace App\Http\Controllers\Tenant\Contacts;

use Illuminate\Http\Request;
use App\Filters\Tenant\ActivityFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Contact\ActivityRequest;
use App\Models\Tenant\Customer\Activity;
use App\Services\Tenant\Contact\ContactPersonActivityService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;

class ActivityController extends Controller
{

    public function __construct(ContactPersonActivityService $service, ActivityFilter $filter)
    {
        $this->service = $service;
        $this->filter = $filter;
    }

    public function index($type): LengthAwarePaginator
    {
        //
        return $this->service
            ->select('id', 'user_id', 'user_name', 'contact_person_id', 'activityId', 'title', 'description', 'start_time', 'end_time', 'participantsString', 'collaboratorsString', 'activity_status')
            ->filters($this->filter)
            ->where($type . "_id", '!=', null)
            ->with($type)
            ->orderByDesc('id')
            ->paginate(
                request()->get('per_page', 10)
            );
    }

    public function store(ActivityRequest $request)
    {
        $this->service
            ->save(
                $request->only(
                    'id',
                    'user_id',
                    'user_name',
                    'contact_person_id',
                    'activityId',
                    'title',
                    'description',
                    'start_time',
                    'end_time',
                    'participantsString',
                    'collaboratorsString',
                    'activity_status'
                )
            );
        return created_responses('activity');
    }

    public function show($type, Activity $activity): Activity
    {
        return $activity;
    }


    public function update($type, ActivityRequest $request, Activity $activity)
    {
        $this->service
            ->setModel($activity)
            ->save($request->only(
                'id',
                'user_id',
                'user_name',
                'contact_person_id',
                'activityId',
                'title',
                'description',
                'start_time',
                'end_time',
                'participantsString',
                'collaboratorsString',
                'activity_status'
            ));
        return updated_responses('activity', ['activity' => $activity]);
    }

    public function destroy($type, Activity $activity): array
    {
        $activity->delete();

        return deleted_responses('activity');
    }

    public function activityById($activity)
    {
        return $this->service
            ->findActivity($activity);
    }

    public function activityIdd($activity)
    {
        return $this->service
            ->findActivityId($activity);
    }

    public function completeActivity($type, Activity $activity)
    {
        // You may want to add some validation or authorization checks here

        // Update the 'completed' column to indicate that the activity is completed
        $activity->update(['completed' => true]);

        return updated_responses('activity', ['activity' => $activity]);
    }
}
