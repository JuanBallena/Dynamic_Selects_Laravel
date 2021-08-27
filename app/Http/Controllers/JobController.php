<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateJobRequest;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    const LIMIT_PAGINATE = 5;
    const CHARACTER_SLUG = '+';//pepe+pepe+epepe

    public function index()
    {
        $_title = request('title');
        $_place = request('place');

        $jobs = [];

        if (!$_title && !$_place) {
            $jobs = new Job();
        } 
        else {
            $_title = $this->replaceSlugCharacter($_title);
            $_place = $this->replaceSlugCharacter($_place);

            if ($_title && $_place) {
                $jobs = Job::searchByTitleOrDescription($_title)->searchByPlace($_place);
            }
            else if ($_title) {
                $jobs = Job::searchByTitleOrDescription($_title);
            }
            else {
                $jobs = Job::searchByPlace($_place);
            }
            
            $jobs->appends(['title' => $_title, 'place' => $_place]);
        }

        $jobs = $jobs->with(['country','state','city'])->paginate(self::LIMIT_PAGINATE);

        return view('jobs.index', compact('jobs'));
    }

    public function replaceSlugCharacter($parameter) {
        return str_replace(self::CHARACTER_SLUG, ' ', $parameter);
    }

    public function redirectSearch(Request $request) {
        if (!$request->input_title && !$request->input_place)
        {
            return redirect()->route('jobs.index');
        }
        $title = Str::slug($request->input_title, self::CHARACTER_SLUG);
        $place = Str::slug($request->input_place, self::CHARACTER_SLUG);
        return redirect("/jobs?title=".$title."&place=".$place);
        //return redirect()->route('jobs.index', [ 'title' => $request->input_title, 'place' => $place ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $defaultArraySize = 6;
        // $session = request()->session()->all();
        // if (count($session) > $defaultArraySize)
        // {
        //     $idCountry = $session['_old_input']['id_country'];
        //     //dd($idCountry);
        //     if ($idCountry)
        //     {
        //         $states = State::where('id_country', $idCountry)->get();
        //     }
        //     else
        //     {
        //         $states = [];
        //     }
        // }
        // else
        // {
        //     $states = [];
        // }
        $countries = Country::all();
        return view('jobs.create', [
            'countries' => $countries,
            //'states' => $states
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateJobRequest $request)
    {        
        $city = City::where('id_city', $request->id_city)->first();
        $place_description = $city->state->country->name.' '.$city->state->name.' '.$city->name;
        
        $request->request->add([
            "id_user" => auth()->user()->id,
            "place_description" => $place_description
        ]);
        //dd($request->all());
        
        Job::create($request->all());
        return redirect()->route('jobs.index');
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
        //
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

    public function getPlaceDescriptions() {
        $query = request('query');
        $places = Job::where('place_description', 'LIKE', '%'.$query.'%')
            ->distinct('place_description')
            ->pluck('place_description');
            
        return response()->json([
            'places' => $places,
        ]);
    }

    public function getPlaces() {
        
        $query = request('query');

        // $cities = City::where('name', 'LIKE', '%'.$query.'%')->get();
        // $states = State::where('name', 'LIKE', '%'.$query.'%')->get();

        // $collect = new Collection();

        // foreach ($cities as $city) {
        //     $collect->push($city);
        // }

        // foreach ($states as $state) {
        //     foreach ($state->cities as $city) {
        //         $collect->push($city);
        //     }             
        // }

        // $collect = $collect->unique();
        // $collect->all();

        // $places = [];
        // foreach ($collect as $key => $city) {
        //     $places[$key] = $city->state->name.' - '.$city->name;
        // }

        $places = DB::table('cities')
            ->join('states', 'cities.id_state', '=', 'states.id_state')
            ->join('countries', 'states.id_country', '=', 'countries.id_country')
            ->select(
                'cities.name as city', 'states.name as state',
            )
            ->where('cities.name', 'LIKE', '%'.$query.'%')
            ->orWhere('states.name', 'LIKE', '%'.$query.'%')
            ->orWhere('countries.name', 'LIKE', '%'.$query.'%')
            ->get();
            
        return response()->json([
            'places' => $places,
        ]);
    }
}



// $parameter_title = "";
// $parameter_place = "usa";
// $entry=0;


// $query = DB::table('jobs');

// $query->join('cities', 'jobs.id_city', '=', 'cities.id_city')
//     ->join('states', 'cities.id_state', '=', 'states.id_state')
//     ->join('countries', 'states.id_country', '=', 'countries.id_country')
//     ->select(
//         'jobs.id_job as id', 
//         'jobs.title as title',
//         'jobs.description as description',
//         'jobs.place_description as place',
//     );

// if ($parameter_title && $parameter_place)
// {
//     $query->where(function($query) use($parameter_title) {
//         return  $query->where('jobs.title', 'LIKE', '%'.$parameter_title.'%')
//             ->orWhere('jobs.description', 'LIKE', '%'.$parameter_title.'%');
//     })
//     ->where(function($query) use($parameter_place) {
//         return  $query->where('cities.name', 'LIKE', '%'.$parameter_place.'%')
//         ->orWhere('states.name', 'LIKE', '%'.$parameter_place.'%')
//         ->orWhere('countries.name', 'LIKE', '%'.$parameter_place.'%');

//     });
// }
// else if ($parameter_title)
// {
//     $query->where('jobs.title', 'LIKE', '%'.$parameter_title.'%')
//         ->orWhere('jobs.description', 'LIKE', '%'.$parameter_title.'%');
// }
// else
// {
//     $query->where('cities.name', 'LIKE', '%'.$parameter_place.'%')
//         ->orWhere('states.name', 'LIKE', '%'.$parameter_place.'%')
//         ->orWhere('countries.name', 'LIKE', '%'.$parameter_place.'%');
// };

// $data = $query->paginate(self::LIMIT_PAGINATE);

// dd($parameter_title, $parameter_place, $data, $entry);

    
        