<!-- Alert Message Box -->
<!-- Inform customer that vehicle chosen is unavailable -->

<script type = "text/javascript">
    $(document).ready(function() {
        $("#close-alert").click(function() {
            sessionStorage.clear();
        });
    });
</script>

<div class = "alert alert-danger text-center px-0 fade" role = "alert" id = "alert-message">
    <div class = "row border m-auto pt-3">
        <h5 class = "alert-heading">Sorry! Vehicle Unavailable!</h5>
        <p>Please select another model.</p>
    </div>
    <div class = "pb-3">
        <button id = "close-alert" class = "btn btn-danger" type = "button" data-bs-dismiss = "alert">OKAY</button>
    </div>
</div>