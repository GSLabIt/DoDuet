<?php

namespace App\Http\Controllers;

use App\Exceptions\SafeException;
use App\Models\Albums;
use App\Models\Comments;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\Messages;
use App\Models\ReportReasons;
use App\Models\Tracks;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReportReasonsController extends Controller
{
    /**
     * This function creates a new report reason
     * @param Request $request
     * @param $reportable_type
     * @return JsonResponse|null
     * @throws SafeException
     * @throws ValidationException
     */
    private function createReportReason(Request $request, $reportable_type): ?JsonResponse {
        Validator::validate($request->all(), [
            "name" => "required|string|max:255",
            "description" => "required|string",
        ]);
        $this->checkReportableTypeIsValid($reportable_type);
        return response()->json([
            "reportReason" => ReportReasons::create([
                "name" => $request->input('name'),
                "description" => $request->input('description'),
                "reportable_type" => $reportable_type,
            ])->withoutRelations()
        ]);
    }

    /** This function only checks if the requested reportable type is valid for both create and update
     * @throws SafeException
     */
    public static function checkReportableTypeIsValid($reportable_type)
    {
        if (!in_array($reportable_type,[Tracks::class,Lyrics::class,Covers::class,Albums::class,Comments::class,Messages::class,User::class])) {
            throw new SafeException(
                config("error-codes.REPORTABLE_TYPE_NOT_FOUND.message"),
                config("error-codes.REPORTABLE_TYPE_NOT_FOUND.code")
            );
        }
    }

    /** This function only makes the call to createReportReason with the morph class as a parameter */
    public function createTrackReportReason(Request $request): ?JsonResponse
    {
        return $this->createReportReason($request,Tracks::class);
    }

    /** This function only makes the call to createReportReason with the morph class as a parameter */
    public function createLyricReportReason(Request $request): ?JsonResponse
    {
        return $this->createReportReason($request,Lyrics::class);
    }

    /** This function only makes the call to createReportReason with the morph class as a parameter */
    public function createCoverReportReason(Request $request): ?JsonResponse
    {
        return $this->createReportReason($request,Covers::class);
    }

    /** This function only makes the call to createReportReason with the morph class as a parameter */
    public function createAlbumReportReason(Request $request): ?JsonResponse
    {
        return $this->createReportReason($request,Albums::class);
    }

    /** This function only makes the call to createReportReason with the morph class as a parameter */
    public function createCommentReportReason(Request $request): ?JsonResponse
    {
        return $this->createReportReason($request,Comments::class);
    }

    /** This function only makes the call to createReportReason with the morph class as a parameter */
    public function createMessageReportReason(Request $request): ?JsonResponse
    {
        return $this->createReportReason($request,Messages::class);
    }

    /** This function only makes the call to createReportReason with the morph class as a parameter */
    public function createUserReportReason(Request $request): ?JsonResponse
    {
        return $this->createReportReason($request,User::class);
    }

    public function updateReportReason(Request $request, $report_reason_id): ?JsonResponse {
        Validator::validate($request->all(), [
            "name" => "required|string|max:255",
            "description" => "required|string",
        ]);

        $report_reason = ReportReasons::where("id", $report_reason_id)->first();

        if(!empty($report_reason)) {
            $report_reason->update([
                "name" => $request->input('name'),
                "description" => $request->input('description')
            ]);
            return response()->json([
                "reportReason" => $report_reason->withoutRelations()
            ]);
        }
    }

    public function deleteReportReason($report_reason_id): JsonResponse
    {
        Validator::validate(["id" => $report_reason_id], [
            "id" => "required|exists:report_reasons,id"
        ]);
        $report_reason = ReportReasons::where("id", $report_reason_id)->first();
        $report_reason->delete();
        return response()->json([
            "reportReason" => $report_reason->withoutRelations()
        ]);
    }

}
