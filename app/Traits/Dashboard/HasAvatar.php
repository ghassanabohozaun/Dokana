<?php
/**
 * Trait HasAvatar
 * Provides helper methods for displaying initials and colors in premium UI components.
 */

namespace App\Traits\Dashboard;

trait HasAvatar
{
    /**
     * Get the initials for the model name.
     */
    public function getInitialsAttribute()
    {
        $name = method_exists($this, 'getTranslation') 
                ? ($this->getTranslation('name', app()->getLocale()) ?: $this->name)
                : $this->name;

        if (!$name) return '??';

        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }
        return mb_strtoupper(mb_substr($name, 0, 1));
    }

    /**
     * Get a deterministic background color for the avatar based on the model name.
     */
    public function getAvatarColor()
    {
        $colors = ['#5A8DEE', '#FDAC41', '#FF5B5C', '#39DA8A', '#00CFDD', '#7117EA', '#272727'];
        $name = method_exists($this, 'getTranslation') 
                ? ($this->getTranslation('name', 'en') ?: ($this->getTranslation('name', 'ar') ?: $this->name))
                : $this->name;
        
        $charIndex = abs(crc32($name)) % count($colors);
        return $colors[$charIndex];
    }
}
