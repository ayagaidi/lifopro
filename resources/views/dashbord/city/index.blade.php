@extends('layouts.app')
@section('title',trans('app.city'))

@section('content')
<div class="row small-spacing">
    <div class="col-md-12">
        <div class="box-content">
                
                <a type="button" href="{{ route('cities/create') }}" class="btn btn-primary btn-bordered waves-effect waves-light col-sm-3 ">{{trans('city.add')}}</a>

    </div>
    </div>
    <div class="row small-spacing">
        <div class="col-md-12">
            <div class="box-content ">
                <h4 class="box-title">{{trans('city.showcity')}}</h4>
                <div class="table-responsive" data-pattern="priority-columns">
                <table id="datatable1" class="table table-bordered table-hover js-basic-example dataTable table-custom " style="cursor: pointer;">
                    <thead>
                        <tr>
                            <th>المنطقة</th>

                            <th>{{trans('city.name_table')}}</th>
                           
                            <th>{{trans('city.create_date_table')}}</th>


 <th></th>

 

             
                             

                        </tr>
                    </thead>
                    <tbody>
                        <script>
          
                          $(document).ready( function () {
                         
                             $('#datatable1').dataTable({
                               "language": {
                                "url": "{{asset('Arabic.json')}}" //arbaic lang
                         
                                   },
                                   "lengthMenu":[10,15,20,50,100],
                                   "bLengthChange" : true, //thought this line could hide the LengthMenu
                           serverSide: false,
                           paging: true,
                             searching: true,
                             ordering: true,
                             contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                             ajax: '{!! route('cities/cities')!!}',
                          
                          columns: [
                            { data: 'regions.name'},
                                   { data: 'name'},

                                   {data: 'created_at'},

                                   {data: 'edit'}  ,
 
 
                                ],

                                 dom: 'Blfrtip',

buttons: [
{
extend: 'copyHtml5',
exportOptions: {
columns: [ ':visible' ]
},
text:'نسخ'
},
{
extend: 'excelHtml5',
exportOptions: {
columns: ':visible'
},
text:'excel تصدير كـ '

},
{
extend:  'colvis',
text:'الأعمدة'

},
],
                         
                             });
                         
                           });
                         </script>
                      </tbody>
                   
                </table>
                </div>
            </div>
        </div>
      
    </div>
</div>

@endsection