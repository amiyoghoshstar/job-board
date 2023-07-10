<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Skill;
use App\Models\Category;
use App\Models\Company;
use App\Models\Job;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DefStudio\Telegraph\Models\TelegraphChat;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use   DefStudio\Telegraph\Facades\Telegraph;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $req = $request->all();
        $jobIds = [];
        $chat = TelegraphChat::find(1);
        foreach ($req as $job) {

            // first create the job location
            $locations = $job['job_locations'];
            $location_ids = [];
            foreach ($locations as $location) {
                $saveLocation = Location::updateOrCreate(
                    [
                        "name" => $location
                    ],
                    [
                        "name" => trim($location)
                    ]
                );

                array_push($location_ids, $saveLocation->id);
            }

            // lets create skills
            $skills = $job['skills'];
            $skill_ids = [];
            if ($skills) {

                foreach ($skills as $skill) {
                    $saveSkill = Skill::updateOrCreate(
                        [
                            "name" => $skill
                        ],
                        [
                            "name" => trim($skill)
                        ]
                    );

                    array_push($skill_ids, $saveSkill->id);
                }
            }


            // lets create category
            $categories = $job['job_type'];
            $category_ids = [];
            foreach ($categories as $category) {
                $saveCategory = Category::updateOrCreate(
                    [
                        "name" => $category
                    ],
                    [
                        "name" => trim($category)
                    ]
                );

                array_push($category_ids, $saveCategory->id);
            }
            // now we will create the company
            $company = $job['company'];
            $logoUrl = $company['logo'];
            if ($logoUrl) {
                $logoExtension = explode('.', $logoUrl);
                $extension = end($logoExtension);
                $file = $company["name"] . '_' . time() . '.' . $extension;
                $localImagePath = Storage::disk('local')->put("public/" . $file, file_get_contents($logoUrl));
            }

            $companyUrls = $company['others'];
            if (!empty($companyUrls) && isset($companyUrls[0]["Website"])) {
                $website = $companyUrls[0]["Website"];
            }


            $companyModel = Company::updateOrCreate([
                "name" => $company['name']
            ], [
                "logo" => isset($file) ? $file : '',
                "name" => $company['name'],
                "description" => $company['description'],
                "website" => isset($website) ? $website : ''
            ]);

            $position = $job["position"];

            $jobModel = Job::updateOrCreate([
                "unique_id" => Str::slug($job["job_title"] . " " . $company['name'])
            ], [
                "company_id" => $companyModel->id,
                "title" => $job["job_title"],
                "description" => $job["job_description"],
                "apply_url" => $job["apply_link"],
                "category" => $category_ids,
                "position" => $position,
                "salary" => $job["salary"],
                "locations" => $location_ids,
                "skills" => $skill_ids,
                "unique_id" => Str::slug($job["job_title"] . " " . $company['name']),
                "source" => $job["source"],
                "status" => 1
            ]);
            // array_push($jobModel->title ,$jobIds);
            Telegraph::message("New Job Added! 🎉🥳 \n\n " . $jobModel->title . "")
                ->keyboard(Keyboard::make()->buttons([
                    Button::make("✅ Approve")->action("approve")->param('id', $jobModel->id),
                    Button::make("❌ Reject")->action("reject")->param('id', $jobModel->id),
                    Button::make("👀 View Preview")->url('https://test.it'),
                ])->chunk(2))->send();
        }



        return response()->json(['status' => 200, 'message' => 'Job Added'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
