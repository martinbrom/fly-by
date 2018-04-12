@if (isset($name))
    <div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
        @if (isset($label))
            <label for="{{ $name }}">{{ $label }}</label>
        @endif
        <input type="{{ $type ?? 'text' }}" id="{{ $name }}" name="{{ $name }}" value="{{ $value ?? '' }}" class="form-control {{ $class ?? '' }}">
        @if ($errors->has($name))
            <p class="error-message">{{ $errors->first($name) }}</p>
        @endif
    </div>
@endif
