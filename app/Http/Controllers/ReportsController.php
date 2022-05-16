<?php

namespace App\Http\Controllers;

use App\Exceptions\SafeException;
use App\Models\Albums;
use App\Models\Comments;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\Messages;
use App\Models\Reports;
use App\Models\Tracks;
use App\Models\User;
use App\Notifications\ReportNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReportsController extends Controller
{
    /**
     * This function creates a new report
     * @param Request $request
     * @param $reportable_type
     * @return JsonResponse|null
     * @throws SafeException
     * @throws ValidationException
     */
    private function createReport(Request $request, $reportable_type): ?JsonResponse {
        Validator::validate($request->all(), [
            "extra_informations" => "required|string",
            "reportable_id" => "required|uuid",
            "reason_id" => "required|exists:report_reasons,id"
        ]);
        /** @var User $user */
        $user = auth()->user();
        ReportReasonsController::checkReportableTypeIsValid($reportable_type);
        /** @var  $report */
        $report = Reports::create([
            "extra_informations" => $request->input('extra_informations'),
            "reportable_id" => $request->input('reportable_id'),
            "reportable_type" => $reportable_type,
            "reason_id" => $request->input('reason_id'),
            "reporter_id" => $user->id
        ]);
        /** @var User $user */
        $Reporteduser = $report->reportable->reportableUser();
        $Reporteduser->notify(new ReportNotification($Reporteduser,$report));
        return response()->json([
            "report" => $report->withoutRelations()
        ]);
    }

    /** This function only makes the call to createReport with the morph class as a parameter */
    public function createTrackReport(Request $request): ?JsonResponse
    {
        return $this->createReport($request,Tracks::class);
    }

    /** This function only makes the call to createReport with the morph class as a parameter */
    public function createLyricReport(Request $request): ?JsonResponse
    {
        return $this->createReport($request,Lyrics::class);
    }

    /** This function only makes the call to createReport with the morph class as a parameter */
    public function createCoverReport(Request $request): ?JsonResponse
    {
        return $this->createReport($request,Covers::class);
    }

    /** This function only makes the call to createReport with the morph class as a parameter */
    public function createAlbumReport(Request $request): ?JsonResponse
    {
        return $this->createReport($request,Albums::class);
    }

    /** This function only makes the call to createReport with the morph class as a parameter */
    public function createCommentReport(Request $request): ?JsonResponse
    {
        return $this->createReport($request,Comments::class);
    }

    /** This function only makes the call to createReport with the morph class as a parameter */
    public function createMessageReport(Request $request): ?JsonResponse
    {
        return $this->createReport($request,Messages::class);
    }

    /** This function only makes the call to createReport with the morph class as a parameter */
    public function createUserReport(Request $request): ?JsonResponse
    {
        return $this->createReport($request,User::class);
    }

    /**
     * This function updates report
     * @param Request $request
     * @param $report_id
     * @return JsonResponse|null
     * @throws ValidationException
     * @throws SafeException
     */
    public function updateReport(Request $request, $report_id): ?JsonResponse {
        Validator::validate($request->all(), [
            "extra_informations" => "required|string",
        ]);
        /** @var User $user */
        $user = auth()->user();
        $report = $user->createdReports()->where("id", $report_id)->first();
        if(!empty($report)) {
            $report->update([
                "extra_informations" => $request->input("extra_informations")
            ]);
            return response()->json([
                "report" => $report->withoutRelations()
            ]);
        }

        throw new SafeException(
            config("error-codes.REPORT_NOT_FOUND.message"),
            config("error-codes.REPORT_NOT_FOUND.code")
        );
    }
}
