<div class="overview-panel col-sm-6 col-lg-4">
    <div class="overview-panel-head {{ $color ?? 'gray' }}">
        <i class="overview-panel-icon fa fa-{{ $icon ?? 'question' }}"></i>
    </div>
    <div class="overview-panel-body">
        <p class="overview-panel-text">
            {{ $text }}:
            <span class="overview-panel-value">{{ $value }}</span>
        </p>
    </div>
</div>