<?php
namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\admin\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Flat;
use App\Http\Requests;

use Illuminate\Http\Request;


    class CustomerController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            // $customer = Customer::paginate(2);
            // return view("admin.customer.index", array('model' => $customer));
            $customers = DB::table('khachhang')
                        ->join('canho','khachhang.idcanho','=','canho.idcanho')
                        ->select('khachhang.*','canho.tencanho','canho.idcanho')
                        ->distinct()
                        ->paginate(5);
            return view('admin.customer.index',['customer_array'=>$customers]);
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            if (Gate::allows('admin', Auth::user())){
                //chỉ lấy các căn hộ còn trống
                $flats = DB::table('canho')->where('tinhtrang','0')->get();
                return view("admin.customer.create",['flats'=>$flats]);
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
                'name' => 'required|regex:/^([a-zA-Z\s\-]*)$/|max:50',
                'flat' => 'required',
                'identity_card' => 'required|integer|digits_between:9,10|unique:khachhang,chungminhthu',
                // 'dob' => 'required|before_or_equal:today',
                'email' => 'required|email|unique:khachhang,email',
                'phone_number' => 'required|numeric|digits_between:9,10|unique:khachhang,sodienthoai',
                'inhabitant' => 'required',
                'address' => 'required|max:50'
                //ghi chú có thể để trống 
            ],
            [
                'name.required' => 'Tên khách hàng còn trống',
                'name.regex' => 'Tên khách hàng chứa ký tự không hợp lệ',
                'name.max' => 'Tên khách hàng vượt quá số ký tự cho phép',
                //
                'flat.required' => 'Căn hộ còn trống',
                //
                'identity_card.required' => 'Chứng minh thư còn trống',
                'identity_card.integer' => 'Chứng minh thư không hợp lệ',
                'identity_card.unique' => 'Số chứng minh thư đã tồn tại',
                'identity_card.digits_between' => 'Số chứng minh thư không hợp lệ',//số chứng minh thư p dài từ 9-10 ký tự
                // 'dob.required' => 'Ngày sinh còn trống',
                // 'dob.before_or_equal' => 'Ngày sinh không hợp lệ',
                //
                'email.required' => 'Email còn trống',
                'email.email' => 'Địa chỉ email không hợp lệ',
                'email.unique' => 'Địa chỉ email đã tồn tại',
                //
                'phone_number.required' => 'Số điện thoại còn trống',
                'phone_number.integer' => 'Số điện thoại không hợp lệ',
                'phone_number.digits_between' => 'Số điện thoại không hợp lệ',
                'phone_number.unique' => 'Số điện thoại đã tồn tại',
                //
                'inhabitant.required' => 'Hộ khẩu còn trống',
                //
                'address.required' => 'Địa chỉ còn trống',
                'address.max' => 'Địa chỉ không hợp lệ',
            ]);
                
                $customer = Customer::create();
                
                $customer->hoten= $request->get('name');
                $customer->idcanho= $request->get('flat');
                // $customer->ngaysinh= $request->get('dob');
                $customer->chungminhthu = $request->get('identity_card');
                $customer->email = $request->get('email');
                $customer->sodienthoai = $request->get('phone_number');
                $customer->hokhau = $request->get('inhabitant');
                $customer->diachi = $request->get('address');
                $customer->ghichu = $request->get('note');

                $customer->save();
                
                session()->flash('create_notif','Thêm khách hàng thành công!');
                return redirect('/admin/customer');
        }

        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //$customer = Customer::find($id);
            $customers = DB::table('khachhang')
                        ->join('canho','khachhang.idcanho','=','canho.idcanho')
                        ->select('khachhang.*','canho.tencanho','canho.idcanho')
                        ->where ('idkhachhang','=',$id)
                        ->get();

            return view('admin.customer.show', compact('customers'));
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id){
            if (Gate::allows('admin', Auth::user())){
                $flats = DB::table('canho')->where('tinhtrang','0')->get();
                $customer= Customer::find($id);
                $customer_id = $customer->idkhachhang;
                $flat_id = DB::table('khachhang')->where('idkhachhang', $customer_id)->value('idcanho');
                $flat = Flat::find($flat_id);
                
                return view("admin.customer.edit", compact('customer','flats'));
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
                'name' => 'required|regex:/^([a-zA-Z\s\-]*)$/|max:50',
                'identity_card' => 'required|numeric|digits_between:9,10',
                'email' => 'required|unique:khachhang,email,' . $id.',idkhachhang',
                'phone_number' => 'required|digits_between:9,10|unique:khachhang,sodienthoai,' . $id.',idkhachhang',
                'inhabitant' => 'required',
                'address' => 'required',
                'flat' => 'required|unique:canho,tencanho,' . $id.',idcanho',
            ],
            [
                'name.required' => 'Tên khách hàng còn trống',
                'name.regex' => 'Tên khách hàng chứa ký tự không hợp lệ',
                'name.max' => 'Tên khách hàng vượt quá số ký tự cho phép',
                'identity_card.required' => 'Số chứng minh còn trống',
                'identity_card.numeric' => 'Số chứng minh chứa ký tự không hợp lệ',
                'identity_card.digits_between' => 'Độ dài số chứng minh thư không hợp lệ',
                //
                'email.required' => 'Email còn trống',
                'email.email' => 'Địa chỉ email không hợp lệ',
                'email.unique' => 'Địa chỉ email đã tồn tại',
                //
                'phone_number.required' => 'Số điện thoại còn trống',
                'phone_number.numeric' => 'Số điện thoại không chứa ký tự là chữ cái',
                'phone_number.digits_between' => 'Độ dài số điện thoại không hợp lệ',
                'phone_number.unique' => 'Số điện thoại đã tồn tại',
                //
                'inhabitant.required' => 'Hộ khẩu không được trống',
                //
                'address.required' => 'Địa chỉ không được trống',
                //
                'flat.required' => 'Tên căn hộ còn trống',
                'flat.unique' => 'Căn hộ đã có người mua',
            ]);
                $flat_name = $request->get('flat');
                $flat_id = DB::table('canho')->where('tencanho', $flat_name)->value('idcanho');
                $customer = Customer::find($id);
                
                $customer->hoten= $request->get('name');
                $customer->chungminhthu = $request->get('identity_card');
                $customer->email = $request->get('email');
                $customer->sodienthoai = $request->get('phone_number');
                $customer->hokhau = $request->get('inhabitant');
                $customer->diachi = $request->get('address');
                $customer->ghichu = $request->get('note');
                $customer->save();

                
                // $flat = Flat::find($flat_id);
                // $flat->tinhtrang = 1;
                // $flat->save();

                session()->flash('update_notif','Cập nhật khách hàng thành công!');
                return redirect('/admin/customer');
                
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            $customer_id = DB::table('khachhang')->where('idkhachhang',$id)->value('idkhachhang');
            $contract = DB::table('hopdong')->where('idkhachhang',$customer_id)->first();
            if($contract == null){
                $customer = Customer::find($id);
                $customer->delete();
                session()->flash('delete_notif','Đã xóa khách hàng');
                return redirect('/admin/customer');
            }else{
                session()->flash('invalid_notif','Không thể xóa khách hàng vì hợp đồng còn tồn tại!');
                return redirect('/admin/customer');
            }
            

            
        }
    }