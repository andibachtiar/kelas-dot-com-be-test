<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {

    }

    public function murid()
    {
        $student = User::where("role", "murid")
            ->with(["courses", "subscription", "courses.mentor"])
            ->first();

        if(!$student) {
            return [
                "status" => 404,
                "message" => "Data not found!",
            ];
        }

        $courses = [];

        $studentCourses = $student->courses()
            ->wherePivotBetween(
                "month",
                [now()->startOfMonth(), now()->endOfMonth()]
            )
            ->get();

        foreach($studentCourses as $course) {
            $courses[] = [
                "course" => $course->name,
                "mentor" => $course->mentor->name,
                "course_duration" => $course->video_duration,
                "user_watch_duration" => $course->pivot->watch_duration,
            ];
        }

        return response()->json([
            "status" => 200,
            "data" => [
                "name" => $student->name,
                "email" => $student->email,
                "periode" => now()->format("Y-m"),
                "subscription"=> $student->subscription->name,
                "subscription_expiration_date" => $student->subscription_end,
                "courses_watched" => $courses,
            ],
        ]);
    }

    public function mentor()
    {
        $mentor = User::where("role", "mentor")
            ->with([
                "mentor_courses",
                "mentor_courses.users",
                "mentor_courses.users.subscription"
            ])
            ->first();

        if(!$mentor) {
            return [
                "status" => 404,
                "message" => "Data not found!",
            ];
        }

        $subscribers = [];

        foreach($mentor->mentor_courses as $course) {
            $users = $course->users()
                ->wherePivotBetween(
                    "month",
                    [now()->startOfMonth(), now()->endOfMonth()]
                )
                ->get();

            $videoDuration = 0;

            foreach($users as $user) {
                if(!array_key_exists($user->id, $subscribers)) {
                    $subscribers[$user->id] = [
                        "name" => $user->name,
                        "email" => $user->email,
                        "revenue" => 0,
                        "watched_duration" => 0,
                        "courses_duration" => 0,
                        "course_details" => [],
                        "subscription" => [
                            "price" => $user->subscription->price,
                            "fee" => $user->subscription->fee,
                        ]
                    ];
                }

                $subscribers[$user->id]["course_details"][$course->id] = [
                    "name" => $course->name,
                    "watch_percentage" => number_format($user->pivot->watch_duration / $course->video_duration * 100, 2),
                    "course_duration" => $course->video_duration,
                    "watched_duration" => $user->pivot->watch_duration,
                ];

                $subscribers[$user->id]["watched_duration"] += $user->pivot->watch_duration;
                $subscribers[$user->id]["courses_duration"] += $course->video_duration;
            }
        }

        $subscribers = collect($subscribers)->map(function($item) {
            $item["revenue"] =  $revenue = self::getCourseRevenue(
                $item["watched_duration"],
                $item["courses_duration"],
                $item["subscription"]["price"],
                $item["subscription"]["fee"],
            );
            $item["course_details"] = array_values($item["course_details"]);
            unset($item["subscription"]);
            return $item;
        });

        return response()->json([
            "status" => 200,
            "data" => [
                "name" => $mentor->name,
                "email" => $mentor->email,
                "periode" => now()->format('Y-m'),
                "total_revenue" => self::getMonthlyRevenue($subscribers),
                "subscribers" => $subscribers->values(),
            ],
        ]);
    }

    protected static function getCourseRevenue(
        $watched_duraton,
        $course_duration,
        $subscription_price,
        $subscription_fee)
    {
        $fee = $subscription_price * $subscription_fee;
        $basicRevenue = ($subscription_price - $fee) / 2;

        return intval($basicRevenue * $watched_duraton / $course_duration);
    }

    protected static function getMonthlyRevenue(Collection $subscribers)
    {
        return $subscribers->reduce(function($total, $item) {
            return $total + $item["revenue"];
        });
    }
}
