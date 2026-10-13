<?php

namespace App\Models\Concerns;

trait TranslatableJson
{
    /**
     * Retrieve the translated string for a JSON column based on current or requested locale.
     */
    public function getTranslated(string $attribute, ?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $values = $this->{$attribute};

        if (is_array($values)) {
            if (!empty($values[$locale])) {
                return (string) $values[$locale];
            }
            $fallback = config('app.fallback_locale', 'en');
            if (!empty($values[$fallback])) {
                return (string) $values[$fallback];
            }
            $first = reset($values);
            return is_string($first) ? $first : '';
        }

        if (is_string($values)) {
            $decoded = json_decode($values, true);
            if (is_array($decoded)) {
                return (string) ($decoded[$locale] ?? $decoded[config('app.fallback_locale', 'en')] ?? reset($decoded) ?: '');
            }
            return $values;
        }

        return '';
    }

    /**
     * Convenience accessor used by admin/public views for the `title` column.
     */
    public function getTitle(?string $locale = null): string
    {
        return $this->getTranslated('title', $locale);
    }
}
