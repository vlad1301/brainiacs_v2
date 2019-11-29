<?php

namespace App\Http\Controllers;

use App\Keyword;
use App\Project;
use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use PhpMyAdmin\Dbi\DbiDummy;
use Session;


class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $projects=Project::all();

        return view('project.all_projects', compact('projects'));
        //return view('project.index')->withProjects($projects);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'project_name' => 'required',
            'project_url' => 'required',
            'se_name' => 'required',
            'se_language' => 'required',
            'se_location' => 'required',
            'frequency' => 'required',
        ]);
        //dd($request->all());
        /*  $input=$request->all();*/

        $project_name= $request->project_name;
        $project_url = $request->project_url;
        $search_engine = $request->se_name;
        $se_language = $request->se_language;
        //$loc_name_canonical=$request->se_location;
        $loc_name = $request->se_location;
        $frequency= $request->frequency;
        //$loc_name_canonical=Location::where('loc_name_canonical', 'LIKE', "%{$loc_name}%")->get();

        $se_id=DB::table('engines')->where('se_name', 'LIKE', $search_engine)->where('se_language', 'LIKE', $se_language)->pluck('se_id');

        $loc_id = DB::Table('locations')->select('loc_id')->where('loc_name','LIKE',  $loc_name)->where('loc_type', 'LIKE', 'City')
            ->pluck('loc_id');
        /*        Project::create($input);*/

        Project::create(['projectName' => $project_name, 'projectUrl' => $project_url, 'search_engine_name' => $search_engine, 'search_engine_id' => $se_id[0],
            'location_name' => $loc_name, 'location_id'=>$loc_id[0], 'search_engine_language' => $se_language, 'frequency' => $frequency]);

        Session::flash('flash_message', 'Task successfully added!');

        //return redirect()->back();


        $projects=Project::all();
        return view('project.all_projects', compact('projects'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $project=Project::findOrFail($id);
        return view('project.show')->withProject($project);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $project=Project::findOrFail($id);

        return view('project.edit')->withProject($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project=Project::find($id);
        $project_id=$project->id;
        $user['id'] = $project->user;
        $search_key=$request->keyword;
        $search_key=preg_split('/\r\n|[\r\n]/', $search_key);
        foreach ($search_key as $search_key_keyword)
        {

            Keyword::create(['project_id' => $project_id, 'user_id' => $user['id'], 'value' => $search_key_keyword]);

        }
        $projects=Project::all();
        return view('project.all_projects', compact('projects'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function view_projects(){

      $projects=Project::all();

        return view('project.all_projects', compact('projects'));

    }

    /*VIEW PROJECT RESULTS*/
    public function view_projects_results()
    {

        $url_s = DB::table('projects')->get();

        foreach($url_s as $url)  {

            $rezultate = $url->domain;
            /* $results []= SerpResult::where('resultUrl', 'LIKE', "%{$rezultate}%")->get();*/

        }
        $results = Result::where('result_url', 'LIKE', "%{$rezultate}%")->get();

        /*        $results = SerpResult::where('resultUrl', 'LIKE', "%{$rezultate}%")->groupBy('task_id')->get();*/


        /*        $results =DB::table('serp_results')->where('resultUrl', 'LIKE', "%{$rezultate}%")->groupBy('resultDatetime')->get();*/

        /*        $results =DB::table('serp_results')->where('resultUrl', 'LIKE', "%{$rezultate}%")->get();*/

        /*        dd($results);*/
        $number=count($results);

        return view('project.projectsResults', compact('results', 'number'));

    }


    /*SET PROJECT*/

    public function set_project()
    {

        return view('project.set_project');

    }

    /*SET KEYWORD*/

    public function set_keywords()
    {


        $projects=Project::all();

        return view('project.all_projects', compact('projects'));

    }

    /*END OF SET KEYWORD*/

    /*END OF SET PROJECT*/






    /* Search engine
       Search location
       Search language
   */
    public function search_engine(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('engines')
                ->select('se_name')
                ->distinct()
                ->where('se_name', 'LIKE', "%{$query}%")
                ->get();

            $output1 = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($data as $row)
            {
                $output1 .= '<li><a href="#">'.$row->se_name.'</a></li>';
            }
            $output1 .= '</ul>';
            echo $output1;
        }
    }

    public function search_language(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('engines')
                ->where('se_name', $query)
                ->distinct('se_language')
                ->get();
            /* $output = '<select class="dropdown-menu" style="display:block; position:relative">';*/
            //$output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($data as $row)
            {
                $output .= '<li><a href="#">'  . $row->se_language . '</a></li>';
                //$output .= '<li><a href="#">'.$row->se_language.'</a></li>';
            }
            $output .= '</ul>';
            //$output .= '</ul>';
            echo $output;
        }
    }


    public function search_location(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::table('locations')
                //->where([['name', 'LIKE', "%{$query}%"]])//, ['loc_type', '=', 'City']])
                ->where('loc_name', 'LIKE', "%{$query}%")->where('loc_type', 'LIKE', 'City')
                ->take(10)
                ->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($data as $row)
            {
                $output .= '
       <li><a href="#">'.$row->loc_name.'</a></li>
       ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    /*End of
        Search engine
        Search location
        Search language
    */

    public function save_project(Request $request)
    {

        /*if SAVE button is clicked*/
        if (Input::get('save'))
        {

            $this->validate($request, [
                'project_name' => 'required',
                'project_url' => 'required',
                'se_name' => 'required',
                'se_language' => 'required',
                'se_location' => 'required',
                'cronjob' => 'required'
            ]);
            //dd($request->all());
            /*  $input=$request->all();*/

            $project_name = $request->project_name;
            $project_url = $request->project_url;
            $user['id'] = Auth::user()->id;
/*            $user['name'] = Auth::user()->name;*/
            $search_engine = $request->se_name;
            $se_language = $request->se_language;
            //$loc_name_canonical=$request->se_location;
            $loc_name = $request->se_location;
            $cronjob = $request->cronjob;
            /*   dd($frequency);*/
            //$loc_name_canonical=Location::where('loc_name_canonical', 'LIKE', "%{$loc_name}%")->get();

            $se_id = DB::table('engines')->where('se_name', 'LIKE', $search_engine)->where('se_language', 'LIKE', $se_language)->pluck('se_id');

            $loc_id = DB::table('locations')->select('loc_id')->where('loc_name', 'LIKE', $loc_name)->where('loc_type', 'LIKE', 'City')
                ->pluck('loc_id');
            /*        Project::create($input);*/

            Project::create(['name' => $project_name, 'domain' => $project_url, 'user_id' => $user['id'], 'engine' => $search_engine, 'engine_id' => $se_id[0],
                'location' => $loc_name, 'location_id' => $loc_id[0], 'language' => $se_language, 'cronjob' => $cronjob]);

            /*            Session::flash('flash_message', 'Task successfully added!');*/

            //return redirect()->back();


            $projects = Project::all();
            return view('project.all_projects', compact('projects'));

        } /*if SAVE & ADD KEYWORD button is clicked*/
        elseif (Input::get('save_add_keyword'))
        {
            $this->validate($request, [
                'project_name' => 'required',
                'project_url' => 'required',
                'se_name' => 'required',
                'se_language' => 'required',
                'se_location' => 'required',
                'frequency' => 'required',
            ]);
            //dd($request->all());
            /*  $input=$request->all();*/


            $project_name = $request->project_name;
            $project_url = $request->project_url;
            $user['id'] = Auth::user()->id;
            $search_engine = $request->se_name;
            $se_language = $request->se_language;
            //$loc_name_canonical=$request->se_location;
            $loc_name = $request->se_location;
            $frequency = $request->frequency;
            //$loc_name_canonical=Location::where('loc_name_canonical', 'LIKE', "%{$loc_name}%")->get();

            $se_id = DB::table('engines')->where('se_name', 'LIKE', $search_engine)->where('se_language', 'LIKE', $se_language)->pluck('se_id');

            $loc_id = DB::Table('locations')->select('loc_id')->where('loc_name', 'LIKE', $loc_name)->where('loc_type', 'LIKE', 'City')
                ->pluck('loc_id');
            /*        Project::create($input);*/

            Project::create(['projectName' => $project_name, 'projectUrl' => $project_url,'user' => $user['id'], 'search_engine_name' => $search_engine, 'search_engine_id' => $se_id[0],
                'location_name' => $loc_name, 'location_id' => $loc_id[0], 'search_engine_language' => $se_language, 'frequency' => $frequency]);

            /*                Session::flash('flash_message', 'Task successfully added!');*/

            /* $id=Project::where('projectName', 'LIKE', 'vola')->get();

             dd($id);*/

            return view('project.set_keywords');

        }
    }

    /* public function store(Request $request)*/























//End of controller
}
