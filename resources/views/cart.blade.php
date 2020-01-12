@extends('layout')
 <style type="text/css">
     .button1 {background-color: #4CAF50;} /* Green */

 </style>
@section('title', 'Cart')
 
@section('content')
 
    <table id="cart" class="table table-hover table-condensed">
        <thead>
        <tr>
            <th style="width:40%">Product</th>
            <th style="width:10%">Price</th>
            <th style="width:10%">Quantity</th>
            <th style="width:10%">Promocode</th>
            <th style="width:10%"></th>

            <th style="width:10%" class="text-center">Subtotal</th>
            <th style="width:10%"></th>
        </tr>
        </thead>
        <tbody>
 
        <?php $total = 0;$discounted_price=0;?>
 
        @if(session('cart'))
            @foreach(session('cart') as $id => $details)
 
                <?php 

                $discount=$details['discount'] ;
                $total += $details['price'] * $details['quantity'];
                // if($details['discount'])
                $discounted_price = $total - ($total * $discount / 100);
                 ?>

                <tr>
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"><img src="{{ $details['photo'] }}" width="100" height="100" class="img-responsive"/></div>
                            <div class="col-sm-9">
                                <h4 class="nomargin">{{ $details['name'] }}</h4>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">Rs {{ $details['price'] }}</td>
                    <td data-th="Quantity">
                        <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity" />
                    </td>
                    <td> <input type="text" id="promo_code" value="" class="form-control promocode" />
                       
                    </td>
                    <td>
                        <!--  <button onclick="applyPromo()" class="button1">Apply Promocode</button> -->
                         <button class="btn btn-info btn-sm button1 apply-promo" data-id="{{ $id }}">Apply Promo</button>
                     </td>
                    <td data-th="Subtotal" class="text-center">Rs {{ $details['price'] * $details['quantity'] }}</td>
                    <td class="actions" data-th="">
                        <button class="btn btn-info btn-sm update-cart" data-id="{{ $id }}"><i class="fa fa-refresh"></i></button>
                        <button class="btn btn-danger btn-sm remove-from-cart" data-id="{{ $id }}"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
            @endforeach
        @endif
 
        </tbody>
        <tfoot>
        <tr class="visible-xs">
            <td class="text-center"><strong>Total {{ $total }}</strong></td>
            <td><strong>After Applying Promo {{ $discounted_price }}</strong></td>

        </tr>
        <tr>
            <td><a href="{{ url('/') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
            <td colspan="2" class="hidden-xs"></td>
            <td class="hidden-xs text-center"><strong>Total Rs {{ $discounted_price }}</strong></td>
        </tr>
        </tfoot>
    </table>
 
@endsection

@section('scripts')
 
 
    <script type="text/javascript">
 
        $(".update-cart").click(function (e) {
           e.preventDefault();
 
           var ele = $(this);
 
            $.ajax({
               url: '{{ url('update-cart') }}',
               method: "patch",
               data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), quantity: ele.parents("tr").find(".quantity").val()},
               success: function (response) {
                   window.location.reload();
               }
            });
        });

         $(".apply-promo").click(function (e) {

           e.preventDefault();
 
           var ele = $(this);
 
            $.ajax({
               url: '{{ url('apply-promo') }}',
               method: "patch",
               data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), promocode: ele.parents("tr").find(".promocode").val()},
               success: function (response) {
                   window.location.reload();
               }
            });
        });
 
        $(".remove-from-cart").click(function (e) {
            e.preventDefault();
 
            var ele = $(this);
 
            if(confirm("Are you sure")) {
                $.ajax({
                    url: '{{ url('remove-from-cart') }}',
                    method: "DELETE",
                    data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id")},
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }
        });
 
    </script>
 
@endsection