<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\admin\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\apartment;
use App\Http\Requests;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $apartment = apartment::paginate(2);
        // return view("admin.apartment.index", array('model' => $apartment));
        $apartments = DB::table('toachungcu')
                        ->join('duan','toachungcu.idduan','=','duan.idduan')
                        ->select('toachungcu.*','duan.idduan','duan.tenduan')
                        ->paginate(5);
        return view('admin.apartment.index',['apartment_array'=>$apartments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::allows('admin', Auth::user())){
            $projects = DB::table('duan')->distinct()->get();
            return view("admin.apartment.create",['projects'=>$projects]);
        }else{
            return view("../404");
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required',
            'apartment_name' => 'required|regex:/^([a-zA-Z0-9\s\-]*)$/|max:50|unique:toachungcu,tentoa',
            'begin_trade_floor' => 'required|integer|min:1',
            'end_trade_floor' => 'required|integer|min:0',
            'begin_people_floor' => 'required|integer|min:0',
            'end_people_floor' => 'required|integer|min:0',
            'manage_team' => 'required|regex:/^([a-zA-Z0-9\s\-]*)$/|max:50',
        ],
        [
            'project_name' => 'Tên dự án còn trống',
            //
            'apartment_name.required' => 'Tên tòa chung cư còn trống',
            'apartment_name.regex' => 'Tên tòa chung cư chứa ký tự không hợp lệ',
            'apartment_name.max' => 'Tên tòa chung cư vượt quá số ký tự cho phép',
            'apartment_name.unique' => 'Tên tòa chung cư đã tồn tại',
            //
            'begin_trade_floor.required' => 'Tầng thương mại đầu tiên còn trống',
            'begin_trade_floor.integer' => 'Tầng thương mại đầu tiên phải là số nguyên',
            'begin_trade_floor.min'=> 'Tầng thương mại đầu tiên phải bắt đầu từ tầng 1',
            //
            'end_trade_floor.required' => 'Tầng thương mại cuối cùng còn trống',
            'end_trade_floor.integer' => 'Tầng thương mại cuối cùng phải là số nguyên',
            'end_trade_floor.min' => 'Tầng thương mại cuối cùng phải là số dương',
            //
            'begin_people_floor.required' => 'Tầng dân cư đầu tiên còn trống',
            'begin_people_floor.integer' => 'Tầng dân cư đầu tiên phải là số nguyên',
            'begin_people_floor.min' => 'Tầng dân cư đầu tiên phải bắt đầu bằng số dương',
            //
            'end_people_floor.required' => 'Tầng dân cư cuối cùng còn trống',
            'end_people_floor.integer' => 'Tầng dân cư cuối cùng phải là số nguyên',
            'end_people_floor.min' => 'Tầng dân cư cuối cùng phải là số dương',
            //
            'manage_team.required' => 'Đơn vị quản lý còn trống',
            'manage_team.regex' => 'Đơn vị quản lý chứa ký tự không hợp lệ',
            'manage_team.max' => 'Tên đơn vị quản lý vượt quá số ký tự cho phép',
        ]);
        $trade_begin = $request->get('begin_trade_floor');
        $trade_end = $request->get('end_trade_floor');
        $ppl_begin = $request->get('begin_people_floor');
        $ppl_end = $request->get('end_people_floor');
        if($trade_begin > $trade_end || $trade_begin > $ppl_begin || $trade_begin > $ppl_end){
            return redirect('/admin/apartment/create')->withErrors('Các tầng nên được chia theo thứ tự tăng dần từ 1')->withInput();
        }
        else if($trade_end > $ppl_begin || $trade_end > $ppl_end){
            return redirect('/admin/apartment/create')->withErrors('Các tầng nên được chia theo thứ tự tăng dần từ 1')->withInput();
        }
        else if($ppl_begin > $ppl_end){
            return redirect('/admin/apartment/create')->withErrors('Các tầng nên được chia theo thứ tự tăng dần từ 1')->withInput();
        }else{
            $apartment = Apartment::create();
            $apartment->idduan = $request->get('project_name');
            $apartment->tentoa = $request->get('apartment_name');
            $apartment->batdauthuongmai = $request->get('begin_trade_floor');
            $apartment->ketthucthuongmai = $request->get('end_trade_floor');
            $apartment->batdaudancu = $request->get('begin_people_floor');
            $apartment->ketthucdancu = $request->get('end_people_floor');
            $apartment->donviquanly = $request->get('manage_team');

            $apartment->save();
            session()->flash('create_notif','Thêm tòa chung cư thành công!');
            return redirect('/admin/apartment');
        }
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
        if (Gate::allows('admin', Auth::user())){
            $apartment = Apartment::find($id);
            $projects =DB::table('duan')->get();
            return view("admin.apartment.edit", compact('apartment','projects'));
        }else{
            return view("../404");
        }
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
        $request->validate([
            'apartment_name' => 'required|regex:/^([a-zA-Z0-9\s\-]*)$/|max:50|unique:toachungcu,tentoa,' . $id.',idtoachungcu',
            'begin_people_floor' => 'required|integer|min:0',
            'end_people_floor' => 'required|integer|min:0',
            'begin_trade_floor' => 'required|integer|min:1',
            'end_trade_floor' => 'required|integer|min:0',
            'manage_team' => 'required|regex:/^([a-zA-Z0-9\s\-]*)$/|max:50',
        ],
        [
            'project_name' => 'Tên dự án còn trống',
            //
            'apartment_name.required' => 'Tên tòa chung cư còn trống',
            'apartment_name.regex' => 'Tên tòa chung cư chứa ký tự không hợp lệ',
            'apartment_name.max' => 'Tên tòa chung cư vượt quá số ký tự cho phép',
            'apartment_name.unique' => 'Tên tòa chung cư đã tồn tại',
            //
            'begin_trade_floor.required' => 'Tầng thương mại đầu tiên còn trống',
            'begin_trade_floor.integer' => 'Tầng thương mại đầu tiên phải là số nguyên',
            'begin_trade_floor.min'=> 'Tầng thương mại đầu tiên phải bắt đầu từ tầng 1',
            //
            'end_trade_floor.required' => 'Tầng thương mại cuối cùng còn trống',
            'end_trade_floor.integer' => 'Tầng thương mại cuối cùng phải là số nguyên',
            'end_trade_floor.min' => 'Tầng thương mại cuối cùng phải là số dương',
            //
            'begin_people_floor.required' => 'Tầng dân cư đầu tiên còn trống',
            'begin_people_floor.integer' => 'Tầng dân cư đầu tiên phải là số nguyên',
            'begin_people_floor.min' => 'Tầng dân cư đầu tiên phải bắt đầu bằng số dương',
            //
            'end_people_floor.required' => 'Tầng dân cư cuối cùng còn trống',
            'end_people_floor.integer' => 'Tầng dân cư cuối cùng phải là số nguyên',
            'end_people_floor.min' => 'Tầng dân cư cuối cùng phải là số dương',
            //
            'manage_team.required' => 'Đơn vị quản lý còn trống',
            'manage_team.regex' => 'Đơn vị quản lý chứa ký tự không hợp lệ',
            'manage_team.max' => 'Tên đơn vị quản lý vượt quá số ký tự cho phép',
        ]);
        $trade_begin = $request->get('begin_trade_floor');
        $trade_end = $request->get('end_trade_floor');
        $ppl_begin = $request->get('begin_people_floor');
        $ppl_end = $request->get('end_people_floor');
        if($trade_begin > $trade_end || $trade_begin > $ppl_begin || $trade_begin > $ppl_end){
            return redirect('/admin/apartment/'.$id.'/edit')->withErrors('Các tầng nên được chia theo thứ tự tăng dần từ 1')->withInput();
        }
        else if($trade_end > $ppl_begin || $trade_end > $ppl_end){
            return redirect('/admin/apartment/'.$id.'/edit')->withErrors('Các tầng nên được chia theo thứ tự tăng dần từ 1')->withInput();
        }
        else if($ppl_begin > $ppl_end){
            return redirect('/admin/apartment/'.$id.'/edit')->withErrors('Các tầng nên được chia theo thứ tự tăng dần từ 1')->withInput();
        }else{
            $apartment = Apartment::find($id);
            
            $apartment->idduan= $request->get('project_name');
            $apartment->tentoa= $request->get('apartment_name');
            $apartment->batdauthuongmai= $request->get('begin_trade_floor');
            $apartment->ketthucthuongmai= $request->get('end_trade_floor');
            $apartment->batdaudancu= $request->get('begin_people_floor');
            $apartment->ketthucdancu= $request->get('end_people_floor');
            $apartment->donviquanly = $request->get('manage_team');      

            $apartment->save();
            session()->flash('update_notif','Cập nhật tòa chung cư thành công!');
            return redirect('/admin/apartment');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $apartment = Apartment::find($id);
        $apartment->delete();
        session()->flash('delete_notif','Đã xóa tòa chung cư!');

        return redirect('/admin/apartment');
        // ->with([
        //     'flash_message' => 'Deleted',
        //     'flash_message_important' => false
        // ]);
    }
}
