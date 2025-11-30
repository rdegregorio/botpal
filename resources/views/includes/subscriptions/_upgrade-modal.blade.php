@if(Auth::check() && Auth::user()->getCurrentActiveSubscription()?->isFree())
    <div class="modal fade" id="upgradeModal" tabindex="-1" aria-labelledby="upgradeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Use modal-lg for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        Please upgrade your subscription.
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column" style="height: 400px;">
                    Upgrade required to use this feature.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <a href="/account"><button type="button" class="btn btn-primary">Upgrade</button></a>
                </div>
            </div>
        </div>
    </div>

    <script>
      window.showUpgradeModal = () => {
        $('#upgradeModal').modal('show');
      };
      $(function(e) {
        $(document).on('click', '[data-premium]', function(e) {
          e.preventDefault();
          e.stopPropagation();
          window.showUpgradeModal();
        });
      });
    </script>
@endif