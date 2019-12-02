<style>
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
label {
    margin-left: 30px;
    margin-right: 10px;
}
input.detail{
    width: 50px;
}
button {
    margin-left: 50%;
}
</style>
@extends('partialView.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Quản lý căn hộ
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> AdminAZ</a></li>
        <li><a href="{{ url ('admin/flat') }}">Quản lý căn hộ</a></li>
        <li class="active">Thêm căn hộ </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="container">
    <p>Thêm căn hộ</p>
        <form method="POST" action="{{ route('flat.store') }}">
        {{ csrf_field() }}
        
        @if ($errors->any())
            <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
            </div>
        @endif
            <label>Tên căn hộ</label>
            <input type="text" name="flat" value="{{ old('flat') }}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')">

            <label for="project">Tên dự án</label>
            <select name="project_name" id="project_name">Dự án: 
            @if(isset($projects))
                @foreach($projects as $project)
                    <option value="{{$project->idduan}}">{{$project->tenduan}}</option>
                @endforeach
            @endif
            </select>
            
            <label for="apartment">Tên chung cư</label>
            <!-- take array from create function in FlatController -->
            <select name="apartment" value="{{ old('apartment') }}">
                @foreach($apartments as $apartment)
                    <option value="{{$apartment->idtoachungcu}}">{{$apartment->tentoa}}</option>
                @endforeach
            </select><br><br>

            <label>Giá trị</label>
            <input type="number" name="price" value="{{ old('price') }}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"><br><br>

            
            
            <label>Diện tích</label>
            <input class="detail" type="number" name="square" value="{{ old('square') }}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"> 
            mét vuông <br><br>
            <label>Chi tiết</label>
                <input class="detail" type="number" name="bedroom" value="{{ old('bedroom') }}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"> phòng ngủ - 
                <input class="detail" type="number" name="bathroom" value="{{ old('bathroom') }}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"> phòng vệ sinh
                - 1 phòng khách - 1 phòng bếp - 
            <br><br>

            <label for="status">Tình trạng:</label>
            <select name="status" id="status">
                <option value="0">Còn trống</option>
                <option value="2">Đã đặt cọc</option>
                <option value="1">Đã có người mua</option>
            </select><br><br>
            
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="reset" class="btn btn-primary">Làm mới trang</button>
        </form>
    </div>
</section>
<!-- /.content -->
@endsection('content')