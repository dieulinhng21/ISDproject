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
            <li class="active">Sửa thông tin căn hộ</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
        <p>Sửa thông tin căn hộ</p>
            <form method="POST" action="{{ route('flat.update', $flat->idcanho) }}">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            @if ($errors->any())
                <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
                </div>
            @endif

            <label>Tên căn hộ</label>               
            <input name="flat" type="text" value="{{$flat->tencanho}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')">

            <label for="project">Tên dự án</label>
            <!-- $available_project_list from edit function in FlatController -->
            <select name="project_name" id="project_name">Dự án: 
            @if(isset($projects))
                @foreach($projects as $project)
                    <option value="{{$project->idduan}}">{{$project->tenduan}}</option>
                @endforeach
            @endif
            </select>

            <label for="apartment">Tên chung cư</label>
            <select name="apartment">
            @foreach($apartments as $apartment)
                    <option value="{{$apartment->idtoachungcu}}">{{$apartment->tentoa}}</option>
            @endforeach
            </select><br><br>
        
            <label>Giá trị</label>
            <input name="price" type="number" value="{{$flat->giatri}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"><br><br>
        
            
            <label>Diện tích</label>
            <input class="detail" name="square" type="number" value="{{$flat->dientich}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"> mét vuông<br><br>
            <label>Chi tiết</label>
            <input class="detail" name="bedroom" type="number" value="{{$flat->sophongngu}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"> phòng ngủ
            - 
            <input class="detail" name="bathroom" type="number" value="{{$flat->sophongvesinh}}" required oninvalid="this.setCustomValidity('Chưa nhập thông tin')"> phòng vệ sinh
            - 1 phòng khách - 1 phòng bếp<br><br>
            
            <label>Tình trạng:</label>
            @if($flat->tinhtrang == 1)
            <select name="status">
                <option value="1" selected>Đã có người mua</option>
                <option value="1">Đã đặt cọc</option>
                <option value="0">Còn trống</option>
            </select><br><br> 
            @elseif($flat->tinhtrang == 0)
            <select name="status">
                <option value="1">Đã có người mua</option>
                <option value="1">Đã đặt cọc</option>
                <option value="0" selected>Còn trống</option>
            </select><br><br>
            @elseif($flat->tinhtrang == 2)
            <select name="status">
                <option value="1">Đã đặt cọc</option>
                <option value="1">Đã có người mua</option>
                <option value="0" selected>Còn trống</option>
            </select><br><br>
            @endif
            
            <button type="submit" class="btn btn-primary">Lưu</button>
            </form>
        </div>
    </section>
<!-- /.content -->
@endsection('content')