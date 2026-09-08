<?php

namespace TantHammar\FilamentCountrySelect\Tables\Columns;

use Filament\Support\Components\Contracts\HasEmbeddedView;
use Filament\Support\Concerns\CanWrap;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\Column;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Js;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryData;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryList;
use TantHammar\FilamentCountrySelect\Concerns\HasFlags;
use TantHammar\FilamentCountrySelect\Concerns\HasPhoneCode;

/**
 * Rendered as an embedded view rather than from a blade file, the way Filament's own
 * ColorColumn is, so a table pays no view render per row.
 */
class CountryColumn extends Column implements HasEmbeddedView
{
    use CanWrap;
    use HasCountryData;
    use HasCountryList;
    use HasFlags;
    use HasPhoneCode;

    public function toEmbeddedHtml(): string
    {
        $state = $this->getState();
        $alignment = $this->getAlignment();

        $attributes = $this->getExtraAttributeBag()
            ->merge([
                'x-tooltip' => filled($tooltip = $this->getTooltip($state))
                    ? '{
                        content: '.Js::from($tooltip).',
                        theme: $store.theme,
                        allowHTML: '.Js::from($tooltip instanceof Htmlable).',
                    }'
                    : null,
            ], escape: false)
            ->class([
                'fi-ta-country flex items-center gap-x-2',
                'fi-inline' => $this->isInline(),
                'fi-wrapped' => $this->canWrap(),
                ($alignment instanceof Alignment) ? "fi-align-{$alignment->value}" : (is_string($alignment) ? $alignment : ''),
            ]);

        if (blank($state)) {
            $placeholder = $this->getPlaceholder();

            ob_start(); ?>

            <div <?= $attributes->toHtml() ?>>
                <?php if (filled($placeholder)) { ?>
                    <p class="fi-ta-placeholder"><?= e($placeholder) ?></p>
                <?php } ?>
            </div>

            <?php return ob_get_clean();
        }

        $flag = $this->getShowFlags() ? $this->getCountryFlagUrl($state) : null;

        ob_start(); ?>

        <div <?= $attributes->toHtml() ?>>
            <?php if (filled($flag)) { ?>
                <img src="<?= e($flag) ?>" alt="" width="24" height="20" class="h-5 w-6 shrink-0 object-contain" loading="lazy">
            <?php } ?>

            <span class="fi-ta-text-item-label">
                <?= e($this->getCountryLabel($state) ?? $state) ?>
            </span>
        </div>

        <?php return ob_get_clean();
    }
}
