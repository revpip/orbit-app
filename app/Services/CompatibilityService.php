<?php

declare(strict_types=1);

namespace Orbit\Services;

final class CompatibilityService
{
    public function score(array $current, array $candidate): array
    {
        $score = 40;
        $reasons = [];

        foreach (['communication_style', 'social_energy', 'conflict_style', 'humour_style'] as $field) {
            if (!empty($current[$field]) && $current[$field] === ($candidate[$field] ?? null)) {
                $score += 10;
                $reasons[] = $this->label($field) . ' aligns';
            }
        }

        foreach (['reliability_self_score', 'openness_score', 'boundaries_score'] as $field) {
            $a = (int) ($current[$field] ?? 0);
            $b = (int) ($candidate[$field] ?? 0);
            if ($a > 0 && $b > 0) {
                $difference = abs($a - $b);
                if ($difference <= 1) {
                    $score += 7;
                    $reasons[] = $this->label($field) . ' feels closely matched';
                } elseif ($difference <= 3) {
                    $score += 3;
                }
            }
        }

        $score = max(0, min(99, $score));

        if ($reasons === []) {
            $reasons[] = 'Some early compatibility signals are present';
        }

        return [
            'score' => $score,
            'reason' => implode('. ', array_slice($reasons, 0, 3)) . '.',
        ];
    }

    private function label(string $field): string
    {
        return match ($field) {
            'communication_style' => 'Communication style',
            'social_energy' => 'Social energy',
            'conflict_style' => 'Conflict style',
            'humour_style' => 'Humour style',
            'reliability_self_score' => 'Reliability',
            'openness_score' => 'Openness',
            'boundaries_score' => 'Boundary style',
            default => 'Compatibility',
        };
    }
}
