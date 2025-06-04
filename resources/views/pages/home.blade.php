<!-- Home Page -->

@extends("layout.master")

@section("css")
<link rel = "stylesheet" type = "text/css" href =  "{{ asset('css/home.css') }}">
@endsection

@section("content")

@if (!empty(Session::get('unavailable')))
<script type = "text/javascript">
    // Display alert message when car reserved is unavailable
    $(document).ready(function() {
        $('#alert-message').alert('show');
    });
</script>
@endif

<!-- Grid view of cars -->
<div class = "container m-auto" id = "car-grid">
    @if (!Session::has("keyword"))
    <!-- All Cars -->
    <div class = "row row-cols-lg-3 row-cols-md-2" id = "all-cars">
        @foreach ($car_types as $type)
            @foreach ($type as $car)
            <div class = "col mb-3" id = "car{{ $car['id'] }}">
                <div class = "card h-100">
                    <img src = "{{ asset($car['image']) }}" class = "card-img-top object-fit-scale border-bottom" height = "160">
                    <div class = "row py-2 m-auto">
                        <div class = "row m-0 p-0 m-auto">
                            <div class = "col">
                                <h5 class = "title-card text-break text-capitalize mb-0">{{ $car['brand'] }} {{ $car['model'] }}</h5>
                                <p class = "text-body-secondary mb-2">{{ $car['type'] }}</p>
                            </div>
                            <div class = "col-4 text-end pe-2">
                                <h5 class = "mb-0">${{ number_format($car['price_per_day'], 2) }}</h5>
                                <p class = "text-body-secondary mb-2">per day</p>
                            </div>
                        </div>
                        <div class = "row m-0 p-0 m-auto">
                            <div class = "col">
                                <p class = "text-body-secondary mb-0"><i class = "bi bi-person-fill me-2"></i>{{ $car['seats'] }} seats</p>
                                <p class = "text-body-secondary mb-0"><i class = "bi bi-speedometer2 me-2"></i>{{ $car['mileage'] }} mileage</p>
                                @if ($car['quantity'] > 0)
                                <span class = "fs-6 text-success w-100 py-2"><i class = "bi bi-check2-circle me-2"></i>Availabillity: Yes</span>
                                @else
                                <span class = "fs-6 text-danger text-end py-2"><i class = "bi bi-x-circle me-2"></i>Availabillity: No</span>
                                @endif
                            </div>
                            <div class = "col text-end position-relative">
                                <div class = "position-absolute bottom-0 end-0 pe-2">
                                    <form method = "post" action = "{{ url('reservation') }}">
                                        @csrf
                                        <input id = "new_rent" name = "new_rent" value = true hidden>
                                        <input id = "car_id" type = "number" hidden name = "car_id" value = "{{ $car['id'] }}">
                                        <button id = "rent-btn" class = "btn btn-success py-1 px-3 fw-semibold" type = "submit" {{ $car['quantity'] <= 0 ? 'disabled': '' }} >RENT</button>
                                    </form>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>

    <!-- Car Type -->
    @foreach ($car_types as $key => $type)
    <div class = "row row-cols-lg-3 row-cols-md-2" id = "type-{{ $key }}" hidden>
        @foreach ($type as $car)
        <div class = "col mb-3" id = "car{{ $car['id'] }}">
            <div class = "card h-100">
                <img src = "{{ asset($car['image']) }}" class = "card-img-top object-fit-scale border-bottom" height = "160">
                <div class = "row py-2 m-auto">
                    <div class = "row m-0 p-0 m-auto">
                        <div class = "col">
                            <h5 class = "title-card text-break text-capitalize mb-0">{{ $car['brand'] }} {{ $car['model'] }}</h5>
                            <p class = "text-body-secondary mb-2">{{ $car['type'] }}</p>
                        </div>
                        <div class = "col-4 text-end pe-2">
                            <h5 class = "mb-0">${{ number_format($car['price_per_day'], 2) }}</h5>
                            <p class = "text-body-secondary mb-2">per day</p>
                        </div>
                    </div>
                    <div class = "row m-0 p-0 m-auto">
                        <div class = "col">
                            <p class = "text-body-secondary mb-0"><i class = "bi bi-person-fill me-2"></i>{{ $car['seats'] }} seats</p>
                            <p class = "text-body-secondary mb-0"><i class = "bi bi-speedometer2 me-2"></i>{{ $car['mileage'] }} mileage</p>
                            @if ($car['quantity'] > 0)
                            <span class = "fs-6 text-success w-100 py-2"><i class = "bi bi-check2-circle me-2"></i>Availabillity: Yes</span>
                            @else
                            <span class = "fs-6 text-danger text-end py-2"><i class = "bi bi-x-circle me-2"></i>Availabillity: No</span>
                            @endif
                        </div>
                        <div class = "col text-end position-relative">
                            <div class = "position-absolute bottom-0 end-0 pe-2">
                                <form method = "post" action = "{{ url('reservation') }}">
                                    @csrf
                                    <input id = "new_rent" name = "new_rent" value = true hidden>
                                    <input id = "car_id" type = "number" hidden name = "car_id" value = "{{ $car['id'] }}">
                                    <button id = "rent-btn" class = "btn btn-success py-1 px-3 fw-semibold" type = "submit" {{ $car['quantity'] <= 0 ? 'disabled': '' }} >RENT</button>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach

    <!-- Car Brand -->
    @foreach ($car_brands as $key => $brand)
    <div class = "row row-cols-lg-3 row-cols-md-2" id = "brand-{{ $key }}" hidden>
        @foreach ($brand as $car)
        <div class = "col mb-3" id = "car{{ $car['id'] }}">
            <div class = "card h-100">
                <img src = "{{ asset($car['image']) }}" class = "card-img-top object-fit-scale border-bottom" height = "160">
                <div class = "row py-2 m-auto">
                    <div class = "row m-0 p-0 m-auto">
                        <div class = "col">
                            <h5 class = "title-card text-break text-capitalize mb-0">{{ $car['brand'] }} {{ $car['model'] }}</h5>
                            <p class = "text-body-secondary mb-2">{{ $car['type'] }}</p>
                        </div>
                        <div class = "col-4 text-end pe-2">
                            <h5 class = "mb-0">${{ number_format($car['price_per_day'], 2) }}</h5>
                            <p class = "text-body-secondary mb-2">per day</p>
                        </div>
                    </div>
                    <div class = "row m-0 p-0 m-auto">
                        <div class = "col">
                            <p class = "text-body-secondary mb-0"><i class = "bi bi-person-fill me-2"></i>{{ $car['seats'] }} seats</p>
                            <p class = "text-body-secondary mb-0"><i class = "bi bi-speedometer2 me-2"></i>{{ $car['mileage'] }} mileage</p>
                            @if ($car['quantity'] > 0)
                            <span class = "fs-6 text-success w-100 py-2"><i class = "bi bi-check2-circle me-2"></i>Availabillity: Yes</span>
                            @else
                            <span class = "fs-6 text-danger text-end py-2"><i class = "bi bi-x-circle me-2"></i>Availabillity: No</span>
                            @endif
                        </div>
                        <div class = "col text-end position-relative">
                            <div class = "position-absolute bottom-0 end-0 pe-2">
                                <form method = "post" action = "{{ url('reservation') }}">
                                    @csrf
                                    <input id = "new_rent" name = "new_rent" value = true hidden>
                                    <input id = "car_id" type = "number" hidden name = "car_id" value = "{{ $car['id'] }}">
                                    <button id = "rent-btn" class = "btn btn-success py-1 px-3 fw-semibold" type = "submit" {{ $car['quantity'] <= 0 ? 'disabled': '' }} >RENT</button>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach

    <!-- Search Result -->
    @else
    <div class = "row row-cols-lg-3 row-cols-md-2">
        @foreach (Session::get('search_results') as $car)
        <div class = "col mb-3" id = "car{{ $car['id'] }}">
            <div class = "card h-100">
                <img src = "{{ asset($car['image']) }}" class = "card-img-top object-fit-scale border-bottom" height = "160">
                <div class = "row py-2 m-auto">
                    <div class = "row m-0 p-0 m-auto">
                        <div class = "col">
                            <h5 class = "title-card text-break text-capitalize mb-0">{{ $car['brand'] }} {{ $car['model'] }}</h5>
                            <p class = "text-body-secondary mb-2">{{ $car['type'] }}</p>
                        </div>
                        <div class = "col-4 text-end pe-2">
                            <h5 class = "mb-0">${{ number_format($car['price_per_day'], 2) }}</h5>
                            <p class = "text-body-secondary mb-2">per day</p>
                        </div>
                    </div>
                    <div class = "row m-0 p-0 m-auto">
                        <div class = "col">
                            <p class = "text-body-secondary mb-0"><i class = "bi bi-person-fill me-2"></i>{{ $car['seats'] }} seats</p>
                            <p class = "text-body-secondary mb-0"><i class = "bi bi-speedometer2 me-2"></i>{{ $car['mileage'] }} mileage</p>
                            @if ($car['quantity'] > 0)
                            <span class = "fs-6 text-success w-100 py-2"><i class = "bi bi-check2-circle me-2"></i>Availabillity: Yes</span>
                            @else
                            <span class = "fs-6 text-danger text-end py-2"><i class = "bi bi-x-circle me-2"></i>Availabillity: No</span>
                            @endif
                        </div>
                        <div class = "col text-end position-relative">
                            <div class = "position-absolute bottom-0 end-0 pe-2">
                                <form method = "post" action = "{{ url('reservation') }}">
                                    @csrf
                                    <input id = "new_rent" name = "new_rent" value = true hidden>
                                    <input id = "car_id" type = "number" hidden name = "car_id" value = "{{ $car['id'] }}">
                                    <button id = "rent-btn" class = "btn btn-success py-1 px-3 fw-semibold" type = "submit" {{ $car['quantity'] <= 0 ? 'disabled': '' }} >RENT</button>
                                </form>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@include("layout.alert");

@endsection