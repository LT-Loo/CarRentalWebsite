<!-- Reservation Page -->

@extends("layout.master")

@section("css")
<link rel = "stylesheet" type = "text/css" href =  "{{ asset('css/reservation.css') }}">
@endsection

@section("content")
<script type = "text/javascript">
    $(document).ready(function() {
        var car;
        var customer = {};
        var validDates = false;
        var reserve_state = "{{ $reserve_state }}";

        // Retrieve and display saved information
        if (sessionStorage.length > 0 && reserve_state == "reserve") {
            if (sessionStorage.getItem("car") != null) {
                car = JSON.parse(sessionStorage.getItem("car"));
                customer.car_id = car.id;
                customer.price_per_day = car.price_per_day;
                customer.quantity = 1;
                $("#quantity").text(customer.quantity);
                $("#form_quantity").val(customer.quantity);
            }

            if (sessionStorage.getItem("customer") != null) {
                customer = JSON.parse(sessionStorage.getItem("customer"));
                $("#quantity").text(customer.quantity);
                $("#form_quantity").val(customer.quantity);
                if (customer.hasOwnProperty("start_date")) {
                    $("#start_date").val(moment(customer.start_date).format('YYYY-MM-DD'));
                    if (customer.hasOwnProperty("end_date")) {
                        $("#end_date").val(moment(customer.end_date).format('YYYY-MM-DD'));
                        calculateDaysNPrice();
                    }
                }
                if (customer.hasOwnProperty("name")) {$("#customer_name").val(customer.name);}
                if (customer.hasOwnProperty("email")) {$("#email").val(customer.email);}
                if (customer.hasOwnProperty("phone")) {$("#contact").val(customer.phone);}
                if (customer.hasOwnProperty("license")) {
                    $("#form_license").val(customer.license);
                    if (customer.license) {$("#license").prop("checked", true);}
                    else {$("#license").prop("checked", false);}
                }
            }
            // Clear storage and retrieve car information for new rental
        } else if (reserve_state == "new") { 
            if (sessionStorage.length > 0) {sessionStorage.clear();}
            $.ajax({
                url: "{{ url('/get-car') }}",
                type: "GET",
                success: function(response) {
                    car = response;
                    sessionStorage.setItem("car", JSON.stringify(response));
                    customer.car_id = car.id;
                    customer.price_per_day = car.price_per_day
                    customer.quantity = 1
                    sessionStorage.setItem("customer", JSON.stringify(customer));
                    $("#quantity").text(customer.quantity);
                    $("#form_quantity").val(customer.quantity);
                    console.log(response);
                }
            });
        }

        // Decrement quantity
        $("#decrement-btn").click(function() {
            if (customer.quantity > 1) {
                customer.quantity -= 1;
                sessionStorage.setItem("customer", JSON.stringify(customer));
                $("#quantity").text(customer.quantity);
                $("#form_quantity").val(customer.quantity);
                if ($("#end_date").val() && $("#start_date").val()) {
                    calculateDaysNPrice();
                }
            }
        });

        // Increment quantity
        $("#increment-btn").click(function() {
            if (customer.quantity >= 1) {
                customer.quantity += 1;
                sessionStorage.setItem("customer", JSON.stringify(customer));
                $("#quantity").text(customer.quantity);
                $("#form_quantity").val(customer.quantity);
                if ($("#end_date").val() && $("#start_date").val()) {
                    calculateDaysNPrice();
                }
            }
        });

        // Calculate days and total price
        function calculateDaysNPrice() {
            var start_date = new Date($("#start_date").val());
            var end_date = new Date($("#end_date").val());
            var milli_secs = end_date.getTime() - start_date.getTime();
            if (milli_secs >= 0) {
                var days = Math.round(milli_secs / (1000 * 3600 * 24)) + 1;
                var total_price = customer.quantity * days * car.price_per_day;
                customer.start_date = start_date;
                customer.end_date = end_date;
                customer.days = days;
                customer.total_price = total_price;
                sessionStorage.setItem("customer", JSON.stringify(customer));
                $("#days").text("for " + days + " day(s)");
                $("#total_price").text("$" + parseFloat(total_price).toFixed(2));
                $("#form_days").val(days);
                $("#form_total").val(total_price);
                validDates = true;
                return 1;
            } else {
                customer.start_date = "";
                customer.end_date = "";
                customer.days = 0;
                customer.total_price = 0;
                sessionStorage.setItem("customer", JSON.stringify(customer));
                $("#days").text("");
                $("#total_price").text("$0.00");
                validDates = false;
                return -1;
            }
        }

        // Start date validation
        $("#start_date").change(function() {
            if ($("#end_date").val()) {
                if (calculateDaysNPrice() < 0) {
                    $("#start_date_error").text("* The start date must come before the end date.");
                    $("#end_date_error").text("");
                } else {
                    $("#start_date_error").text("");
                }
            }  
        });

        // End date validation
        $("#end_date").change(function() {
            if ($("#start_date").val()) {
                if (calculateDaysNPrice() < 0) {
                    $("#end_date_error").text("* The end date must come after the start date.");
                    $("#start_date_error").text("");
                } else {
                    $("#end_date_error").text("");
                }
            }  
        });

        // Cancel reservation and clear storage
        $("#cancel-reservation-btn").click(function() {
            sessionStorage.clear();
        });

        // Input validation
        $("#customer-info-form").validate({
            rules: {
                customer_name: {required: true},
                email: {required: true, email: true},
                contact: {required: true, digits: true},
                license: {required: true},
                start_date: {required: true},
                end_date: {required: true}
            },
            messages: {
                customer_name: {
                    required: "* Please enter your name.",
                },
                email: {
                    required: "* Please enter your e-mail.",
                    email: "* Please make sure the format is correct."
                },
                contact: {
                    required: "* Please enter your contact number.",
                    digits: "* Only numbers are allowed in this field."
                },
                license: {
                    required: "* You need to have a valid license to place an order."
                },
                start_date: {
                    required: "* Please select a date."
                },
                end_date: {
                    required: "* Please select a date."
                }
            }
        })
        
        // Update customer info in session storage
        $("#customer_name").change(function() {
            customer.name = $("#customer_name").val();
            sessionStorage.setItem("customer", JSON.stringify(customer));
        });

        $("#email").change(function() {
            customer.email = $("#email").val();
            sessionStorage.setItem("customer", JSON.stringify(customer));
        });

        $("#contact").change(function() {
            customer.phone = $("#contact").val();
            sessionStorage.setItem("customer", JSON.stringify(customer));
        });

        $("#license").change(function() {
            customer.license = $("#license").is(":checked");
            $("#form_license").val(customer.license);
            sessionStorage.setItem("customer", JSON.stringify(customer));
        });
    });
</script>

<div class = "container m-auto">
    @if ($reserve_state == "none")
    <div class = "row m-auto text-center" id = "no-reservation-notice">
        <div class = "col">
            <h5 class = "text-center text-secondary mb-4">You don't have any reservation at the moment.</h5>
            <a role = "button" type = "button" class = "btn btn-secondary" href = "{{ url('/') }}">RETURN</a>
        </div>
    </div>
    @else
    <h2 class = "title-font">Reservation</h2>
    <hr class = "mt-0">
    <!-- Car Information -->
    <form class = "m-auto" id = "customer-info-form" method = "post" action = "{{ url('place-order') }}">
        @csrf
        <input type = "text" name = "car" id = "car" value = "{{ $car['id'] }}" hidden>
        <input type = "text" name = "price_per_day" id = "price_per_day" value = "{{ $car['price_per_day'] }}" hidden>
        <div class = "row" id = "car-info">
            <div class = "col-4 px-0">
                <img src = "{{ asset($car['image']) }}" class = "card-img-top object-fit-scale border-bottom" height = "160">
            </div>
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
                        @if ($car['quantity'] > 0)
                        <span class = "fs-6 text-success w-100 py-2"><i class = "bi bi-check2-circle me-2"></i>Availabillity: Yes</span>
                        @else
                        <span class = "fs-6 text-danger text-end py-2"><i class = "bi bi-x-circle me-2"></i>Availabillity: No</span>
                        @endif
                    </div>
                    <!-- Quantity and dates input fields -->
                    <div class = "col-6">
                        <div id = "quantity-col" class = "row px-0 mb-2">
                            <div class = "col-5">
                                <p class = "py-1 my-auto me-2 align-middle">Quantity</p>
                            </div>
                            <div class = "col d-flex">
                                <a type = "button" class = "px-1 py-0 border rounded-start" id = "decrement-btn"><i class = "bi bi-dash align-middle"></i></a>
                                <p type = "number" id = "quantity" class = "py-1 px-3 my-auto border" name = "quantity"></p>
                                <input type = "text" id = "form_quantity" name = "form_quantity" value = "" hidden>
                                <a type = "button" class = "px-1 py-0 border rounded-end" id = "increment-btn"><i class = "bi bi-plus align-middle"></i></a>
                            </div>
                        </div>

                        <div class = "row my-0">
                            <div class = "col-5">
                                <label for = "start_date" class = "form-label form-label-sm">Start Date</label>
                            </div>
                            <div class = "col">
                                <input type = "date" class = "form-control form-control-sm border" id = "start_date" name = "start_date" value = "" placeholder = "DD-MMM-YYYY">
                                <div class = "mt-1 mb-2 m-auto error lh-sm text-justify" id = "start_date_error"></div>
                            </div>
                        </div>
                        <div class = "row my-0">
                            <div class = "col-5">
                                <label for = "end_date" class = "form-label form-label-sm">End Date</label>
                            </div>
                            <div class = "col">
                                <input type = "date" class = "form-control form-control-sm" id = "end_date" name = "end_date" value = "" placeholder = "DD-MMM-YYYY">
                                <div class = "mt-1 m-auto lh-sm text-justify error" id = "end_date_error"></div>
                            </div>
                        </div>
                        <input type = "text" id = "form_days" name = "form_days" value = "" hidden>
                        <input type = "text" id = "form_total" name = "form_total" value = "" hidden>
                    </div>
                </div>
                <div class = "row text-end mt-3 m-auto">
                    <div class = "col">
                        <h4 class = "d-inline me-1">Total Price</h4>
                        <p id = "days" class = "d-inline me-2"></p>
                        <h4 id = "total_price" class = "d-inline">$0.00</h4>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <!-- Customer Information Form -->
        <h3 class = "text-center w-100">Customer Information</h3>
        <div class = "row pb-3 m-auto">
            <div class = "mb-3">
                <label for = "customer_name" class = "form-label">Name</label>
                <input type = "text" class = "form-control" id = "customer_name" placeholder = "Name" name = "customer_name" value = "">
            </div>

            <div class = "mb-3">
                <label for = "email" class = "form-label">E-mail</label>
                <input type = "email" class = "form-control" id = "email" placeholder = "E-mail" name = "email" value = "">
            </div>

            <div class = "mb-3">
                <label for = "contact" class = "form-label">Contact Number</label>
                <input type = "tel" class = "form-control" id = "contact" placeholder = "Contact Number" name = "contact" value = "">
            </div>

            <div class = "mb-3">
                <div class = "form-check">
                    <input class = "form-check-input" name = "license" type = "checkbox" value = "" id = "license">
                    <input name = "form_license" id = "form_license" value = "" hidden>
                    <label class = "form-check-label" for = "license">I have a valid license.</label>
                    <label for = "license" class = "error" id = "license-error"></label>
                </div>
            </div>

            <div class = "row m-auto justify-content-between p-0">
                <div class = "col text-start">
                    <a id = "cancel-reservation-btn" type = "button" class = "btn btn-danger px-4" href = "{{ url('/clear-session') }}">CANCEL</a>    
                </div>
                <div class = "col text-end">
                    <button id = "place-order-btn" type = "submit" class = "btn btn-primary px-4">SUBMIT</button>
                </div> 
            </div>
        </div>
    </form>
    @endif
</div>
@endsection
