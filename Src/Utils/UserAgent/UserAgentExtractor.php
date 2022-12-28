<?php

namespace Zakharov\Yii2SeleniumTools\Utils\UserAgent;

use yii\helpers\StringHelper;

class UserAgentExtractor
{
    /**
     * extract user agents strings from tne html
     *
     * @param  string $html
     * @return array
     */
    public function extract(string $html): array
    {
        preg_match_all('/<a\s?[^>]*title="([^"]+)"/', $html, $matches);
        $userAgents = array_pop($matches);
        return array_filter($userAgents, function ($item) {
            return StringHelper::startsWith($item, 'Mozilla/5.0', false);
        });
    }
}
