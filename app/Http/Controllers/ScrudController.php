<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ScrudController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function getOffers()
    {
        return Offer::get();
    }

    public function create()
    {
        return view('offers.create');
    }

    public function store(Request $request)
    {
        /* validate the data before insertion */
        $rules = $this->getRules();
        $message = $this->getMessages();
        $validator = Validator::make($request->all(),$rules,$message);
        /* if form has wrong validation */
        if ($validator->fails())
        {
            //redirect to same form with all inputs and with error messages
            return redirect()->back()->withErrors($validator)
                ->withInput($request->all());
        }

        /* insert into Offer table if validation is ok*/
        Offer::create([
            "name_ar" => $request->name_ar,
            "name_en" => $request->name_en,
            "price"=> $request->price,
            "details_ar"=> $request->details_ar,
            "details_en"=> $request->details_en,
        ]);
        return redirect()->back()->with(["success"=>"تم اضافة العرض بنجاح"]);
    }
    public function getRules(){
        return $rules=[
            'name_ar'=>'required|max:20|unique:offers,name_ar',
            'name_en'=>'required|max:20|unique:offers,name_en',
            'price'=>'required|numeric',
            'details_ar'=>'required',
            'details_en'=>'required',
        ];
    }
    public function getMessages(){

        return $message =[
            'name_ar.required'=> __('messages.offers name_ar required'),
            'name_en.required'=> __('messages.offers name_en required'),
            'name_ar.unique'=> trans('messages.offers name_ar unique'),
            'name_en.unique'=> trans('messages.offers name_en unique'),
            'price.required'=>__('messages.offers price required')
        ];
    }
     // function to show all records of offers table with two languages
    public function show()
    {
        $offers = Offer::select(
            'id',
            'price',
            'name_' . LaravelLocalization::getCurrentLocale() . ' as name',
            'details_' . LaravelLocalization::getCurrentLocale() . ' as details'
        )->get();
        return view('offers.all',compact('offers'));
    }

    // Edit offer Function
    public function editOffer($offer_id){
        $offer = Offer::select('id','price','name_ar','name_en','details_ar','details_en')->find($offer_id);
        return view('offers.edit',compact('offer'));
    }

    // update offer
    public function updateOffer(Request $request,$offer_id){
        $offer = Offer::find($offer_id);
        if(!$offer)
            return redirect()->back();
        $offer->update($request->all());
        return redirect()->back()->with(['success'=>'شكراً']);

    }
}
