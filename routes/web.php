<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Order;

// Homepage
Route::get('/', function () {
    $cars = File::json(base_path("storage/cars.json"));
    $car_types = [];
    $car_brands = [];
    foreach ($cars as $car) {
        $car_types[$car["type"]][] = $car;
        $car_brands[$car["brand"]][] = $car;
    }
    ksort($car_types);
    ksort($car_brands);

    return view("pages.home")->with("page", "home")->with('car_types', $car_types)->with('car_brands', $car_brands)->with("current_grid", "#all-cars");
});

// Reservation page
Route::post('/reservation', function (Request $request) {
    $reserve_state = "none";
    $car = null;
    if ($request->has("from_nav") && $request->from_nav) {
        if (session()->get("reservation.car") != null) {
            $reserve_state = "reserve";
            $car = session()->get("reservation.car");
        }
    } else if ($request->has("new_rent") && $request->new_rent) {
        $cars = File::json(base_path("storage/cars.json"));
        $car = $cars[$request->car_id-1];
        session(["reservation.car" => $car]);
        $reserve_state = "new";
    }
    
    return view("pages.reservation")->with("page", "reservation")->with("car", $car)->with("reserve_state", $reserve_state);
});

// Get car information
Route::get('/get-car', function() {
    return response()->json(session("reservation.car"));
});

// Place order and display order confirmation page
Route::post('/place-order', function(Request $request) {

    $cars = File::json(base_path("storage/cars.json"));
    $car = $cars[$request->car-1];

    if ($car["quantity"] - $request->form_quantity < 0) {
        session()->forget("reservation");
        return redirect()->to('/')->with("unavailable", true);
    }

    $order = Order::create([
        "customer_name" => $request->customer_name,
        "email" => $request->email,
        "phone" => $request->contact,
        "license" => $request->form_license,
        "car_id" => $request->car,
        "quantity" => $request->form_quantity,
        "price_per_day" => $request->price_per_day,
        "start_date" => $request->start_date,
        "end_date" => $request->end_date,
        "days" => $request->form_days,
        "total_price" => $request->form_total,
        "status" => "unconfirmed"
    ]);

    session()->forget("reservation");

    return view("pages.confirm-order")->with("page", "confirm-order")->with("order", $order)->with("car", $car);

});

// Confirm order, update database & JSON
Route::get('/confirm-order/{id}', function($id) {
    $order = Order::Find($id);
    $order->status = "confirmed";
    $order->save();

    $cars = File::json(base_path("storage/cars.json"));
    $cars[$order->car_id-1]['quantity'] -= $order->quantity;
    File::put(base_path("storage/cars.json"), json_encode($cars));

    return redirect()->to('/');
});

// Clear storage session
Route::get('/clear-session', function() {
    session()->forget("reservation");
    return redirect()->to('/');
});

// Realtime search suggestion/history
Route::get('/live-suggestion', function(Request $request) {

    if ($request->ajax()) {
        
        $cars = File::json(base_path("storage/cars.json"));
        $car_types = [];
        $car_brands = [];
        $car_models = [];
        foreach ($cars as $car) {
            if (!in_array($car["type"], $car_types)) {$car_types[] = $car["type"];}
            if (!in_array($car["brand"], $car_brands)) {$car_brands[] = $car["brand"];}
            if (!in_array($car["model"], $car_models)) {$car_models[] = $car["model"];}
        }
        ksort($car_types);
        ksort($car_brands);
        ksort($car_models);
    
        $results = [];
        $type = "";
        $keyword = strtolower($request->keyword);
        $len = strlen($keyword);
        if ($len > 0) {
            foreach($car_types as $type) {
                if (strtolower(substr($type, 0, $len)) == $keyword) {
                    $results[] = $type;
                } else if (stripos(strtolower($type), $keyword)) {
                    $results[] = $type;
                }
            }
            foreach($car_models as $model) {
                if (strtolower(substr($model, 0, $len)) == $keyword) {
                    $results[] = $model;
                } else if (stripos(strtolower($model), $keyword)) {
                    $results[] = $model;
                }

            }
            foreach($car_brands as $brand) {
                if (strtolower(substr($brand, 0, $len)) == $keyword) {
                    $results[] = $brand;
                } else if (stripos(strtolower($brand), $keyword)) {
                    $results[] = $brand;
                }
            }
            $type = "Suggestions";
            usort($results, "customSort");
        } else if ($len == 0 || count($results) == 0) {
            $results = session()->get("history");    
            $type = "Recent Searches";
        }

        if (sizeof($results) > 6) {array_slice($results, 6);}
        else {array_slice($results, sizeof($results));}

        return response()->json(["results" => $results, "type" => $type]);
    }

});

// Search function
Route::post("/search", function(Request $request) {

    $cars = File::json(base_path("storage/cars.json"));

    $keyword = strtolower($request->search_key);
    $results = [];
    foreach ($cars as $car) {
        if (str_contains(strtolower($car["type"]), $keyword) ||
            str_contains(strtolower($car["model"]), $keyword) ||
            str_contains(strtolower($car["brand"]), $keyword) ||
            str_contains(strtolower($car["description"]), $keyword) ||
            str_contains(strtolower($car["mileage"]), $keyword)) {
                $results[] = $car;
            }
    }

    $history = session()->get('history');
    if (!in_array($keyword, $history)) {
        array_unshift($history, $keyword); 
    }
    session(["history" => $history]);

    if ($keyword == "") {$results = $cars;}

    return back()->with("keyword", $keyword)->with("search_results", $results);

});
