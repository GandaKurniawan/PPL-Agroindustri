@extends('master.app')

@section('title')

Pendapatan

@stop

@section('content')

<section class="section ">
    <form action="{{Route('cari')}}" method="GET" >
      <div class="row">
          <select class="custom-select  col-md-2" name="bulan">
            <option selected value="" disabled>Bulan</option>
            <option value="1">Januari</option>
            <option value="2">Februari</option>
            <option value="3">Maret</option>
            <option value="4">April</option>
            <option value="5">Mei</option>
            <option value="6">Juni</option>
            <option value="7">Juli</option>
            <option value="8">Agustus</option>
            <option value="9">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
          </select>
          <input type="number" name="tahun" style="margin-left: 5px"  class="form-control col-md-2" placeholder="Tahun">
      <button type="submit" class="btn btn-success " style="margin-left: 10px">Cari</button>
      </div>
    </form>
    <div class="section-body">
      <div class="row mt-sm-4">
        <div class=" col-md">
          <div class="card">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">id</th>
                  <th scope="col">Username</th>
                  <th scope="col">Berat Bersih</th>
                  <th scope="col">Berat Kotor</th>
                  <th scope="col">Tanggal Input</th>
                  <th scope="col">Tanggal Edit</th>
                  <th scope="col">Status</th>
                  @if(Auth::user()->role_id != 1 )
                  <th scope="col">Aksi</th>
                  @endif
                  @if(Auth::user()->role_id == 2 )
                  <th scope="col">Verifikasi</th>
                  @endif
                  
                  
                  
                </tr>
              </thead>
              <tbody>
                @php
                    $i =1;
                    $total = 0;
                    $total2=0
                @endphp
                @forelse ($data_pendapatan as $pendapatan)
                
                <tr>
                  <th scope="row">{{$i}}</th>
                  <td>{{$pendapatan->user->name}}</td>
                  <td>{{$pendapatan->berat_bersih}}</td>
                  <td>{{$pendapatan->berat_kotor}}</td>
                <td>{{$pendapatan->tanggal->format('Y/m/d')}}</td>
                <td>{{$pendapatan->updated_at->format('Y/m/d')}}</td>
                <td>{{$pendapatan->confirm->name_confirm}}</td>
                @if(Auth::user()->role_id != 1 )
                <td><a href="{{Route('editPendapatan',[$pendapatan->id])}}"><button  class="btn btn-primary">Edit</button></a></td>
                @endif
                @if(Auth::user()->role_id == 2 && $pendapatan->confirm_id == 1  )
                <td><button type="button" class="btn btn-success float-right mb-1" data-toggle="modal" data-target="#modalpendapatanKaret{{$pendapatan->id}}">Konfirmasi</button></td>
                @elseif(Auth::user()->role_id == 2 && $pendapatan->confirm_id == 2)
                <td><button type="button" disabled class="btn btn-success float-right mb-1" data-toggle="modal" data-target="#modalpendapatanKaret{{$pendapatan->id}}">Konfirmasi</button></td>
                @endif
                </tr>
                @php
                 $i++;
                   $total_kotor=$pendapatan->berat_kotor+$total;
                   $total=$total+$pendapatan->berat_kotor; 
                   $total_bersih=$pendapatan->berat_bersih+$total2;
                   $total2=$total2+$pendapatan->berat_bersih; 
                    
                @endphp
                @empty
                <h1>Data Tidak Ditemukan</h1>
                @endforelse
                @if (empty($total_kotor))
        
        
                @elseif($total_kotor!=0)
                <table class="table table-striped"  width ="50px" >
                  <strong>

                    <th scope ="col" >Total  Berat Kotor</th>
                    <th scope="col" style="text-align: right">{{$total_kotor}}</th>
                  </strong>
                </table>
                <table class="table table-striped"  width ="50px" >
                  <strong>

                    <th scope ="col" >Total  Berat Bersih</th>
                    <th scope="col" style="text-align: right">{{$total_bersih}}</th>
                  </strong>
                </table>
                
                @endif

                
              </tbody>
            </table>
            @if(Auth::user()->role_id == 3 )
            <div class="card-footer text-right">
              <a href="{{Route('tambahPendapatan',[Auth::user()->id])}}"><button  class="btn btn-primary">Tambah Data</button></a>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    
    {{$data_pendapatan->links()}}
  </section>
  @foreach ($data_pendapatan as $data)
            
  <!-- Modal -->
<div class="modal fade" id="modalpendapatanKaret{{$data->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
  <h5 class="modal-title">Konfirmasi Data Pendapatan</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
  </div>
  <div class="modal-body">
  <!--FORM UPDATE BARANG-->
  <form method="post" action="{{Route('konfirmasi',[$data->id])}}" class="needs-validation" novalidate="">
    {{ csrf_field() }}
              <div class="card-body">
                <div class="row">
                  <div class="form-group col-md ">
                    <label>Tanggal</label>
                    <input id="beratKotor" type="date" name = 'beratKotor' class="form-control" value="{{$data->tanggal}}" required="" readonly>
                    <div class="invalid-feedback">
                      Please fill in the 
                    </div>
                  </div>
                </div>
                  <div class="row">
                    <div class="form-group col-md ">
                      <label>Berat Bersih</label>
                      <input id="beratBersih" name ="beratBersih"type="number" class="form-control" value="{{$data->berat_bersih}}" required="" readonly>
                      <div class="invalid-feedback">
                        Please fill in the first name
                      </div>
                    </div>
                    <div class="form-group">
                      <input id="userId" name ='userId'type="text" class="form-control" value="{{$data->user_id}}" required="" hidden>
                      <div class="invalid-feedback">
                        Please fill in the first name
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md ">
                      <label>Berat Kotor</label>
                      <input id="beratKotor" type="number" name = 'beratKotor' class="form-control" value="{{$data->berat_kotor}}" required="" readonly>
                      <div class="invalid-feedback">
                        Please fill in the 
                      </div>
                    </div>
                  </div>
              </div>
              <div class="modal-footer ">
                <button class="btn btn-primary">Konfirmasi</button>
              </div>
            </form>
</div>
</div>
</div>
</div>
    
@endforeach
@endsection