<?php
namespace Database\Seeders;
use App\Models\Project;
use Illuminate\Database\Seeder;
class BuilderCaseStudySeeder extends Seeder {
    public function run(): void {
        // Preview-only editorial candidates. No results, sales figures, or claimed case-study type are fabricated.
        foreach ([
            'nichols-project---9-gold-awards'=>'Interior design / renovation',
            'petersen-residence'=>'Residential design',
            'mockingbird-paradise-valley'=>'Luxury home staging',
            'occupied-home-staging'=>'Occupied staging',
            '13-5-million-dollar-property'=>'Luxury property',
            'prof-sports-client-featured-on-nbc'=>'Property presentation',
        ] as $slug=>$type) {
            $project=Project::where('slug',$slug)->first();
            if ($project && $project->case_study_type === null) {
                $project->builder_featured=true;
                $project->case_study_type=$type;
                $project->save();
            }
        }
    }
}
