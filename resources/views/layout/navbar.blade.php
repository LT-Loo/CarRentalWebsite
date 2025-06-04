<!-- Navigation Bar -->

<script type = "text/javascript">
    $(document).ready(function() {
        // Show selected car category
        var current_grid = "#all-cars";
        $(".dropdown-item").click(function() {
            var id = "#";
            if ($(this).parent().parent().attr("id") == "car-types") {id += "type-" + $(this).text();}
            else {id += "brand-" + $(this).text();}
            if (current_grid != id) {
                $(id).removeAttr("hidden");
                $(current_grid).attr("hidden", true);
                current_grid = id;    
            }
        });

        $.ajaxSetup({headers: {"csrftoken": "{{ csrf_token() }}"}});

        // Realtime search suggestions on search bar
        $("#keyword").on("keyup click", function() {
            $value = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ url('/live-suggestion') }}",
                data: {"keyword": $value},
                success: function(response) {
                    var results = response.results;
                    var html = '<li><a class = "dropdown-item disabled text-secondary" aria-expanded = false>' + response.type + '</a></li>';
                    $.each(results, function(index, value) {
                        html += '<li><a class = "dropdown-item" type = "button" id = "search-item" aria-expanded = false>';
                        html += value; 
                        html += '</a></li>';
                    });
                    $("#search-suggestion").html(html);
                    $("#search-suggestion").addClass("expand");
                }
            });
        });

        // Select search item in search list
        $("#search-item").on("click", function() {
            console.log("click");
            var value = $(this).text();
            console.log(value);
            $("#search_key").val(value);
            $("#search-form").submit();
        });

        // Trigger view change when search icon is pressed
        $("#search-btn").on("click", function() {
            $("#search_key").val($("#keyword").val());
            $("#search-form").submit();
        });
    });

</script>

<!-- Navigation Menu -->
<nav class = "navbar navbar-expand-md fixed-top border-bottom p-1">
    <div class = "container-fluid m-auto" id = "navbar-container">
        <!-- Logo & Title -->
        <a class = "navbar-brand" href = "{{ url('/') }}">
            <img id = "logo" class = "d-inline-block align-text-top pe-1" src = "{{ asset('car_logo.png') }}" height = 25>
            <h2 class = "m-auto d-inline"><b>Car Rental</b></h2>
        </a>
        <button class = "navbar-toggler" type = "button" data-bs-toggle = "collapse" data-bs-target = "navbar" aria-expanded = "false">
            <span class = "navbar-toggler-icon"></span>
        </button>
        @if ($page == 'home')
        <div class = "collapse navbar-collapse d-flex">
            <!-- Car Type & Car Brand Categories -->
            <ul class = "navbar-nav me-auto">
                <li class = "nav-item dropdown me-1 ">
                    <a id = "type-btn" class = "nav-link btn py-1 mt-1" role = "button" data-bs-toggle = "dropdown" aria-expanded = "false">
                        Car Type
                    </a>
                    <ul class = "dropdown-menu" id = "car-types">
                        @foreach ($car_types as $key => $item)
                        <li><a class = "dropdown-item" role = "button" href = "#type-{{ $key }}">{{ $key }}</a></li>
                        @endforeach
                    </ul>
                </li>
                <li class = "nav-item dropdown me-2">
                    <a id = "brand-btn" class = "nav-link btn py-1 mt-1" role = "button" data-bs-toggle = "dropdown" aria-expanded = "false">
                        Car Brand
                    </a>
                    <ul class = "dropdown-menu" id = "car-brands">
                        <div class = "row row-cols-lg-5 row-cols-md-3 m-auto">
                            @foreach ($car_brands as $key => $item)
                            <li class = "col p-0"><a class = "dropdown-item text-wrap" href = "#brand-{{ $key }}">{{ $key }}</a></li>
                            @endforeach    
                        </div>
                    </ul>
                </li>
                <!-- Search Bar -->
                <li class = "nav-item dropdown">
                    <div class = "d-flex m-auto mt-1">
                        <input type = "text" class = "form-control form-control-sm me-1 border border-secondary" role = "button" data-bs-toggle = "dropdown" aria-expanded = false id = "keyword" name = "keyword" value = "" type = "search" placeholder = "Search car..." aria-label = "Search">
                        <ul class = "dropdown-menu" id = "search-suggestion"></ul> 
                        <button class = "btn btn-sm btn-outline-success" type = "button" id = "search-btn"><i class="bi bi-search"></i></button>
                    </div>
                </li>
            </ul>
        </div>
        @endif
        <!-- Reservation Button -->
        <form method = "post" action = "{{ url('/reservation') }}">
            @csrf
            <input hidden id = "from_nav" name = "from_nav" value = "true" type = "text">
            <button id = "booking-btn" role = "button" class = "btn btn-primary me-2 py-1" type = "submit">Reservation</button>
        </form>
    </div>

    <!-- Hidden form for search bar -->
    <form id = "search-form" method = "post" action = "{{ url('/search') }}" hidden>
        @csrf
        <input type = "text" id = "search_key" name = "search_key" value = "">
    </form>
</nav>
