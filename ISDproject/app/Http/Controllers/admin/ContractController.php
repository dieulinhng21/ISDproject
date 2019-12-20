<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\admin\Controller;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Flat;
use App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class ContractController extends Controller
{

    public function index()
    { 
        // $contract = Contract::paginate(2);
        // , array('model' => $contract)
        $contracts = DB::table('hopdong')
                        ->join('duan','hopdong.idduan','=','duan.idduan')
                        ->join('canho','hopdong.idcanho','=','canho.idcanho')
                        ->join('khachhang','hopdong.idkhachhang','=','khachhang.idkhachhang')
                        ->select('hopdong.*','duan.tenduan','canho.tencanho','khachhang.idkhachhang','khachhang.hoten')
                        ->paginate(5);
        return view('admin.contract.index',['contract_array'=>$contracts]);
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
            $customers = DB::table('khachhang')->distinct()->orderByRaw('created_at DESC')->get();
            $flats = DB::table('canho')->where('tinhtrang','0')->get();
            return view('admin.contract.create',compact('projects','customers','flats'));
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

        $validator = $request->validate([
            //|regex:/^[a-zA-Z]+$
            'project_name' => 'required',
            'tencanho' => 'required|exists:canho,tencanho',
            // [
            //     'required',
            //     //tìm căn hộ trong bảng canho đồng thời validate tình trạng là còn trống hay đã đc bán
            //     Rule::exists('canho')->where(function ($query) {
            //         $query->where('tinhtrang', 0);
            //     }),
            // ],
            'san' => 'required',
            'contract_code' => 'required|regex:/^([a-zA-Z0-9\s\-]*)$/',
            'price' => 'required|numeric',
            'contract_date' => 'required|before_or_equal:today',
            'pay_date' => 'after_or_equal:today',
            'extra_date' => 'min:0',
            //validate customer infor before insert to db 
            'name' => 'required|regex:/^([a-zA-Z0-9\s\-]*)$/|max:50',
            'noicap' => 'required|max:50',
            'identity_card' => 'required|numeric|digits_between:9,10|unique:khachhang,chungminhthu',
            'identity_date' => 'required|before_or_equal:today',
            'phone_number' => 'required|numeric|digits_between:9,10|unique:khachhang,sodienthoai',
            'inhabitant' => 'required',
            'address' => 'required'
        ],
        [
            'project_name.required' => 'Tên dự án còn trống',
            //
            'tencanho.required' => 'Tên căn hộ còn trống',
            'tencanho.exists' => 'Căn hộ không có sẵn',
            // 'flat_name.unique' => 'Căn hộ đã có chủ',
            //
            'san.required' => 'Sàn giao dịch còn trống',
            //
            'contract_code.required' => 'Số hợp đồng còn trống',
            'contract_code.regex' => 'Số hợp đồng chứa ký tự không hợp lệ',
            //
            'price.required' => 'Giá trị hợp đồng còn trống',
            'price.numeric' => 'Giá trị hợp đồng phải là số',
            //
            'contract_date.required' => 'Ngày tạo hợp đồng còn trống',
            'contract_date.before_or_equal' => 'Ngày tạo hợp đồng không vượt quá hiện tại',
            //
            'pay_date.after_or_equal' => 'Ngày thanh toán hợp đồng không được ở trong quá khứ ',
            //
            // 'extra_date.integer' => 'Ngày gia hạn phải là số',
            'extra_date.min' => 'Ngày gia hạn phải là số dương',
            //
            'name.required' => 'Tên khách hàng còn trống',
            'name.regex' => 'Tên khách hàng chứa ký tự không hợp lệ',
            'name.max' => 'Tên khách hàng vượt quá số ký tự cho phép',
            //
            'noicap.required' => 'nơi cấp còn trống',
            'noicap.max' => 'nơi cấp vượt quá số ký tự cho phép',
            //
            'identity_card.required' => 'Số chứng minh còn trống',
            'identity_card.digits_between' => 'Độ dài số chứng minh thư không hợp lệ',
            'identity_card.unique' => 'Số chứng minh thư đã tồn tại',
            //
            'identity_date.required' => 'Ngày cấp chứng minh còn trống',
            'identity_date.before_or_equal' => 'Ngày cấp chứng minh không hợp lệ',
            //
            'phone_number.required' => 'Số điện thoại còn trống',
            'phone_number.numeric' => 'Số điện thoại không chứa ký tự là chữ cái',
            'phone_number.digits_between' => 'Độ dài số điện thoại không hợp lệ',
            'phone_number.unique' => 'Số điện thoại đã tồn tại',
            //
            'inhabitant.required' => 'Hộ khẩu còn trống',
            //
            'address.required' => 'Địa chỉ còn trống'
        ]); 
        //nếu hợp đồng là 1 đóng 1 lần thì cần có hạn đóng tiền
        // $date = $request->get('contract_date');
        // $kind = $request->get('contract_kind');
        // if(strtotime($date)!='0000-00-00' && $kind == 1){
        //     $error_mess = "Hợp đồng với tiến độ là 1 cần có ngày ký hợp đồng!";
        //     session()->flash('invalid_notif',$error_mess);
        //     return redirect('admin/contract/create')->withInput();;
        // }else{
            //tạo customer
            
            $flat_name = $request->get('tencanho');
            $flat_id = DB::table('canho')->where('tencanho', $flat_name)->value('idcanho');
            $flat_status = DB::table('canho')->where('idcanho', $flat_id)->value('tinhtrang');
            //nếu căn hộ chưa có ng mua
            if($flat_status == 0){
                $customer = Customer::create();

                $customer->idcanho = $flat_id;
                $customer->hoten = $request->get('name');
                $customer->chungminhthu = $request->get('identity_card');
                $customer->ngaycap = $request->get('identity_date');
                $customer->noicap = $request->get('noicap');
                $customer->sodienthoai = $request->get('phone_number');
                $customer->hokhau = $request->get('inhabitant');
                $customer->diachi = $request->get('address');
                $customer->save();

                //tìm id khách hàng theo số cmnd để lấy id
                $customer_infor = $request->get('identity_card');
                $customer_id = DB::table('khachhang')->where('chungminhthu', $customer_infor)->value('idkhachhang');

                $contract = Contract::create();

                $contract->mahopdong= $request->get('contract_code');
                $contract->idduan= $request->get('project_name');
                $contract->idcanho= $flat_id;
                $contract->idkhachhang= $customer_id;
                $contract->san= $request->get('san');
                $contract->giatri = $request->get('price');
                $contract->ngayky = $request->get('contract_date');;
                $contract->tiendo = $request->get('contract_kind');
                $contract->ngaythanhtoan = $request->get('pay_date');
                $contract->han = $request->get('extra_date');
                $contract->save();
                
                //tìm căn hộ và cập nhật tình trạng "đã có ng mua"
                $flat = Flat::find($flat_id);
                $flat->tinhtrang = 1;
                $flat->save();
                session()->flash('create_notif','Thêm hợp đồng thành công!');
                return redirect('/admin/contract');
                
        }else{
            // session()->flash('invalid_notif','Căn hộ đã có người mua');
            return redirect('/admin/contract/create')->withErrors("Căn hộ đã có người mua")->withInput();
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
        $projects = DB::table('duan')->distinct()->get();
        // $customers_list = DB::table('khachhang')->distinct()->get();

        $contract = Contract::find($id);
        //tìm khách hàng và căn hộ của hợp đồng
        $idkhachhang = $contract->idkhachhang;
        $idcanho = $contract->idcanho;
        //tìm tên khách hàng theo id trong hợp đồng
        $customer = DB::table('khachhang')->where('idkhachhang', $idkhachhang)->first();
        //tìm căn hộ theo id trong hợp đồng
        $flat = DB::table('canho')->where('idcanho',"=",$idcanho)->first();

        return view('admin.contract.edit',compact("contract","customer","flat","projects"));
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
        // |unique:hopdong,mahopdong |unique:hopdong,ten_canho |before_or_equal:date
        $request->validate([
            'san' => 'required',
            'contract_code' => 'required|regex:/^([a-zA-Z0-9\s\-]*)$/|unique:hopdong,mahopdong,' . $id.',idhopdong',
            'contract_date' => 'required|before_or_equal:today',
            'pay_date' => 'required|after_or_equal:today',
            'extra_date' => 'min:0',
            //validate customer infor before insert to db
            'name' => 'required|max:50',
            'noicap' => 'required|max:50',
            'identity_card' => 'required|numeric|digits_between:9,10|unique:hopdong,idkhachhang,' . $id.',idhopdong',
            'identity_date' => 'required|before_or_equal:today',
            'phone_number' => 'required|numeric|digits_between:9,10|unique:hopdong,idkhachhang,' . $id.',idhopdong',
            'inhabitant' => 'required',
            'address' => 'required'
        ],
        [
            'project_name.required' => 'Tên dự án còn trống',
            //
            'tencanho.required' => 'Tên căn hộ còn trống',
            'tencanho.exists' => 'Căn hộ không có sẵn',
            //
            'san.required' => 'Sàn giao dịch còn trống',
            //
            'contract_code.required' => 'Số hợp đồng còn trống',
            'contract_code.regex' => 'Số hợp đồng chứa ký tự không hợp lệ',
            //
            'contract_date.required' => 'Ngày tạo hợp đồng còn trống',
            'contract_date.before_or_equal' => 'Ngày tạo hợp đồng không vượt quá hiện tại',
            //
            'pay_date.required' => 'Ngày thanh toán hợp đồng còn trống',
            'pay_date.after_or_equal' => 'Ngày thanh toán không được ở trong quá khứ',
            // 'extra_date.integer' => 'Ngày gia hạn phải là số',
            'extra_date.min' => 'Ngày gia hạn phải là số dương',

            //customer information start
            'name.required' => 'Tên khách hàng còn trống',
            'name.max' => 'Tên khách hàng vượt quá số ký tự cho phép',
            //
            'noicap.required' => 'nơi cấp còn trống',
            'noicap.max' => 'nơi cấp vượt quá số ký tự cho phép',
            //
            'identity_card.required' => 'Số chứng minh còn trống',
            'identity_card.digits_between' => 'Độ dài số chứng minh thư không hợp lệ',
            //
            'identity_date.required' => 'Ngày cấp chứng minh còn trống',
            'identity_date.before_or_equal' => 'Ngày cấp chứng minh không hợp lệ',
            //
            'phone_number.required' => 'Số điện thoại còn trống',
            'phone_number.numeric' => 'Số điện thoại không chứa ký tự là chữ cái',
            'phone_number.digits_between' => 'Độ dài số điện thoại không hợp lệ',
            //
            'inhabitant.required' => 'Hộ khẩu không được trống',
            //
            'address.required' => 'Địa chỉ không được trống'
        ]); 
            $customer_identity_card = $request->get('identity_card');
            $flat_name = $request->get('tencanho');
            $flat_id = DB::table('canho')->where('tencanho', $flat_name)->value('idcanho');
            $customer_id = DB::table('khachhang')->where('chungminhthu', $customer_identity_card)
            ->value('idkhachhang');
            $customer = Customer::find($customer_id);

            $customer->idcanho = $flat_id;
            $customer->hoten = $request->get('name');
            $customer->chungminhthu = $request->get('identity_card');
            $customer->ngaycap = $request->get('identity_date');
            $customer->noicap = $request->get('noicap');
            $customer->sodienthoai = $request->get('phone_number');
            $customer->hokhau = $request->get('inhabitant');
            $customer->diachi = $request->get('address');
            $customer->save();

            //tìm id khách hàng theo số cmnd để lấy id
            $customer_infor = $request->get('identity_card');
            $customer_id = DB::table('khachhang')->where('chungminhthu', $customer_infor)->value('idkhachhang');
            $contract = Contract::find($id);
            
            $contract->mahopdong= $request->get('contract_code');
            $contract->idduan= $request->get('project_name');
            $contract->idcanho= $flat_id;
            $contract->idkhachhang= $customer_id;
            $contract->san= $request->get('san');
            $contract->giatri = $request->get('price');
            $contract->ngayky = $request->get('contract_date');
            $contract->ngaythanhtoan = $request->get('pay_date');
            $contract->han = $request->get('extra_date');
            $contract->tiendo = $request->get('contract_kind');

            $contract->save();
            session()->flash('update_notif','Chỉnh sửa hợp đồng thành công!');
            return redirect('/admin/contract');
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contract = Contract::find($id);
        $contract->delete();

        session()->flash('delete_notif','Xóa hợp đồng thành công!');
        return redirect('/admin/contract');
    }
}
