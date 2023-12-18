<!-- Modal -->
<div class="modal fade" id="confirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-center">Confirmation Dialog</h1>
            </div>
            <div class="modal-body">
                <p id="messageConfirmationModal" class="text-center text-break"></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-ok-confirmation-dialog" class="btn btn-primary me-3">
                    <i class="fa-solid fa-check"></i>
                    OK
                </button>
                <button type="button" id="btn-cancel-confirmation-dialog" class="btn btn-secondary">
                    <i class="fa-solid fa-xmark"></i>
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function modalConfirmation(message, onConfirm) {
        var fClose = function(){
            modal.modal("hide")
        }
        var modal = $("#confirmationModal")
        modal.modal("show")

        $("#messageConfirmationModal").empty().append(message)
        $("#btn-ok-confirmation-dialog").unbind().one('click', onConfirm).one('click', fClose)
        $("#btn-cancel-confirmation-dialog").unbind().one("click", fClose)
    }
</script>
