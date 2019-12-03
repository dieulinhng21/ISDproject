<style>
label {
    margin-left: 30px;
    margin-right: 10px;
}
form {
    border: 1px solid #3c8dbc;
    border-radius: 5px;
    padding: 30px 5%;
}
input,select,textarea{
    border:none;
    border-radius:5px;
    text-align:center;
}
button {
    margin-left: 50%;
}
textarea{
    margin-left:30px;
}
input.custom{
    width: 300px;
}
input.custom_address{
    width: 350px;
}
</style>
@extends('partialView.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Quản lý khách hàng
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> AdminAZ</a></li>
        <li><a href="{{ url ('admin/customer') }}">Quản lý khách hàng</a></li>
        <li class="active">Sửa thông tin khách hàng</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
        <div class="container">
        <p>Sửa thông tin khách hàng</p>
            <form role="form" method="POST" action="{{ route('customer.update', $customer->idkhachhang) }}">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <label for="name">Họ và tên</label>
                    <input name="name" type="text" id="name" value="{{$customer->hoten}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"><i>(Họ tên ghi không dấu)</i><br><br>
                
                    <label for="identity_card">CMND</label>
                    <input name="identity_card" type="number" id="identity_card" value="{{$customer->chungminhthu}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')">
                
                    <label>Căn hộ</label>
                    <select name="flat">
                    <!-- hiện ra các căn hộ còn trống -->
                    @foreach($flats as $flat)
                        <option value="{{$flat->idcanho}}">{{$flat->tencanho}}</option>
                    @endforeach
                    </select><br><br>
                
                    <label for="email">Email</label>
                    <input name="email" type="email" class="custom" id="email" value="{{$customer->email}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"oninvalid="this.setCustomValidity('Định dạng email sai')">
                
                    <label for="phone_number">SĐT</label>
                    <input name="phone_number" type="number" id="phone_number" value="{{$customer->sodienthoai}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"><br><br>
                
                    <label for="inhabitant">Hộ khẩu</label>
                    <input name="inhabitant" type="text" class="custom_address" id="inhabitant" value="{{$customer->hokhau}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"><br><br>
                
                    <label>Địa chỉ</label>
                    <input name="address" type="text" class="custom_address" value="{{$customer->diachi}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"><br><br>
                
                    <label for="note">Ghi chú</label><br>
                    <textarea name="note" type="text" name="note" rows="4" cols="50">{{$customer->ghichu}}</textarea><br><br>
                
                    <button type="submit" class="btn btn-primary">Lưu</button>
                
            </form>
        </div>
</section>
<!-- /.content -->
@endsection('content')