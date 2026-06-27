@extends('layouts.admin')

@section('content')
<div class="row">
    @if(session('success'))
        <div class="col-12 mb-3">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif

    <!-- Nustatymai -->
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-cog"></i> {{ __('Laimės rato nustatymai') }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.lucky-wheel.settings.update') }}" method="POST">
                    @csrf
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_enabled" id="is_enabled" value="1" {{ $settings->is_enabled ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_enabled">{{ __('Įjungti Laimės ratą svetainėje') }}</label>
                    </div>

                    <div class="mb-3">
                        <label for="theme" class="form-label">{{ __('Dizaino tema') }}</label>
                        <select name="theme" id="theme" class="form-control">
                            <option value="default" {{ $settings->theme === 'default' ? 'selected' : '' }}>🌟 Moderni (Tamsi / Auksinė)</option>
                            <option value="christmas" {{ $settings->theme === 'christmas' ? 'selected' : '' }}>🎄 Kalėdinė (Snaigės ir Šventės)</option>
                            <option value="summer" {{ $settings->theme === 'summer' ? 'selected' : '' }}>🏖️ Vasaros (Saulėta ir Karšta)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="email_policy" class="form-label">{{ __('El. pašto reikalavimas') }}</label>
                        <select name="email_policy" id="email_policy" class="form-control">
                            <option value="none" {{ $settings->email_policy === 'none' ? 'selected' : '' }}>{{ __('Nereikalauti (Svečiai gali sukti iškart)') }}</option>
                            <option value="before_spin" {{ $settings->email_policy === 'before_spin' ? 'selected' : '' }}>{{ __('Reikalauti prieš sukant ratą') }}</option>
                            <option value="after_win" {{ $settings->email_policy === 'after_win' ? 'selected' : '' }}>{{ __('Reikalauti po rato sukimo (atsiimant prizą)') }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cooldown_hours" class="form-label">{{ __('Pakartotinio sukimo ribojimas (valandomis)') }}</label>
                        <input type="number" name="cooldown_hours" id="cooldown_hours" class="form-control" value="{{ $settings->cooldown_hours }}" min="0">
                        <small class="text-muted">{{ __('0 reiškia, kad ribojimo nėra') }}</small>
                    </div>

                    <div class="mb-3">
                        <label for="starts_at" class="form-label">{{ __('Rodyti nuo (neprivaloma)') }}</label>
                        <input type="datetime-local" name="starts_at" id="starts_at" class="form-control" value="{{ $settings->starts_at ? $settings->starts_at->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="ends_at" class="form-label">{{ __('Rodyti iki (neprivaloma)') }}</label>
                        <input type="datetime-local" name="ends_at" id="ends_at" class="form-control" value="{{ $settings->ends_at ? $settings->ends_at->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">{{ __('Išsaugoti nustatymus') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Prizai -->
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-gift"></i> {{ __('Rato prizai ir tikimybės') }}</span>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Pavadinimas') }}</th>
                            <th>{{ __('Tipas') }}</th>
                            <th>{{ __('Vertė') }}</th>
                            <th>{{ __('Svoris') }}</th>
                            <th>{{ __('Spalva') }}</th>
                            <th>{{ __('Veiksmai') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prizes as $prize)
                            <tr>
                                <td class="fw-bold">{{ $prize->title }}</td>
                                <td>{{ $prize->type }}</td>
                                <td>{{ $prize->value ?? '-' }}</td>
                                <td><span class="badge bg-info">{{ $prize->probability_weight }}</span></td>
                                <td>
                                    <span style="display:inline-block;width:20px;height:20px;background:{{ $prize->bg_color }};border:1px solid #ccc;vertical-align:middle;"></span>
                                    <small>{{ $prize->bg_color }}</small>
                                </td>
                                <td>
                                    <form action="{{ route('admin.lucky-wheel.prizes.destroy', $prize) }}" method="POST" onsubmit="return confirm('Ar tikrai pašalinti?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('Prizų dar nėra pridėta.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <hr>
                <h5>{{ __('Pridėti naują prizą') }}</h5>
                <form action="{{ route('admin.lucky-wheel.prizes.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">{{ __('Pavadinimas') }}</label>
                            <input type="text" name="title" class="form-control" placeholder="pvz. 10% Nuolaida" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">{{ __('Prizo tipas') }}</label>
                            <select name="type" class="form-control">
                                <option value="percentage">Procentinė nuolaida (%)</option>
                                <option value="fixed">Fiksuota nuolaida (€)</option>
                                <option value="free_shipping">Nemokamas pristatymas</option>
                                <option value="no_prize">Nėra prizo (Bandyti dar kartą)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">{{ __('Vertė (skaičius)') }}</label>
                            <input type="number" step="0.01" name="value" class="form-control" placeholder="pvz. 10">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">{{ __('Tikimybės svoris') }}</label>
                            <input type="number" name="probability_weight" class="form-control" value="10" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">{{ __('Fonas') }}</label>
                            <input type="color" name="bg_color" class="form-control form-control-color" value="#3b82f6" title="Pasirinkite fono spalvą">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">{{ __('Tekstas') }}</label>
                            <input type="color" name="text_color" class="form-control form-control-color" value="#ffffff" title="Pasirinkite teksto spalvą">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-2"><i class="fas fa-plus"></i> {{ __('Pridėti prizą') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
