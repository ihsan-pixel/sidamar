@extends('backend.layout.base')

@section('content')
    <div class="row">
        <div class="col-mb-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="font-size: 40px">{{ $title }}</h5>
                </div>
                <div class="card-body">



                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label" for="blok">Nama /eWanuGK/KARTANU</label>
                                <select class="form-control selectpicker" data-actions-box="true"
                                    data-virtual-scroll="false" data-live-search="true" name="user_id" id="user_id"
                                    onchange="tampil_data()">
                                    <option value="" selected>-- Pilih --</option>
                                    @foreach ($siswa as $s)
                                        <option value="{{ $s->id }}">{{ $s->nama_lengkap }} - {{ $s->nis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div id="open" class="position-absolute top-0 start-50 translate-middle"
                                style="margin-top: 5%">
                                <div id="loading-image" class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <br>
                            <button onclick="printTunggakan()" id="cetakexcel" class="btn btn-success">Excel</button>
                            <a href="/tunggakan" type="button" class="btn btn-danger">refresh</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card col-mb-12">
                <div class="card table-responsive">

                    <div class="container mt-4 ">
                        <table id="datatable" class="table table-striped ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tahun</th>
                                    <th>Pembayaran</th>
                                    <th>Tagihan</th>

                                </tr>
                            </thead>
                            <tbody id="show_data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            tampil_data();
            $("#loading-image").hide();
            function formatNumber(val) {
                let round = (val.toString() / 1).toFixed(0);
                let value = round.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                return "Rp. " + value;
            };
            function tampil_data() {

                // console.log($("#thajaran_id").val());
                $.ajax({
                    type: 'GET',
                    url: '{{ route('tunggakan.load_data') }}',
                    async: true,
                    data: {
                        user_id: $("#user_id").val(),

                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $("#loading-image").show();
                        $('#cetakexcel').attr('disabled', true)
                    },
                    success: function(data) {
                        var html = '';
                        var i;
                        var no = 1;
                        for (i = 0; i < data.length; i++) {
                            html += '<tr>' +
                                '<td>' + no++ + '</td>' +
                                '<td>' + data[i].nama_lengkap + '</td>' +
                                '<td>' + data[i].tahun + '</td>' +
                                '<td>' + data[i].pembayaran + '</td>' +
                                '<td>' +  formatNumber(data[i].tunggakan) + '</td>' +
                                '</tr>';
                        }
                        $('#show_data').html(html);
                        $("#loading-image").hide();
                        $('#cetakexcel').removeAttr('disabled');
                        $('#datatable').DataTable();

                    }
                });
            }

            function printTunggakan() {
                if ($('#user_id').val() == "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'User tidak boleh kosong!!!',
                    })
                } else {
                    $.ajax({
                        type: "GET",
                        dataType: 'json',
                        url: "{{ url('cetakTunggakan') }}/",
                        data: {
                            user_id: $("#user_id").val(),
                        },

                        success: function(response) {
                            // console.log(response.file);
                            window.open(response.file, '_blank');

                        },
                        error: function() {
                            alert("error");
                        }
                    });
                    return false;
                }
            }
        </script>
    @endsection
