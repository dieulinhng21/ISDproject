<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\admin\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Http\Requests;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = project::paginate(5);
        return view("admin.project.index", array('model' => $project));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::allows('admin', Auth::user())){
        return view("admin.project.create");
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
            'project_name' => 'required|regex:/^([a-zA-Z\s\-]*)$/|unique:duan,tenduan',
            'company' => 'required|regex:/^([a-zA-Z\s\-]*)$/',
            'location' => 'required',
            'price' => 'required|numeric|min:1',
            'apartment_number' => 'required|numeric| min:1'
            // 'status' => 'required'
        ],
        [
            'project_name.required' => 'Tên dự án còn trống',
            'project_name.regex' => 'Tên dự án chứa ký tự không hợp lệ',
            'project_name.unique' => 'Tên dự án đã tồn tại',
            //
            'company.required' => 'Công ty trực thuộc còn trống',
            'company.regex' => 'Tên công ty trực thuộc chứa ký tự không hợp lệ',
            //
            'location.required' => 'Vị trí còn trống',
            //
            'price.required' => 'Trị giá còn trống',
            'price.numeric' => 'Trị giáphải có dạng số',
            'price.min' => 'Trị giá phải lớn hơn 1',
            //
            'apartment_number.required' => 'Số tòa nhà còn trống',
            'apartment_number.numeric' => 'Số tòa nhà phải có dạng số',
            'apartment_number.min' => 'Số tòa nhà phải lớn hơn 1',
            // 'date_format:Y-m-d' => 'Ngày tháng theo định dạng năm-tháng-ngày',
        ]);
            $project = project::create();  
                        
            $project->tenduan= $request->get('project_name');
            $project->congtytructhuoc = $request->get('company');
            $project->vitri = $request->get('location');
            $project->trigia= $request->get('price');
            $project->sotoanha = $request->get('apartment_number');
            $project->tinhtrang = $request->get('status');
            
            $project->save();
            session()->flash('create_notif','Thêm dự án thành công!');
            return redirect('/admin/project');
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
        //$project_list = DB::table('duan')->get();,'project_list'
        $project = project::find($id);
        return view("admin.project.edit", compact('project'));
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
            'project_name' => 'required|regex:/^([a-zA-Z\s\-]*)$/|unique:duan,tenduan,' . $id.',idduan',
            'company' => 'required|regex:/^([a-zA-Z\s\-]*)$/',
            'location' => 'required',
            'price' => 'required|numeric|min:1',
            'apartment_number' => 'required|integer|min:1',
            
        ],
        [
            'project_name.required' => 'Tên dự án còn trống',
            'project_name.regex' => 'Tên dự án chứa ký tự không hợp lệ',
            'project_name.unique' => 'Tên dự án đã tồn tại',
            //
            'company.required' => 'Công ty trực thuộc còn trống',
            'company.regex' => 'Công ty trực thuộc chứa ký tự không hợp lệ',
            //
            'location.required' => 'Vị trí còn trống',
            //
            'price.required' => 'Trị giá dự án còn trống',
            'price.numeric' => 'Trị giá dự án phải có dạng số',
            'price.min' => 'Trị giá dự án phải lớn hơn 1',
            //
            'apartment_number.required' => 'Số tòa nhà còn trống',
            'apartment_number.integer' => 'Số tòa nhà phải có dạng số',
            'apartment_number.min' => 'Số tòa nhà phải lớn hơn 1',
            //
            
        ]);          
            $project = Project::find($id);

            $project->tenduan= $request->get('project_name');
            $project->congtytructhuoc = $request->get('company');
            $project->vitri = $request->get('location');
            $project->trigia= $request->get('price');
            $project->sotoanha = $request->get('apartment_number');
            //luu input
            $project->save();
            session()->flash('update_notif','Cập nhật dự án thành công!');
            return redirect('/admin/project');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //xoa 
    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();
        session()->flash('delete_notif','Đã xóa dự án');
        return redirect('/admin/project');
    }
}
