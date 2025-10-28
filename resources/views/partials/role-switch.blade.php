<form action="{{ route('role.switch') }}" method="POST" class="d-flex align-items-center ms-3">
    @csrf
    <label for="role-switch" class="me-2 mb-0 text-muted small fw-semibold">Rolle wechseln:</label>
    <select id="role-switch"
            name="role"
            onchange="this.form.submit()"
            class="form-select form-select-sm bg-white border border-secondary-subtle rounded-3 shadow-sm"
            style="width: auto; font-size: 0.85rem; padding: 0.35rem 1.2rem; min-width: 120px;">
        @foreach(['leitung' => 'Leitung', 'mitarbeiter' => 'Mitarbeiter', 'techniker' => 'Techniker'] as $value => $label)
            <option value="{{ $value }}" {{ auth()->user()->role === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</form>
