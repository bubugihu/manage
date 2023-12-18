<!-- Modal -->
<div class="modal fade" id="alertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center">Alert Dialog</h1>
            </div>
            <div class="modal-body">
                <p id="messageAlertModal" class="text-center"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function modalAlert(message) {
        $("#messageAlertModal").empty().append(message)
        $("#alertModal").modal("show")
    }
</script>
