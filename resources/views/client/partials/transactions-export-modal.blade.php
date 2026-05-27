@php
    $today = today();
    $defaultStart = $today->copy()->startOfMonth()->format('Y-m-d');
    $defaultEnd = $today->format('Y-m-d');
@endphp
<div class="modal fade fd-trans-export-modal" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered fd-trans-export-modal__dialog">
        <form method="GET" action="{{ route('transactions.download') }}" class="modal-content fd-trans-export-modal__content">
            <div class="modal-header fd-trans-export-modal__header">
                <div class="fd-trans-export-modal__title-wrap">
                    <span class="fd-trans-export-modal__title-icon" aria-hidden="true">
                        <i class="fa fa-download" aria-hidden="true"></i>
                    </span>
                    <h5 class="modal-title" id="downloadModalLabel">Export to Excel</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fd-trans-export-modal__body">
                <div class="fd-trans-export-presets" role="group" aria-label="Quick date ranges">
                    <button type="button" class="fd-trans-export-preset" data-range="thisMonth">This month</button>
                    <button type="button" class="fd-trans-export-preset" data-range="lastMonth">Last month</button>
                    <button type="button" class="fd-trans-export-preset" data-range="last7">Last 7 days</button>
                    <button type="button" class="fd-trans-export-preset" data-range="last30">Last 30 days</button>
                </div>
                <div class="fd-trans-export-dates">
                    <div class="row g-2">
                        <div class="col-6">
                            <label for="export_start_date" class="fd-trans-export-modal__field-label">From</label>
                            <input type="date" name="start_date" id="export_start_date" class="form-control fd-trans-export-date"
                                value="{{ old('start_date', $defaultStart) }}" required max="{{ $defaultEnd }}">
                            @error('start_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="export_end_date" class="fd-trans-export-modal__field-label">To</label>
                            <input type="date" name="end_date" id="export_end_date" class="form-control fd-trans-export-date"
                                value="{{ old('end_date', $defaultEnd) }}" required max="{{ $defaultEnd }}">
                            @error('end_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer fd-trans-export-modal__footer">
                <button type="button" class="fd-trans-export-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="fd-trans-export-submit">
                    <i class="bi bi-download" aria-hidden="true"></i>
                    <span>Download</span>
                </button>
            </div>
        </form>
    </div>
</div>
<script>
(function () {
    var modal = document.getElementById('downloadModal');
    if (!modal) return;

    var startInput = document.getElementById('export_start_date');
    var endInput = document.getElementById('export_end_date');
    var maxDate = endInput.getAttribute('max');

    function formatDate(d) {
        var y = d.getFullYear();
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var day = String(d.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + day;
    }

    function clearActivePresets() {
        modal.querySelectorAll('.fd-trans-export-preset').forEach(function (btn) {
            btn.classList.remove('is-active');
        });
    }

    modal.querySelectorAll('.fd-trans-export-preset').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var now = new Date();
            var start, end;
            switch (btn.getAttribute('data-range')) {
                case 'thisMonth':
                    start = new Date(now.getFullYear(), now.getMonth(), 1);
                    end = now;
                    break;
                case 'lastMonth':
                    start = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                    end = new Date(now.getFullYear(), now.getMonth(), 0);
                    break;
                case 'last7':
                    end = now;
                    start = new Date(now);
                    start.setDate(start.getDate() - 6);
                    break;
                case 'last30':
                    end = now;
                    start = new Date(now);
                    start.setDate(start.getDate() - 29);
                    break;
            }
            startInput.value = formatDate(start);
            endInput.value = formatDate(end);
            clearActivePresets();
            btn.classList.add('is-active');
        });
    });

    startInput.addEventListener('change', function () {
        if (startInput.value > endInput.value) endInput.value = startInput.value;
        clearActivePresets();
    });
    endInput.addEventListener('change', function () {
        if (endInput.value > maxDate) endInput.value = maxDate;
        if (startInput.value > endInput.value) startInput.value = endInput.value;
        clearActivePresets();
    });
})();

@if ($errors->has('start_date') || $errors->has('end_date'))
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('downloadModal');
    if (el && typeof bootstrap !== 'undefined') {
        bootstrap.Modal.getOrCreateInstance(el).show();
    }
});
@endif
</script>
