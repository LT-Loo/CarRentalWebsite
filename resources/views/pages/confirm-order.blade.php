<!-- Order Confirmation Page -->

@extends("layout.master")

@section("css")
<link rel = "stylesheet" type = "text/css" href =  "{{ asset('css/confirm-order.css') }}">
@endsection

@section("content")
<script type = "text/javascript">
    // Clear session storage
    $(document).ready(function() {
        sessionStorage.clear();
    });
</script>

<div class = "container m-auto">
    <h2 class = "title-font">Order Confirmation</h2>
    <hr class = "mt-0">
    <!-- Order Details -->
    <div class = "row" id = "car-info">
        <div class = "col-4 px-0">
            <img src = "{{ asset($car['image']) }}" class = "card-img-top object-fit-scale border-bottom" height = "160">
        </div>
        <!-- Car Information -->
        <div class = "col">
            <div class = "row m-auto">
                <div class = "col">
                    <h4 class = "text-break text-capitalize mb-0">{{ $car['brand'] }} {{ $car['model'] }}</h4>
                    <p class = "text-body-secondary mb-3">{{ $car['type'] }}</p>
                </div>
                <div class = "col-6 text-end pe-2">
                    <h4 class = "mb-0 d-inline">${{ number_format($car['price_per_day'], 2) }}</h4>
                    <p class = "text-body-secondary mb-2 ms-1 d-inline">per day</p>
                </div>
            </div>
            <div class = "row m-auto m-0 p-0">
                <p class = "mb-3">{{ $car['description'] }}</p>
            </div>
            <div class = "row m-0 p-0 m-auto justify-content-between">
                <div class = "col-lg-4 col-md-5">
                    <p class = "mb-0"><i class = "bi bi-person-fill me-2"></i>{{ $car['seats'] }} seats</p>
                    <p class = "mb-0"><i class = "bi bi-speedometer2 me-2"></i>{{ $car['mileage'] }} mileage</p>
                    <p class = "mb-2"><i class = "bi bi-fuel-pump-fill me-2"></i>{{ $car['fuel_type'] }}</p>
                </div>
                <!-- Quantity, Dates & Price -->
                <div class = "col-5">
                    <p class = "mb-0">Quantity: {{ $order->quantity }}</p>
                    <p class = "mb-0">Start Date: {{ $order->start_date }}</p>
                    <p class = "mb-0">End Date: {{ $order->end_date }}</p>
                </div>
            </div>
            <div class = "row text-end mt-3 m-auto">
                <div class = "col">
                    <h4 class = "d-inline me-1">Total Price</h4>
                    <p id = "days" class = "d-inline me-2">for {{ $order->days }} day(s)</p>
                    <h4 id = "total_price" class = "d-inline">${{ $order->total_price }}</h4>
                </div>
            </div>
            <hr>
            <!-- Customer Information -->
            <div class = "row m-auto m-0 p-0">
                <h3 class = "mb-0">Customer Information</h3>
                <p class = "mb-2 text-secondary">Order ID: {{ $order->id }}</p>

                <p class = "mb-0">Name: {{ $order->customer_name }}</p>
                <p class = "mb-0">Email: {{ $order->email }}</p>
                <p class = "mb-0">Contact: {{ $order->phone }}</p>
                <p class = "mb-0 text-capitalize">Valid License: {{ $order->license }}</p>
            </div>
        </div>
        <!-- Order Confirmation Button -->
        <div class = "row m-auto">
            <div class = "col text-end">
                <a type = "button" id = "confirm-order-btn" class = "btn btn-primary" href = '{{ url("confirm-order/$order->id") }}'>CONFIRM ORDER</a>
            </div>
        </div>
    </div>
    <hr>
</div>
@endsection
