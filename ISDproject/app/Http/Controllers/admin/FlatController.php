<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\admin\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Flat;
use App\Http\Requests;

use Illuminate\Http\Request;

class FlatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // query 2 tables seperately to get distinct property
        $flats = DB::table('canho')
                        ->join('toachungcu','canho.idtoachungcu','=','toachungcu.idtoachungcu')
                        ->join('duan','canho.idduan','=','duan.idduan')
                        ->select('canho.*','toachungcu.tentoa','duan.tenduan')
                        ->distinct()
                        ->paginate(5);

        return view('admin.flat.index',['flat_array'=>$flats]);
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
        $apartments = DB::table('toachungcu')->distinct()->get();
        
        return view("admin.flat.create",compact('projects','apartments'));
        }else{
            return("../404");
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
            // 'project'=>'required',
            'apartment'=>'required',
            'flat'=>'required|regex:/^([a-zA-Z0-9\s\-]*)$/|unique:canho,tencanho',
            'price'=>'required|numeric|min:1',
            'square'=>'required|numeric|min:1',
            // 'livingroom'=>'required|integer|min:0',
            // 'kitchen'=>'required|integer|min:0',
            'bedroom'=>'required|integer|min:1',
            'bathroom'=>'required|integer|min:1',


        ],
        [
            // 'project.required' => 'Tên dự án còn trống',
            //
            'apartment.required' => 'Tên tòa chung cư còn trống',
            //
            'flat.required' => ' Tên căn hộ còn trống',
            'flat.regex' => ' Tên căn hộ chứa ký tự không hợp lệ',
            'flat.unique' => ' Tên căn hộ đã tồn tại',
            //
            'price.required' => 'Giá trị căn hộ còn trống',
            'price.numeric' => 'Giá trị căn hộ phải là số',
            'price.min' => 'Giá trị căn hộ không hợp lệ',
            //
            'square.required' => 'Diện tích căn hộ còn trống',
            'square.numeric' => 'Diện tích căn hộ phải là dạng số',
            'square.min' => 'Diện tích căn hộ không hợp lệ',
            //
            // 'livingroom.required' => 'Số phòng khách còn trống',
            // 'livingroom.integer' => 'Số phòng khách phải là số nguyên',
            // 'livingroom.min' => 'Số phòng khách phải lớn hơn 0',
            //
            'bedroom.required' => 'Số phòng ngủ còn trống',
            'bedroom.integer' => 'Số phòng ngủ phải là số nguyên',
            'bedroom.min' => 'Số phòng ngủ phải lớn hơn 1',
            //
            // 'kitchen.required' => 'Số phòng bếp còn trống',
            // 'kitchen.integer' => 'Số phòng bếp phải là số nguyên',
            // 'kitchen.min' => 'Số phòng bếp phải lớn hơn 0',
            //
            'bathroom.required' => 'Số phòng vệ sinh còn trống',
            'bathroom.integer' => 'Số phòng vệ sinh phải là số nguyên',
            'bathroom.min' => 'Số phòng vệ sinh phải lớn hơn 1',
        ]);
            $flat = Flat::create();
            
            $flat->idduan = $request->get('project');
            $flat->idtoachungcu= $request->get('apartment');
            $flat->tencanho= $request->get('flat');
            $flat->giatri= $request->get('price');
            $flat->dientich= $request->get('square');
            // $flat->sophongkhach= $request->get('livingroom');
            // $flat->sophongbep= $request->get('kitchen');
            $flat->sophongngu= $request->get('bedroom');
            $flat->sophongvesinh= $request->get('bathroom');
            $flat->tinhtrang= $request->get('status');


            $flat->save();
            session()->flash('create_notif','Thêm căn hộ thành công!');
            return redirect('/admin/flat'); 
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
        $flat = Flat::find($id);
        $projects = DB::table('duan')->distinct()->get();
        $apartments = DB::table('toachungcu')->distinct()->get();

        return view("admin.flat.edit",compact('flat','projects','apartments'));
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
            // 'project'=>'required',
            'apartment'=>'required',
            'flat'=>'required|regex:/^([a-zA-Z0-9\s\-]*)$/|unique:canho,tencanho,' . $id.',idcanho',
            'price'=>'required|numeric|min:1',
            'square'=>'required|numeric|min:1',
            // 'livingroom'=>'required|integer|min:0',
            // 'kitchen'=>'required|integer|min:0',
            'bedroom'=>'required|integer|min:1',
            'bathroom'=>'required|integer|min:1'

        ],
        [
            // 'project.required' => 'Tên dự án còn trống',
            //
            'apartment.required' => 'Tên tòa chung cư còn trống',
            //
            'flat.required' => ' Tên căn hộ còn trống',
            'flat.regex' => ' Tên căn hộ chứa ký tự không hợp lệ',
            'flat.unique' => ' Tên căn hộ đã tồn tại',
            //
            'price.required' => 'Giá trị căn hộ còn trống',
            'price.numeric' => 'Giá trị căn hộ phải là số',
            'price.min' => 'Giá trị căn hộ không hợp lệ',
            //
            'square.required' => 'Diện tích căn hộ còn trống',
            'square.numeric' => 'Diện tích căn hộ phải là dạng số',
            'square.min' => 'Diện tích căn hộ không hợp lệ',
            //
            // 'livingroom.required' => 'Số phòng khách còn trống',
            // 'livingroom.integer' => 'Số phòng khách phải là số nguyên',
            // 'livingroom.min' => 'Số phòng khách phải lớn hơn 0',
            //
            'bedroom.required' => 'Số phòng ngủ còn trống',
            'bedroom.integer' => 'Số phòng ngủ phải là số nguyên',
            'bedroom.min' => 'Số phòng ngủ phải lớn hơn 1',
            //
            // 'kitchen.required' => 'Số phòng bếp còn trống',
            // 'kitchen.integer' => 'Số phòng bếp phải là số nguyên',
            // 'kitchen.min' => 'Số phòng bếp phải lớn hơn 0',
            //
            'bathroom.required' => 'Số phòng vệ sinh còn trống',
            'bathroom.integer' => 'Số phòng vệ sinh phải là số nguyên',
            'bathroom.min' => 'Số phòng vệ sinh phải lớn hơn 1',
        ]);
            $flat = Flat::find($id);
            
            $flat->tencanho= $request->get('flat');
            $flat->idduan= $request->get('project');
            $flat->idtoachungcu= $request->get('apartment');
            $flat->dientich= $request->get('square');
            // $flat->sophongkhach= $request->get('livingroom');
            // $flat->sophongbep= $request->get('kitchen');
            $flat->sophongngu= $request->get('bedroom');
            $flat->sophongvesinh= $request->get('bathroom');
            $flat->tinhtrang= $request->get('status');

            $flat->save();
            session()->flash('update_notif','Cập nhật căn hộ thành công!');
            
            return redirect('/admin/flat');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flat = Flat::find($id);
        $flat->delete();

        session()->flash('delete_notif','Đã xóa căn hộ');
        return redirect('/admin/flat');
    }
}
